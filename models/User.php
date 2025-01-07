<?php
require_once '../includes/db.php';

class User {
    private $id;
    private $username;
    private $email;
    private $password;
    private $created_at;

    public function __construct($username, $email, $password, $id = null, $created_at = null) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->created_at = $created_at;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getId() {
        return $this->id;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function setUsername($username) {
        $this->username = $username;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function setPassword($password) {
        $this->password = $password;
    }
    
    public function update() {
        global $pdo;
    
        $query = "UPDATE users SET username = :username, email = :email" .
                 (!empty($this->password) ? ", password = :password" : "") .
                 " WHERE id = :id";
    
        $params = [
            ':username' => $this->username,
            ':email' => $this->email,
            ':id' => $this->id,
        ];
    
        if (!empty($this->password)) {
            $params[':password'] = $this->password;
        }
    
        $stmt = $pdo->prepare($query);
        return $stmt->execute($params);
    }    

    public static function userExists($username, $email) {
        global $pdo;

        $query = "SELECT id FROM users WHERE username = :username OR email = :email";
        $stmt = $pdo->prepare($query);

        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function register() {
        global $pdo;

        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($query);

        return $stmt->execute([
            ':username' => $this->username,
            ':email' => $this->email,
            ':password' => password_hash($this->password, PASSWORD_BCRYPT),
        ]);
    }

    public static function getById($id) {
        global $pdo;

        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new self(
                $row['username'],
                $row['email'],
                $row['password'],
                $row['id'],
                $row['created_at']
            );
        }

        return null;
    }

    public static function findByUsernameOrEmail($usernameOrEmail) {
        global $pdo;
        $query = "SELECT * FROM users WHERE username = :usernameOrEmail OR email = :usernameOrEmail LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':usernameOrEmail' => $usernameOrEmail]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return new self(
                $user['username'],
                $user['email'],
                $user['password'],
                $user['id'],
                $user['created_at']
            );
        } else {
            return null;
        }
    }
}
?>