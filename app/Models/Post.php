<?php

namespace App\Models;

use App\Models\Database;
use App\Helpers\Str;
use App\Models\User;
use App\Config;
use App\Models\FileStorage;

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

    public function create(int $userId, array $postData, array $image)
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

        $sql = "SELECT MAX(`id`) AS 'id' FROM `posts` WHERE `user_id` = :user_id";
        $postQuery = $this->db->query($sql, [ 'user_id' => $userId ]);

        $postId = $postQuery->first()['id'];

        $fileStorage = new FileStorage($image);
        $fileStorage->saveIn(Config::get('app.uploadFolder'));
        $imageName = $fileStorage->getGeneratedName();

        $sql = "
            INSERT INTO `post_images`
            (`post_id`, `filename`, `created_at`)
            VALUES (:post_id, :filename, :created_at)
        ";

        $this->db->query($sql, [
            'post_id' => $postId,
            'filename' => $imageName,
            'created_at' => time()
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
        $images = $this->getImages();

        foreach ($images as $image) {
            FileStorage::delete($image);
        }

        $sql = "DELETE FROM `posts` WHERE `id` = :id";
        $deleteQuery = $this->db->query($sql, [ 'id' => $this->getId() ]);

        return (bool) $deleteQuery->count();
    }

    public function like(int $userId): bool
    {
        $sql = "
            INSERT INTO `post_likes`
            (`user_id`, `post_id`, `created_at`)
            VALUES (:user_id, :post_id, :created_at)
        ";

        $likeQuery = $this->db->query($sql, [
            'user_id' => $userId,
            'post_id' => $this->getId(),
            'created_at' => time()
        ]);

        return (bool) $likeQuery->count();
    }

    public function dislike(int $userId): bool
    {
        $sql = "DELETE FROM `post_likes` WHERE `post_id` = :post_id AND `user_id` = :user_id";

        $deleteQuery = $this->db->query($sql, [
            'post_id' => $this->getId(),
            'user_id' => $userId
        ]);

        return (bool) $deleteQuery->count();
    }

    public function getTotalLikes(): int
    {
        $sql = "SELECT COUNT(`id`) as 'like_count' FROM `post_likes` WHERE `post_id` = :post_id";

        $likesQuery = $this->db->query($sql, [
            'post_id' => $this->getId()
        ]);

        return (int) $likesQuery->first()['like_count'];
    }

    public function isLikedBy(int $userId): bool
    {
        $sql = "SELECT 1 FROM `post_likes` WHERE `post_id` = :post_id AND `user_id` = :user_id";
        $likeQuery = $this->db->query($sql, [
            'post_id' => $this->getId(),
            'user_id' => $userId
        ]);

        return (bool) $likeQuery->count();
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

    public function getImages(): array
    {
        $sql = "SELECT `filename` FROM `post_images` WHERE `post_id` = :post_id";
        $query = $this->db->query($sql, [ 'post_id' => $this->getId() ]);

        $images = array_map(function ($image) {
            return DIRECTORY_SEPARATOR . Config::get('app.uploadFolder') . DIRECTORY_SEPARATOR . $image['filename'];
        }, $query->results());

        return $images;
    }
}
