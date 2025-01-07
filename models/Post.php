<?php
require_once '../includes/db.php';

class Post {
    public $id;
    public $thread_id;
    public $user_id;
    public $content;
    public $created_at;

    public function create($thread_id, $user_id, $content) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("INSERT INTO posts (thread_id, user_id, content, created_at) VALUES (:thread_id, :user_id, :content, NOW())");
            $result = $stmt->execute([
                ':thread_id' => $thread_id,
                ':user_id' => $user_id,
                ':content' => $content
            ]);
    
            if ($result) {
                return true;
            } else {
                echo "Error: " . implode(", ", $stmt->errorInfo());
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public static function getByThread($thread_id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE thread_id = ?");
        $stmt->execute([$thread_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getById($post_id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute([':id' => $post_id]);
        return $stmt->fetchObject(self::class);
    }

    public function delete() {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public function update($content) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE posts SET content = :content WHERE id = :id");
        return $stmt->execute([
            ':content' => $content,
            ':id' => $this->id
        ]);
    }
}
?>
