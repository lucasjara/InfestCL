# INFEST CL

INFEST Chile organizes and promotes events and parties. Through INFEST Night Party, it delivers nightlife experiences, helping visitors discover upcoming events and stay connected with the brand.

## Technologies

- [Laravel](https://laravel.com/) `13.38.0`
- PHP `^8.3`
- [Vite](https://vite.dev/) `^8.0.0`
- [Tailwind CSS](https://tailwindcss.com/) `^4.0.0`
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