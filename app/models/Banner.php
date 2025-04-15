<?php
require_once 'config/config.php';

class Banner {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    public function getActiveBanners() {
        try {
            $stmt = $this->conn->query("SELECT * FROM side_banners WHERE status = 'active' ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function getAllBanners() {
        try {
            $stmt = $this->conn->query("SELECT * FROM side_banners ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getBannerById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM side_banners WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function create($title, $imagePath, $status = 'active') {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO side_banners (title, image_path, status, created_at) 
                 VALUES (?, ?, ?, NOW())"
            );
            return $stmt->execute([$title, $imagePath, $status]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateStatus($id, $status) {
        try {
            $stmt = $this->conn->prepare("UPDATE side_banners SET status = ? WHERE id = ?");
            return $stmt->execute([$status, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM side_banners WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
    public function update($id, $title, $imagePath, $status) {
    try {
        $stmt = $this->conn->prepare(
            "UPDATE side_banners 
             SET title = ?, image_path = ?, status = ?, updated_at = NOW() 
             WHERE id = ?"
        );
        return $stmt->execute([$title, $imagePath, $status, $id]);
    } catch (PDOException $e) {
        return false;
    }
}
public function getTotalBanners() {
    try {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM side_banners");
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}

public function getBannersByPage($limit, $offset) {
    try {
        $stmt = $this->conn->prepare("SELECT * FROM side_banners ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}
}