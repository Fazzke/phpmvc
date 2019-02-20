<?php 
    class User {
        private $db;

          // Callen und initialisieren der Database-PDO-Klasse 
        public function __construct() {
            $this->db = new Database;
        }

          // Schreibe User-Registrierung in die Datenbank
          public function register($data) {
                // Bereite SQL-Statement vor
              $this->db->query('INSERT INTO users (name, email, password)
              VALUES(:name, :email, :password)');
              $this->db->bind(':name', $data['name']);
              $this->db->bind(':email', $data['email']);
              $this->db->bind(':password', $data['password']);

                //schreibe SQL_Statement dank execute() aus der Database-Klasse
              if($this->db->execute()) {
                  return true;
              } else {
                  return false;
              }
          }

          // Erhalte die ID des Users, der sich einloggen moechte
          public function login($email, $password) {
            // Bereite SQL-Statement vor
          $this->db->query('SELECT * FROM users WHERE email = :email');
          $this->db->bind(':email', $email);
          
          $row = $this->db->singleResult();
          
          $hashed_password = $row->password;

          if(password_verify($password, $hashed_password)) {
            return $row;
          } else {
            return false;
          }

            //schreibe SQL_Statement dank execute() aus der Database-Klasse
          if($this->db->execute()) {
              return true;
          } else {
              return false;
          }
      }

          // Finde User mit Email
        public function findUserByEmail($email) {
              // rufe die Funktion query('SQL Statement') in der Klasse Database auf
            $this->db->query('SELECT * FROM users WHERE email = :email');
            $this->db->bind(':email', $email);
              
              //es sollte nur eine uebereinstiende Mail geben, also single() methode nuzzen
            $row = $this->db->singleResult();

              // Check row, if > 0 an email is found
            if($this->db->numRows() > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function getUserById($id) {

        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);  
      
        $row = $this->db->singleResult();

        return $row;
    }
    }