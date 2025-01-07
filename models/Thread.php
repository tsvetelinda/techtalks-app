<?php
require_once '../includes/db.php';

class Thread {
    public $id;
    public $title;
    public $description;
    public $created_by;
    public $created_at;
    public $category;

    public static function getAll($searchQuery = '', $categoryFilter = '') {
        global $pdo;
        
        $sql = "SELECT * FROM threads WHERE 1";
    
        if ($searchQuery) {
            $sql .= " AND title LIKE :searchQuery";
        }
    
        if ($categoryFilter) {
            $sql .= " AND category = :categoryFilter";
        }
    
        $stmt = $pdo->prepare($sql);
    
        if ($searchQuery) {
            $stmt->bindValue(':searchQuery', '%' . $searchQuery . '%');
        }
        if ($categoryFilter) {
            $stmt->bindValue(':categoryFilter', $categoryFilter);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }    

    public function create($title, $description, $category, $created_by) {
        global $pdo;
        
        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :created_by");
        $stmt->execute([':created_by' => $created_by]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$user) {
            throw new Exception("User with ID $created_by does not exist.");
        }

        $stmt = $pdo->prepare("INSERT INTO threads (title, description, created_by, created_at, category) 
                               VALUES (:title, :description, :created_by, NOW(), :category)");
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':category' => $category,
            ':created_by' => $created_by
        ]);
        return $pdo->lastInsertId();
    }

    public static function getById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM threads WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($data) {
            $thread = new self();
            $thread->id = $data->id;
            $thread->title = $data->title;
            $thread->description = $data->description;
            $thread->created_by = $data->created_by;
            $thread->created_at = $data->created_at;
            $thread->category = $data->category;
            return $thread;
        }
        return null;
    }

    public function update($title, $description, $category) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE threads SET title = :title, description = :description, category = :category WHERE id = :id");
        $success = $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':category' => $category,
            ':id' => $this->id,
        ]);
        
        if ($success) {
            $this->title = $title;
            $this->description = $description;
            $this->category = $category;
        }
    
        return $success;
    }
    
    public function delete() {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM threads WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    public static function getByUserId($userId) {
        global $pdo;
    
        $stmt = $pdo->prepare("SELECT * FROM threads WHERE created_by = :user_id");
        $stmt->execute([':user_id' => $userId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>