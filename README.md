
# Symfony Application Code Blog "Stuck over bug"



## Requirements

Before you begin, ensure you have the following installed:

- PHP >= 8.0
- Composer
- Symfony CLI (recommended but not required)
- A database (PostgreSQL)

## Installation

1. Clone the repository:

```bash
git clone https://github.com/your-username/symfony-app.git
cd symfony-app
```

2. Install dependencies using Composer:

```bash
composer install
```

3. Configure the `.env` file for database connection:

- Edit the `.env` file and set your database connection parameters:

```dotenv
DATABASE_URL="mysql://username:password@127.0.0.1:3306/dbname"
```

4. Create the database (if not already created):

```bash
php bin/console doctrine:database:create
```

5. Apply migrations:

```bash
php bin/console doctrine:migrations:migrate
```

## Development

To start development locally, run the following command:

```bash
symfony serve
```

This will start the Symfony built-in server at `http://localhost:8000`.

## Tests

To run tests, use the following commands:

```bash
php bin/console doctrine:schema:update --force  # Sync database schema with models
php bin/console cache:clear  # Clear cache
php bin/console test  # Run the tests
```

## Deployment

For deployment to a server, use standard Symfony production server mechanisms.

To clear the cache and logs on the production server, run:

```bash
php bin/console cache:clear --env=prod --no-debug
```

## Links

- [Symfony Documentation](https://symfony.com/doc/current/index.html)
- [Composer](https://getcomposer.org/)
- [Symfony CLI](https://symfony.com/download)

