<?php

// Middle Layer
// This class handles product-related logic.
// It is used to display products and get product details for cart and checkout.

class Product
{
    private PDO $conn;

    // The database connection is passed into this class from Database.php.
    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    // This function gets all available products.
    // If a category is selected, it only shows products from that category.
    public function all(string $category = "All"): array
    {
        if ($category !== "All") {

            $stmt = $this->conn->prepare("
                SELECT *
                FROM Product
                WHERE Category = ?
                AND IsAvailable = TRUE
            ");

            $stmt->execute([$category]);

        } else {

            $stmt = $this->conn->prepare("
                SELECT *
                FROM Product
                WHERE IsAvailable = TRUE
            ");

            $stmt->execute();
        }

        return $stmt->fetchAll();
    }

    // This function gets one product by its ProductID.
    // It is used on the product details page.
    public function getProductByID(int $id): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM Product
            WHERE ProductID = ?
        ");

        $stmt->execute([$id]);

        $product = $stmt->fetch();

        if ($product) {
            return $product;
        }

        return null;
    }
}

?>