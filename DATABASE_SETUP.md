# Database Setup Instructions

## Prerequisites

Before setting up the database, ensure you have:

1. MySQL or MariaDB server installed and running
2. PHP with PDO MySQL extension enabled
3. Composer installed for dependency management

## Enabling PDO MySQL Extension

If you're using XAMPP, follow these steps to enable the PDO MySQL extension:

1. Open the `php.ini` file (usually located at `C:\xampp\php\php.ini`)
2. Find the line `;extension=pdo_mysql`
3. Remove the semicolon (`;`) at the beginning to uncomment it
4. Save the file
5. Restart the Apache server

## Creating the Database

1. Create a new database named `perpustakaan` in your MySQL/MariaDB server
2. Run the SQL script located at `database/create_tables_mariadb.sql` to create all necessary tables

## Database Configuration

Update the database configuration in `src/Config/Database.php` with your database credentials:

```php
private static $host = 'localhost';
private static $dbname = 'perpustakaan';
private static $username = 'your_username';
private static $password = 'your_password';
```

## Testing the Connection

After setting up the database, you can test the connection by running:

```bash
php public/test_db.php
```

This script will verify that the application can connect to the database and list all tables.

## Troubleshooting

If you encounter connection issues:

1. Verify that the MySQL server is running
2. Check that the database credentials are correct
3. Ensure the PDO MySQL extension is properly enabled
4. Confirm that the firewall is not blocking the connection