<?php

namespace App\Models;

use App\Models\Database;
use Exception;
use App\Helpers\Str;

class User {
    private Database $db;
    private string $id;
    private string $email;
    private string $firstName;
    private string $lastName;
    private string $password;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function find(int|string $identifier): bool
    {
        $column = is_int($identifier) ? 'id' : 'email';
        $sql = "SELECT * FROM `users` WHERE `{$column}` = :identifier";

        $userQuery = $this->db->query($sql, [ 'identifier' => $identifier ]);

        if (!$userQuery->count()) {
            return false;
        }

        $userData = $userQuery->first();

        foreach ($userData as $column => $value) {
            $columnCamelCase = Str::toCamelCase($column);
            $this->{$columnCamelCase} = $value;
        }

        return true;
    }

    public function register(array $userData): void
    {
        $passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT, [ 'cost' => 10 ]);

        $sql = "
            INSERT INTO `users`
            (`first_name`, `last_name`, `email`, `password`)
            VALUES (:firstName, :lastName, :email, :password)
        ";

        $this->db->query($sql, [
            'firstName' => $userData['firstName'],
            'lastName' => $userData['lastName'],
            'email' => $userData['email'],
            'password' => $passwordHash,
        ]);
    }

    public function login(array $userData): void
    {
        $sql = 'SELECT * FROM `users` WHERE `email` = :email';

        $userQuery = $this->db->query($sql, [
            'email' => $userData['email']
        ]);

        if ($userQuery->count() < 1) {
            throw new Exception('This email address could not be found');
        }

        $queryResult = $userQuery->results()[0];
        $hash = $queryResult['password'];

        if (!password_verify($userData['password'], $hash)) {
            throw new Exception('The password was incorrect.');
        }

        // Login
        $_SESSION['userId'] = (int) $queryResult['id'];
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['userId']);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }
}
