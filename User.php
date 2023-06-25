<?php
    session_start();

    include_once('db.php');

    class User {

        private $db;

        public function __construct($db){
            $this->db = $db;
        }

        public function login($username, $password){
            $sql = "SELECT id, username FROM user WHERE username = ? AND password = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            if(mysqli_num_rows($result) > 0){
                // login success
                $row = mysqli_fetch_assoc($result);
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['id'];
                return true;
            }
            return false;
        }

        public function signUp($username, $password) {
            if ($this->isUserExist($username)) {
                // username already exists
                return false;
            } else {
                // insert new user into database
                $sql = "INSERT INTO user (username, password) VALUES (?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("ss", $username, $password);
                $stmt->execute();
                return true;
            }
        }

        public function checkPassword($password) {
            $userId = $_SESSION['user_id'];
            $storedPassword = $this->findPasswordByUserId($userId);
            return $password == $storedPassword;
        }

        public function updatePassword($password) {
            $userId = $_SESSION['user_id'];
            $oldPassword = $this->findPasswordByUserId($userId);
            if($password == $oldPassword){
                // old password same as new password
                return false;
            }else{
                mysqli_query($this->db, "UPDATE user SET password = '$password' WHERE id = $userId");
                return true;
            }
        }

        private function isUserExist($username) {
            $sql = "SELECT * FROM user WHERE username = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if(mysqli_num_rows($result) > 0){
                // username already exists
                return true;
            } else {
                // username does not exist
                return false;
            }
        }

        private function findPasswordByUserId($postId){
            $result = mysqli_query($this->db, "SELECT password FROM user WHERE id = $postId");
            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);
                return $row['password'];
            }else{
                return null;
            }
        }
    }
?>