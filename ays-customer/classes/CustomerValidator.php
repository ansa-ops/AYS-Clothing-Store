<?php

// Middle Layer
// This class checks customer input before the data is sent to the database.
// It helps prevent empty fields, invalid email addresses and incorrect phone numbers.

class CustomerValidator
{
    // This function validates the main customer details.
    public static function validateCustomer(string $fullName, string $email, string $phoneNumber): array
    {
        $errors = [];

        // Check full name
        if (trim($fullName) === "") {
            $errors[] = "Full name is required.";
        } elseif (!preg_match("/^[a-zA-Z ]+$/", $fullName)) {
            $errors[] = "Full name can only contain letters and spaces.";
        }

        // Check email
        if (trim($email) === "") {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address.";
        }

        // Check phone number
if (trim($phoneNumber) === "") {
    $errors[] = "Phone number is required.";
} elseif (!preg_match("/^[0-9]+$/", $phoneNumber)) {
    $errors[] = "Phone number must contain numbers only.";
} elseif (strlen($phoneNumber) !== 8) {
    $errors[] = "Phone number must be exactly 8 digits.";
}

        return $errors;
    }

    // This function validates the password during registration.
    public static function validatePassword(string $password): array
    {
        $errors = [];

        if ($password === "") {
            $errors[] = "Password is required.";
        } elseif (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        }

        return $errors;
    }
}

?>