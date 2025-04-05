<?php
require_once 'config/config.php';

class User
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    // Lấy tất cả người dùng
    // Thêm các phương thức mới vào class User

    // Phương thức khóa tài khoản
    public function banUser($id)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET status = 'banned' WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in banUser(): " . $e->getMessage());
            return false;
        }
    }

    // Phương thức mở khóa tài khoản
    public function unbanUser($id)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET status = 'active' WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in unbanUser(): " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật phương thức getAllUsers để lấy cả trạng thái
    public function getAllUsers()
    {
        try {
            $stmt = $this->conn->prepare("SELECT id, name, email, role, status, created_at FROM users");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getAllUsers(): " . $e->getMessage());
            return [];
        }
    }

    // Cập nhật phương thức getUserById để lấy cả trạng thái
    public function getUserById($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT id, name, email, role, status, created_at FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getUserById(): " . $e->getMessage());
            return null;
        }
    }

    // Tìm kiếm người dùng
    public function searchUsers($keyword)
    {
        try {
            $stmt = $this->conn->prepare("SELECT id, name, email, role, created_at FROM users WHERE name LIKE :keyword OR email LIKE :keyword");
            $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in searchUsers(): " . $e->getMessage());
            return [];
        }
    }
    public function getTotalUsers()
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users");
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getTotalUsers(): " . $e->getMessage());
            return 0;
        }
    }

    public function getUsersByPage($limit, $offset)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users ORDER BY id DESC LIMIT ? OFFSET ?");
            $stmt->bindParam(1, $limit, PDO::PARAM_INT);
            $stmt->bindParam(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Database error in getUsersByPage(): " . $e->getMessage());
            return [];
        }
    }
}
