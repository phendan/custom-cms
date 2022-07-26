<?php

namespace App\Models;

use App\Models\Database;
use App\Helpers\Str;

class Post {
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
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
}
