# INFEST CL

INFEST Chile organizes and promotes events and parties. Through INFEST Night Party, it delivers nightlife experiences, helping visitors discover upcoming events and stay connected with the brand.

## Features

- Public event and brand landing page with responsive layouts, dark mode, and locally hosted event imagery.
- Tournament creator for single-elimination competitions with support for:
  - 2 to 32 participants.
  - Random or seeded participant order.
  - Byes for bracket sizes that are powers of two.
  - Money or item prizes, including place, currency, description, amount, and quantity.
  - An optional match for third place.
- Interactive tournament bracket with winner selection, round progression, champion and runner-up information, and third-place resolution.
- Public JSON API for the INFEST events catalog and tournament bracket generation.
- OpenAPI documentation generated automatically with Scramble.
- API rate limiting, public cache headers, and security response headers.

## Technologies

- [Laravel](https://laravel.com/) `13.39.0`
- PHP `^8.3`
- [Vite](https://vite.dev/) `^8.0.0`
- [Tailwind CSS](https://tailwindcss.com/) `^4.0.0`
- [Scramble](https://scramble.dedoc.co/) `0.13.52` for OpenAPI documentation
- [Laravel Sanctum](https://laravel.com/docs/sanctum) `4.3.3`
- PHPUnit `12.5.40` for testing

## Requirements

- PHP 8.3 or later and Composer
- Node.js and npm to install and build frontend assets
- SQLite, configured as the default database

## Installation

Clone the repository and run the setup script from the project directory:

```bash
git clone https://github.com/lucasjara/InfestCL.git
cd InfestCL
composer setup
```

The `composer setup` command installs PHP and JavaScript dependencies, creates the `.env` file from `.env.example` if it does not already exist, generates the application key, runs database migrations, and builds the frontend assets for production.

You can customize your local configuration in `.env`. Do not share this file or commit it to the repository.

## Local Development

Start the development processes by running:

```bash
composer run dev
```

The application will be available at [http://localhost:8000](http://localhost:8000).

To build the frontend assets for production, run:

```bash
npm run build
```

To work on frontend assets using the Vite development server, run:

```bash
npm run dev
```

## API

The application exposes a small public API under `/api`:

| Method | Endpoint | Description |
| --- | --- | --- |
| `GET` | `/api/events` | Returns the public INFEST and INFEST Night Party events catalog. |
| `POST` | `/api/tournaments` | Generates a single-elimination tournament bracket from participant and prize data. |

API requests can be reviewed through the generated OpenAPI interface:

- Documentation UI: [http://localhost:8000/docs/api](http://localhost:8000/docs/api)
- OpenAPI JSON: [http://localhost:8000/docs/api.json](http://localhost:8000/docs/api.json)
## Testing

Run the test suite from the project root directory:

```bash
composer test
```

The PHPUnit configuration uses an in-memory SQLite database for testing.

## Author

**Lucas Jara** — [GitHub](https://github.com/lucasjara)

## License

This project is licensed under the MIT License. See the [LICENSE](https://github.com/lucasjara/InfestCL/blob/main/LICENSE) file for more information.