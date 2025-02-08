
# Symfony Application Code Blog "Stuck over bug"

## Requirements

Before you begin, ensure you have the following installed:

- PHP >= 8.0
- Composer
- Symfony CLI (recommended but not required)
- A database (PostgreSQL, MySQL, or SQLite)

## Installation

1. Clone the repository:

```bash
git clone https://github.com/yrche/Symfony-back-end.git
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

## Available Commands

The application provides two custom commands:

### 1. `readme:show`

This command displays the content of the `README.md` file.

**Usage:**

```bash
php bin/console readme:show
```

**Description:**
- Reads the `README.md` file from the root directory of the project.
- Outputs the content to the console.
- If the file is not found, it displays an error message.

---

### 2. `app:show-users`

This command displays a list of all users stored in the database.

**Usage:**

```bash
php bin/console app:show-users
```

**Description:**
- Retrieves all users from the database using Doctrine ORM.
- Displays the users in a table format with the following columns:
  - **ID**: The unique identifier of the user.
  - **Email**: The email address of the user.
  - **Roles**: The roles assigned to the user (comma-separated).
- If no users are found, it displays a warning message.

---


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

---

### Example Outputs

#### `readme:show`

```bash
$ php bin/console readme:show
Content of README.md:
# Symfony Application Code Blog "Stuck over bug"
...
```

#### `app:show-users`

```bash
$ php bin/console app:show-users
+----+-------------------+---------------------+
| ID | Email             | Roles               |
+----+-------------------+---------------------+
| 1  | john@example.com  | ROLE_USER           |
| 2  | jane@example.com  | ROLE_ADMIN, ROLE_USER|
+----+-------------------+---------------------+
```
