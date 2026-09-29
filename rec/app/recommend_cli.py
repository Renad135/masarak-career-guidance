from __future__ import annotations

import json
from pathlib import Path
import argparse

import joblib
import pandas as pd


ROOT = Path(__file__).resolve().parents[1]
DEFAULT_ARTIFACTS_DIR = ROOT / "artifacts"


def _find_artifacts_dir(artifacts_dir: Path) -> Path:
    """يبحث عن مجلد يحتوي على النموذج والميتاداتا: أولاً artifacts/ ثم جذر المشروع."""
    for base in (artifacts_dir, ROOT):
        if (base / "career_rf_pipeline.joblib").exists() and (base / "metadata.json").exists():
            return base
    return artifacts_dir  # ليرمي الخطأ التالي مع المسار المطلوب


def _load_artifacts(artifacts_dir: Path) -> tuple[object, dict]:
    artifacts_dir = _find_artifacts_dir(artifacts_dir)
    model_path = artifacts_dir / "career_rf_pipeline.joblib"
    meta_path = artifacts_dir / "metadata.json"
    if not model_path.exists() or not meta_path.exists():
        raise FileNotFoundError(
            "Model artifacts not found. Train first:\n"
            "  python app/train_model.py --data \"CareerRecommenderDataset.csv\""
        )
    pipe = joblib.load(model_path)
    meta = json.loads(meta_path.read_text(encoding="utf-8"))
    return pipe, meta


def _ask_yes_no(prompt: str) -> str:
    while True:
        v = input(f"{prompt} [y/n]: ").strip().lower()
        if v in {"y", "yes"}:
            return "Yes"
        if v in {"n", "no"}:
            return "No"
        print("Please type y or n.")


def build_answers_interactively(feature_cols: list[str]) -> dict[str, str]:
    answers: dict[str, str] = {}
    print("\nAnswer the questions (y/n). Press Ctrl+C to cancel.\n")
    for col in feature_cols:
        label = col.replace("_", " ")
        answers[col] = _ask_yes_no(f"Do you like / are you interested in: {label}?")
    return answers


def recommend_from_answers(
    answers: dict[str, str], top_k: int = 5, artifacts_dir: Path = DEFAULT_ARTIFACTS_DIR
) -> list[dict[str, object]]:
    pipe, meta = _load_artifacts(artifacts_dir)
    feature_cols: list[str] = meta["feature_cols"]
    course_to_options: dict[str, str] = meta.get("course_to_options", {})

    row = {c: answers.get(c, "No") for c in feature_cols}
    X = pd.DataFrame([row], columns=feature_cols)

    probs = pipe.predict_proba(X)[0]
    classes = [str(c) for c in pipe.classes_]
    scored = sorted(zip(classes, probs), key=lambda t: t[1], reverse=True)[:top_k]

    out: list[dict[str, object]] = []
    for course, p in scored:
        out.append(
            {
                "course": course,
                "probability": float(p),
                "career_options": course_to_options.get(course),
            }
        )
    return out


def main() -> None:
    ap = argparse.ArgumentParser()
    ap.add_argument(
        "--artifacts",
        default=str(DEFAULT_ARTIFACTS_DIR),
        help="Directory containing metadata.json + career_rf_pipeline.joblib",
    )
    ap.add_argument("--top-k", type=int, default=5, help="Number of recommendations")
    args = ap.parse_args()

    artifacts_dir = Path(args.artifacts)
    _, meta = _load_artifacts(artifacts_dir)
    feature_cols: list[str] = meta["feature_cols"]

    answers = build_answers_interactively(feature_cols)
    recs = recommend_from_answers(answers, top_k=args.top_k, artifacts_dir=artifacts_dir)

    print("\nTop recommendations:\n")
    for i, r in enumerate(recs, start=1):
        print(f"{i}. {r['course']} ({r['probability']*100:.1f}%)")
        if r.get("career_options"):
            print(f"   Career options: {r['career_options']}")


if __name__ == "__main__":
    main()

