from __future__ import annotations

import json
from dataclasses import dataclass
from pathlib import Path
import argparse

import joblib
import pandas as pd
from sklearn.compose import ColumnTransformer
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import classification_report
from sklearn.model_selection import train_test_split
from sklearn.pipeline import Pipeline
from sklearn.preprocessing import OneHotEncoder


ROOT = Path(__file__).resolve().parents[1]
DATASET_PATH = ROOT / "CareerRecommenderDataset.csv"
ARTIFACTS_DIR = ROOT / "artifacts"


@dataclass(frozen=True)
class TrainingConfig:
    target_col: str = "Courses"
    career_options_col: str = "Career_Options"
    test_size: float = 0.2
    random_state: int = 42

    n_estimators: int = 500
    max_depth: int | None = None
    min_samples_split: int = 2
    min_samples_leaf: int = 1
    n_jobs: int = -1


def _load_dataset(path: Path) -> pd.DataFrame:
    if not path.exists():
        raise FileNotFoundError(
            "Dataset not found.\n"
            f"- Tried: {path}\n"
            "Fix: pass the path explicitly, for example:\n"
            "  python app/train_model.py --data \".\\CareerRecommenderDataset.csv\"\n"
        )
    df = pd.read_csv(path)
    df.columns = [c.strip() for c in df.columns]
    return df


def _build_pipeline(feature_cols: list[str], config: TrainingConfig) -> Pipeline:
    pre = ColumnTransformer(
        transformers=[
            (
                "cat",
                OneHotEncoder(handle_unknown="ignore", sparse_output=False),
                feature_cols,
            )
        ],
        remainder="drop",
    )

    clf = RandomForestClassifier(
        n_estimators=config.n_estimators,
        max_depth=config.max_depth,
        min_samples_split=config.min_samples_split,
        min_samples_leaf=config.min_samples_leaf,
        random_state=config.random_state,
        n_jobs=config.n_jobs,
        class_weight="balanced_subsample",
    )

    return Pipeline([("preprocess", pre), ("model", clf)])


def train_and_save(
    config: TrainingConfig = TrainingConfig(),
    dataset_path: Path = DATASET_PATH,
    artifacts_dir: Path = ARTIFACTS_DIR,
) -> dict:
    df = _load_dataset(dataset_path)

    for col in [config.target_col, config.career_options_col]:
        if col not in df.columns:
            raise ValueError(
                f"Expected column '{col}' not found. Columns: {list(df.columns)}"
            )

    feature_cols = [
        c for c in df.columns if c not in [config.target_col, config.career_options_col]
    ]

    X = df[feature_cols].copy()
    y = df[config.target_col].astype(str).copy()

    # استخدم stratify فقط إذا كل فئة تحتوي على عينتين على الأقل
    min_class_count = y.value_counts().min()
    use_stratify = y.nunique() > 1 and min_class_count >= 2

    X_train, X_test, y_train, y_test = train_test_split(
        X,
        y,
        test_size=config.test_size,
        random_state=config.random_state,
        stratify=y if use_stratify else None,
    )

    pipe = _build_pipeline(feature_cols, config)
    pipe.fit(X_train, y_train)

    y_pred = pipe.predict(X_test)
    report = classification_report(y_test, y_pred, output_dict=True, zero_division=0)

    course_to_options = (
        df[[config.target_col, config.career_options_col]]
        .dropna()
        .assign(**{config.target_col: lambda d: d[config.target_col].astype(str)})
        .groupby(config.target_col)[config.career_options_col]
        .agg(lambda s: s.value_counts().index[0])
        .to_dict()
    )

    artifacts_dir.mkdir(parents=True, exist_ok=True)
    model_path = artifacts_dir / "career_rf_pipeline.joblib"
    meta_path = artifacts_dir / "metadata.json"

    joblib.dump(pipe, model_path)
    meta = {
        "dataset_path": str(dataset_path),
        "target_col": config.target_col,
        "career_options_col": config.career_options_col,
        "feature_cols": feature_cols,
        "course_to_options": course_to_options,
        "metrics": {
            "accuracy": report.get("accuracy"),
            "macro_avg_f1": report.get("macro avg", {}).get("f1-score"),
            "weighted_avg_f1": report.get("weighted avg", {}).get("f1-score"),
        },
    }
    meta_path.write_text(json.dumps(meta, ensure_ascii=False, indent=2), encoding="utf-8")

    return {"model_path": str(model_path), "meta_path": str(meta_path), "metrics": meta["metrics"]}


if __name__ == "__main__":
    ap = argparse.ArgumentParser()
    ap.add_argument(
        "--data",
        default=str(DATASET_PATH),
        help="Path to CareerRecommenderDataset.csv",
    )
    ap.add_argument(
        "--artifacts",
        default=str(ARTIFACTS_DIR),
        help="Directory to write model artifacts",
    )
    args = ap.parse_args()

    out = train_and_save(
        dataset_path=Path(args.data),
        artifacts_dir=Path(args.artifacts),
    )
    print("Saved: artifacts/career_rf_pipeline.joblib")
    print("Saved: artifacts/metadata.json")
    print("Metrics:", out["metrics"])
