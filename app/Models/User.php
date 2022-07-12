<?php

namespace App\Models;

use App\Models\Database;

class User {
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
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
}
