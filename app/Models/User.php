<?php

namespace App\Models;

use App\Models\Database;
use Exception;
use App\Helpers\Str;
use App\Models\Post;

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
        if (!$this->find($userData['email'])) {
            throw new Exception('The email could not be found.');
        }

        $hash = $this->password;

        if (!password_verify($userData['password'], $hash)) {
            throw new Exception('The password was incorrect.');
        }

        // Login
        $_SESSION['userId'] = $this->getId();
    }

    public function logout(): void
    {
        unset($_SESSION['userId']);
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['userId']);
    }

    public function getPosts(): array
    {
        $sql = "SELECT * FROM `posts` WHERE `user_id` = :user_id";
        $postsQuery = $this->db->query($sql, [ 'user_id' => $this->getId() ]);

        $posts = [];

        foreach ($postsQuery->results() as $result) {
            $posts[] = new Post($this->db, $result);
        }

        return $posts;
    }

    public function getId(): int
    {
        return (int) ($this->id ?? $_SESSION['userId']);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }
}
