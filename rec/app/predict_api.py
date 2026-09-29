# -*- coding: utf-8 -*-
"""
استدعاء من PHP: يقرأ إجابات الاستبيان كـ JSON من stdin ويطبع التوصيات كـ JSON على stdout.
Example: echo {"Coding":"Yes",...} | python app/predict_api.py
"""
from __future__ import annotations

import json
import sys
from pathlib import Path

# جذر المشروع = المجلد الذي فيه app/ و artifacts/
ROOT = Path(__file__).resolve().parents[1]
ARTIFACTS_DIR = ROOT / "artifacts"
# predict_api يستخدم نفس _find_artifacts_dir عبر recommend_from_answers


def main() -> None:
    try:
        # قراءة JSON من stdin (سطر واحد)
        raw = sys.stdin.read().strip()
        if not raw:
            out = {"ok": False, "error": "No input", "recommendations": []}
            print(json.dumps(out, ensure_ascii=False))
            return

        answers = json.loads(raw)
        if not isinstance(answers, dict):
            out = {"ok": False, "error": "Input must be a JSON object", "recommendations": []}
            print(json.dumps(out, ensure_ascii=False))
            return

        # استدعاء دالة التوصية من نفس منطق recommend_cli
        from recommend_cli import recommend_from_answers

        recs = recommend_from_answers(
            answers,
            top_k=5,
            artifacts_dir=ARTIFACTS_DIR,
        )

        out = {
            "ok": True,
            "error": None,
            "recommendations": [
                {
                    "course": r["course"],
                    "probability": r["probability"],
                    "career_options": r.get("career_options"),
                }
                for r in recs
            ],
        }
        print(json.dumps(out, ensure_ascii=False))

    except FileNotFoundError as e:
        out = {"ok": False, "error": str(e), "recommendations": []}
        print(json.dumps(out, ensure_ascii=False))
        sys.exit(1)
    except json.JSONDecodeError as e:
        out = {"ok": False, "error": f"Invalid JSON: {e}", "recommendations": []}
        print(json.dumps(out, ensure_ascii=False))
        sys.exit(1)
    except Exception as e:
        out = {"ok": False, "error": str(e), "recommendations": []}
        print(json.dumps(out, ensure_ascii=False))
        sys.exit(1)


if __name__ == "__main__":
    main()
