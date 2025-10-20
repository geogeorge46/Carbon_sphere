<?php
class Category extends Model {
    public function __construct(){
        parent::__construct();
    }

    public function getAll(){
        $this->db->query('SELECT * FROM categories ORDER BY category_name');
        return $this->db->resultSet();
    }
}
