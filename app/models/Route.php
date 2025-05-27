<?php
namespace app\models;

class Route {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findRoute($fromCity, $toCity) {
        $stmt = $this->db->prepare('
            SELECT * FROM routes 
            WHERE from_city = ? AND to_city = ?
        ');
        $stmt->execute([$fromCity, $toCity]);
        return $stmt->fetch();
    }

    public function saveRoute($fromCity, $toCity, $distance) {
        $stmt = $this->db->prepare('
            INSERT INTO routes (from_city, to_city, distance) 
            VALUES (?, ?, ?)
        ');
        $stmt->execute([$fromCity, $toCity, $distance]);
        return $this->db->lastInsertId();
    }

    public function getRoutes() {
        $stmt = $this->db->query('
            SELECT * FROM routes 
            ORDER BY created_at DESC
        ');
        return $stmt->fetchAll();
    }

    public function getRouteById($id) {
        $stmt = $this->db->prepare('
            SELECT * FROM routes 
            WHERE id = ?
        ');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function deleteRoute($id) {
        $stmt = $this->db->prepare('DELETE FROM routes WHERE id = ?');
        return $stmt->execute([$id]);
    }
} 