<?php

// Middle Layer
// This class contains the main customer business logic.
// It connects the presentation pages to the database procedures.
// It includes Add, Edit, Update, Delete, List, Find, Filter, Login and Membership functions.

class Customer
{
    private PDO $conn;

    // The database connection is passed into this class from Database.php.
    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    // Adds a new customer and creates their membership record.
    public function addCustomer(
        string $fullName,
        string $email,
        string $password,
        string $phoneNumber,
        int $isActive,
        string $membershipType
    ): bool {
        // Password is hashed before saving for security.
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Membership discount is decided by membership type.
        $discountRate = match ($membershipType) {
            "Gold" => 15,
            "Silver" => 10,
            default => 5
        };

        $stmt = $this->conn->prepare("
            CALL AddCustomer(?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $result = $stmt->execute([
            $fullName,
            $email,
            $hashedPassword,
            $phoneNumber,
            $isActive,
            date("Y-m-d"),
            $membershipType,
            $discountRate
        ]);

        $stmt->closeCursor();

        return $result;
    }

    // Updates the basic customer details.
    public function updateCustomer(
        int $id,
        string $name,
        string $email,
        string $phone,
        int $active
    ): bool {
        $stmt = $this->conn->prepare("
            CALL UpdateCustomer(?, ?, ?, ?, ?)
        ");

        $result = $stmt->execute([
            $id,
            $name,
            $email,
            $phone,
            $active
        ]);

        $stmt->closeCursor();

        return $result;
    }

    // Soft delete function.
    // This keeps the customer record but marks them as inactive.
    public function deactivateCustomer(int $id): bool
    {
        $stmt = $this->conn->prepare("
            CALL DeactivateCustomer(?)
        ");

        $result = $stmt->execute([$id]);

        $stmt->closeCursor();

        return $result;
    }

    // Gets every customer from the database.
    // Used for the main customer management list.
    public function getAllCustomers(): array
    {
        $stmt = $this->conn->prepare("
            CALL GetAllCustomers()
        ");

        $stmt->execute();

        $customers = $stmt->fetchAll();

        $stmt->closeCursor();

        return $customers;
    }

    // Gets one customer by CustomerID.
    // Used when editing a customer.
    public function getCustomerByID(int $id): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT
                c.CustomerID,
                c.FullName,
                c.Email,
                c.PhoneNumber,
                c.IsActive,
                c.DateCreated,
                m.MembershipType,
                m.DiscountRate,
                m.Points
            FROM Customer c
            LEFT JOIN CustomerMembership m
                ON c.CustomerID = m.CustomerID
            WHERE c.CustomerID = ?
        ");

        $stmt->execute([$id]);

        $customer = $stmt->fetch();

        if ($customer) {
            return $customer;
        }

        return null;
    }

    // Finds customers by ID, name, email or phone number.
    public function findCustomer(string $search): array
    {
        $stmt = $this->conn->prepare("
            CALL FindCustomer(?)
        ");

        $stmt->execute([$search]);

        $customers = $stmt->fetchAll();

        $stmt->closeCursor();

        return $customers;
    }

    // Filters customers by account status.
    // 1 = Active customers, 0 = Inactive customers.
    public function filterCustomers(int $active): array
    {
        $stmt = $this->conn->prepare("
            CALL FilterCustomers(?)
        ");

        $stmt->execute([$active]);

        $customers = $stmt->fetchAll();

        $stmt->closeCursor();

        return $customers;
    }

    // Customer login function.
    // The email is used to find the customer, then password_verify checks the password.
    public function login(string $email, string $password): ?array
    {
        $stmt = $this->conn->prepare("
            CALL LoginCustomer(?)
        ");

        $stmt->execute([$email]);

        $customer = $stmt->fetch();

        $stmt->closeCursor();

        if ($customer && password_verify($password, $customer["Password"])) {
            return $customer;
        }

        return null;
    }

    // Updates membership type, discount and points.
    public function updateMembership(int $id, string $type, int $points): bool
    {
        $discount = match ($type) {
            "Gold" => 15,
            "Silver" => 10,
            default => 5
        };

        $stmt = $this->conn->prepare("
            CALL UpdateCustomerMembership(?, ?, ?, ?)
        ");

        $result = $stmt->execute([
            $id,
            $type,
            $discount,
            $points
        ]);

        $stmt->closeCursor();

        return $result;
    }

    // Permanent delete function.
    // Related order details and membership records are deleted first
    // because of foreign key relationships in the database.
    public function deleteCustomerPermanent(int $id): bool
    {
        // Delete order detail records linked to the customer's orders.
        $stmt = $this->conn->prepare("
            DELETE od
            FROM OrderDetail od
            INNER JOIN Orders o
                ON od.OrderID = o.OrderID
            WHERE o.CustomerID = ?
        ");

        $stmt->execute([$id]);

        // Delete the customer's orders.
        $stmt = $this->conn->prepare("
            DELETE FROM Orders
            WHERE CustomerID = ?
        ");

        $stmt->execute([$id]);

        // Delete the customer's membership record.
        $stmt = $this->conn->prepare("
            DELETE FROM CustomerMembership
            WHERE CustomerID = ?
        ");

        $stmt->execute([$id]);

        // Finally delete the customer record.
        $stmt = $this->conn->prepare("
            DELETE FROM Customer
            WHERE CustomerID = ?
        ");

        return $stmt->execute([$id]);
    }
}

?>