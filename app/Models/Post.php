<?php

namespace App\Models;

use App\Models\Database;
use App\Helpers\Str;
use App\Models\User;

class Post {
    private Database $db;
    private string $id;
    private string $userId;
    private string $title;
    private string $slug;
    private string $body;
    private string $createdAt;

    public function __construct(Database $db, ?array $data = [])
    {
        $this->db = $db;
        $this->fill($data);
    }

    public function find(int $identifier): bool
    {
        $sql = "SELECT * FROM `posts` WHERE `id` = :identifier";
        $postQuery = $this->db->query($sql, ['identifier' => $identifier]);

        if (!$postQuery->count()) {
            return false;
        }

        $this->fill($postQuery->first());
        return true;
    }

    public function fill(array $data)
    {
        foreach ($data as $field => $value) {
            $this->{Str::toCamelCase($field)} = $value;
        }
    }

    public function create(int $userId, array $postData)
    {
        $sql = "
            INSERT INTO `posts`
            (`user_id`, `title`, `slug`, `body`, `created_at`)
            VALUES (:userId, :title, :slug, :body, :createdAt)
        ";

        $slug = Str::slug($postData['title']);

        $this->db->query($sql, [
            'userId' => $userId,
            'title' => $postData['title'],
            'slug' => $slug,
            'body' => $postData['body'],
            'createdAt' => time()
        ]);
    }

    public function edit(array $postData): bool
    {
        $sql = "
            UPDATE `posts`
            SET `title` = :title, `slug` = :slug, `body` = :body
            WHERE `id` = :id
        ";

        $slug = Str::slug($postData['title']);

        $postData = [
            'id' => $this->getId(),
            'title' => $postData['title'],
            'slug' => $slug,
            'body' => $postData['body']
        ];

        $editQuery = $this->db->query($sql, $postData);

        $this->fill($postData);

        return (bool) $editQuery->count();
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM `posts` WHERE `id` = :id";
        $deleteQuery = $this->db->query($sql, [ 'id' => $this->getId() ]);

        return (bool) $deleteQuery->count();
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function getUserId(): int
    {
        return (int) $this->userId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getCreatedAt(): string
    {
        return date('D, d.m.Y H:i:s', $this->createdAt);
    }

    public function getUser(): User
    {
        $user = new User($this->db);
        $user->find($this->getUserId());
        return $user;
    }
}
