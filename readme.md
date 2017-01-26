# About
This repository contains examples on how to use the [Laravel CSV Importer package](https://github.com/Yavor-Ivanov/laravel-csv-importer). The repository contains a working copy of a Laravel 4 applictaion with the csv importer package already added as a dependency. All importers/exporters contain inline documentation about their inner workings.

# Getting started

1. First, clone the repository: `git clone git@github.com:Yavor-Ivanov/laravel-csv-importer-examples.git`
2. Navigate to the project directory: `cd laravel-csv-importer-examples`
3. Install dependencies via composer: `composer update`
4. Create the database: `touch app/database/production.sqlite`
5. Run the migrations: `php artisan migrate`
6. You're all set!

You can now import/export by running the following commands:
  - `php artisan csv:import importer_name`
  - `php artisan csv:export exporter_name`

Valid importer/exporter names are: `book`, `author`, `genre`, `format`, `bookformat`, `bookgenre`

For more information on the command syntax, refer to the [documentation](https://github.com/Yavor-Ivanov/laravel-csv-importer/blob/master/README.md#commands).

# Directory structure
I've elected to include all Laravel files in the repository, as it greatly simplifies the installation steps (there is zero configuration needed). The tradeoff is this makes the task of finding files you may wish to modify, or take a look at, harder. Below, I've included a list of all files added (or modified):
```
└── app
    ├── config
    │   ├── database.php
    ├── csv
    │   ├── exporters
    │   │   ├── AuthorExporter.php
    │   │   ├── BookExporter.php
    │   │   ├── BookFormatExporter.php
    │   │   ├── BookGenreExporter.php
    │   │   ├── FormatExporter.php
    │   │   └── GenreExporter.php
    │   ├── files
    │   │   ├── backup
    │   │   ├── authors.csv
    │   │   ├── books.csv
    │   │   ├── books_to_formats.csv
    │   │   ├── books_to_genres.csv
    │   │   ├── formats.csv
    │   │   └── genres.csv
    │   └── importers
    │       ├── AuthorImporter.php
    │       ├── BookFormatImporter.php
    │       ├── BookGenreImporter.php
    │       ├── BookImporter.php
    │       ├── FormatImporter.php
    │       └── GenreImporter.php
    ├── database
    │   └── migrations
    │       ├── 2017_01_23_111459_create_authors_table.php
    │       ├── 2017_01_23_115442_create_books_table.php
    │       ├── 2017_01_23_115454_create_genres_table.php
    │       ├── 2017_01_23_122851_create_author_book.php
    │       ├── 2017_01_23_123127_create_book_genre.php
    │       ├── 2017_01_23_135411_create_book_format.php
    │       └── 2017_01_24_130314_create_formats_table.php
    └── models
        ├── Author.php
        ├── BookFormatPivot.php
        ├── BookGenrePivot.php
        ├── Book.php
        ├── Format.php
        ├── Genre.php
        └── User.php
```
The `app/config/database.php` file has been modified to write use SQLite by default. The file for the database is located at `app/database/production.sqlite`.

# CSV files and Database Model
There are 4 entities at play: `Authors`, `Books`, `Formats`, and `Genres`. Each have their models stored in the `app/models` folder, as well as additional pivot models (`BookGenrePivot` and `BookFormatPivot`), which are used when importing the many to many relationships between books and genres and books and formats.

```
       ┌────────────┐              ┌────────────┐
       │            │              │            │
       │            │  *         * │            │
       │   Author   │◀════════════▶│    Book    │◀══╗
       │            │              │            │   ║ *
       │            │              │            │   ║
       └────────────┘              └────────────┘   ║
                                          ▲         ║
                                          ║         ║
                                          ║         ║
                                        * ║         ║
                      ╔═══════════════════╝         ║
                      ║                             ║
                      ║                             ║
                    * ║                             ║
                      ▼                             ║
               ┌────────────┐                       ║   ┌────────────┐
               │            │                       ║   │            │
               │            │                       ║   │            │
               │   Genre    │                     * ╚══▶│   Format   │
               │            │                           │            │
               │            │                           │            │
               └────────────┘                           └────────────┘
```
  Database model *(Many to many relationships annotated with `*` to `*`)*

Although all database table relationships are many to many, I've modelled them differently in the CSV files, to show off the different ways you may wish to reference records outside your current CSV file. For example, although the `author` <--> `book` relationship is many to many in the database, it is modelled as one to many in the `books.csv`.

Another notable difference is the variable column many to many relationship in `books_to_genre.csv`, as opposed to the traditional two columns with no unique foreign keys:

| book | genre 1 | genre 2 | 
|------|---------|---------| 
| 1    | 1       |         | 
| 2    | 1       | 2       | 
| 3    | 3       |         | 
| 4    | 4       |         | 
| 5    | 5       |         | 
| 6    | 6       |         | 
| 7    | 5       |         | 
