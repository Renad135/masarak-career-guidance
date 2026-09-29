# Masarak | مسارك

Masarak is a bilingual (Arabic/English) career and university-major guidance web application. Students can create an account, record academic information, complete an interests survey, and explore suggested majors and career paths.

## Features

- Arabic and English interface with language switching.
- Account registration, sign-in, profile, and academic score pages.
- Interest survey and major recommendations from the PHP/MySQL application.
- A separate career recommender powered by a scikit-learn Random Forest pipeline, with a command-line interface and PHP-facing JSON API.
- Career and major reference content with responsive styling.

> Recommendations are guidance for exploration and should not be treated as admissions decisions or professional career advice.

## Technology

- PHP 8+ with PDO
- MySQL 8+
- Python 3.10+
- scikit-learn, pandas, and joblib for the machine-learning recommender

## Project layout

```text
.
├── ai/                    # PHP application's database-backed recommendation logic
├── api/                   # Language API endpoint
├── assets/                # CSS, JavaScript, and images
├── config/                # Application and database configuration
├── database/schema.sql    # Clean schema and reference majors
├── includes/              # Shared PHP content and translations
├── rec/
│   ├── app/               # Model training, prediction, and source dataset
│   ├── artifacts/         # Bundled trained pipeline and metadata
│   └── web/               # Standalone recommender interface
└── *.php                  # Main application pages
```

## Local setup

1. Install PHP 8+ with PDO MySQL, MySQL 8+, and Python 3.10+.
2. Create a database and load the schema:

   ```sh
   mysql -u root -p < database/schema.sql
   ```

3. Configure the database and site URL through environment variables. Defaults are intended only for local development:

   ```text
   MASARAK_DB_HOST=127.0.0.1
   MASARAK_DB_NAME=masarak_db
   MASARAK_DB_USER=masarak
   MASARAK_DB_PASSWORD=your-local-password
   MASARAK_SITE_URL=http://localhost/MASARAK
   ```

   Set these variables in your local PHP/Apache environment. Never commit real credentials.

4. Install the Python dependencies:

   ```sh
   python -m pip install -r requirements.txt
   ```

5. Serve the project with Apache (for example, XAMPP) and open the project URL. The PHP application expects the project directory to be available at `MASARAK_SITE_URL`.

The bundled recommender model is under `rec/artifacts/`. To retrain it from the included dataset:

```sh
python rec/app/train_model.py
```

To get predictions from the command line:

```sh
python rec/app/recommend_cli.py --help
```

The JSON prediction API reads one JSON object from standard input:

```sh
echo '{"Coding":"Yes"}' | python rec/app/predict_api.py
```

## Model metrics

The supplied model metadata reports **99.4% accuracy** and **0.942 macro F1** on its recorded held-out split. These are the metrics stored with the provided model; they have not been independently reproduced as part of this repository preparation. Performance depends on the dataset and should not be interpreted as a guarantee for real-world outcomes.

## Data and privacy

The repository includes the recommender training dataset and schema/reference data. It intentionally excludes the supplied database export, which contained user emails, profile details, academic records, and password hashes, as well as a separate file containing login credentials. Do not add production data, personal information, secrets, local environment files, or database backups to version control.

## Language

The application UI supports Arabic and English. This README is written in English for repository discoverability.
