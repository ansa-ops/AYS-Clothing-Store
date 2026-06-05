<?php

// Data Layer
// This class is responsible for connecting the PHP system to the MySQL database.
// PDO is used because it is secure and supports prepared statements.

class Database
{
    private string $host = "localhost";
    private string $dbName = "ays_clothing_store";
    private string $username = "root";
    private string $password = "";

    // This function creates and returns the database connection.
    public function connect(): PDO
    {
        try {

            $conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4",
                $this->username,
                $this->password
            );

            // This makes PDO show errors clearly during development.
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // This makes all results return as associative arrays.
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $conn;

        } catch (PDOException $e) {

            die("Database connection failed: " . $e->getMessage());
        }
    }
}

?>