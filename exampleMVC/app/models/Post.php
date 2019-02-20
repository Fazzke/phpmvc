<?php 
    class Post {
        private $db;

          // Callen und initialisieren der Database-PDO-Klasse 
        public function __construct() {
            $this->db = new Database;
        }

        public function getPosts() {
            $this->db->query('SELECT *, users.id AS userId,
                            posts.id AS postId, posts.created_at AS createdAt,
                            users.name AS userName FROM posts LEFT JOIN users ON
                            users.id = posts.user_id ORDER BY posts.created_at DESC');

            $results = $this->db->setResult();

            return $results;
        }

        public function addPost($data) {
        // Bereite SQL-Statement vor
        $this->db->query('INSERT INTO posts (user_id, title, nachricht)
        VALUES(:user_id, :title, :nachricht)');
        // Binde werte
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':nachricht', $data['nachricht']);

        //schreibe SQL_Statement dank execute() aus der Database-Klasse
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getPostById($id) {
        $this->db->query('SELECT * FROM posts WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->singleResult();

        return $row;
    }

    public function updatePost($data) {

        $this->db->query('UPDATE posts SET title = :title, nachricht = :nachricht WHERE id = :id');

        $this->db->bind(':title', $data['title']);
        $this->db->bind(':nachricht', $data['nachricht']);
          // wir brauchen nicht die user id, sondern die id der spezifischen nachricht
        $this->db->bind(':id', $data['id']);

        //schreibe SQL_Statement dank execute() aus der Database-Klasse
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }        
    }

    public function deletePost($id) {
    
        $this->db->query('DELETE FROM posts WHERE id = :id');

          // wir brauchen nicht die user id, sondern die id der spezifischen nachricht
        $this->db->bind(':id', $id);

        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }       
    }

    }