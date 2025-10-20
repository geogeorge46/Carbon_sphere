<?php
class User extends Model {
    private $lastError = '';
    public function __construct() {
        parent::__construct();
    }

    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    public function register($data) {
        try {
            $this->db->query('INSERT INTO users (first_name, last_name, email, phone_number, password) VALUES (:first_name, :last_name, :email, :phone_number, :password)');
            $this->db->bind(':first_name', $data['first_name']);
            $this->db->bind(':last_name', $data['last_name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':phone_number', $data['phone_number']);
            $this->db->bind(':password', $data['password']);

            $ok = $this->db->execute();
            if(!$ok){
                $this->lastError = 'Database error while registering user.';
            }
            return $ok;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    public function getLastError(){
        return $this->lastError;
    }

    public function validateFirstName($name) {
        if (empty($name)) {
            return 'Please enter your first name.';
        }
        if (!preg_match('/^[A-Za-z]{2,}$/', $name)) {
            return 'First name must be at least 2 characters and contain only letters.';
        }
        return '';
    }

    public function validateLastName($name) {
        if (empty($name)) {
            return 'Please enter your last name.';
        }
        if (!preg_match('/^[A-Za-z]{2,}$/', $name)) {
            return 'Last name must be at least 2 characters and contain only letters.';
        }
        return '';
    }

    public function validateEmail($email) {
        if (empty($email)) {
            return 'Please enter your email address.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Please enter a valid email address.';
        }
        if ($this->findUserByEmail($email)) {
            return 'Email is already taken.';
        }
        return '';
    }

    public function validatePhoneNumber($phone) {
        if (empty($phone)) {
            return 'Please enter your phone number.';
        }
        // Must be 10 digits and start with 6-9
        if (!preg_match('/^[6-9]\d{9}$/', $phone)) {
            return 'Phone number must be 10 digits and start with 6-9.';
        }
        if ($this->isPhoneNumberTaken($phone)) {
            return 'Phone number is already taken.';
        }
        return '';
    }

    public function validatePassword($password) {
        if (empty($password)) {
            return 'Please enter a password.';
        }
        if (strlen($password) < 8) {
            return 'Password must be at least 8 characters.';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return 'Password must contain at least one uppercase letter.';
        }
        if (!preg_match('/[a-z]/', $password)) {
            return 'Password must contain at least one lowercase letter.';
        }
        if (!preg_match('/[0-9]/', $password)) {
            return 'Password must contain at least one number.';
        }
        if (!preg_match('/[@$!%*?&]/', $password)) {
            return 'Password must contain at least one special character.';
        }
        return '';
    }

    public function validateConfirmPassword($password, $confirmPassword) {
        if (empty($confirmPassword)) {
            return 'Please confirm your password.';
        }
        if ($password !== $confirmPassword) {
            return 'Passwords do not match.';
        }
        return '';
    }

    public function isPhoneNumberTaken($phone) {
        $this->db->query('SELECT * FROM users WHERE phone_number = :phone');
        $this->db->bind(':phone', $phone);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    // Login User
    public function login($email, $password){
      $row = $this->findUserByEmail($email);

      if($row == false){
        return false;
      }

      $hashed_password = $row->password;
      if(password_verify($password, $hashed_password)){
        return $row;
      } else {
        return false;
      }
    }

    // Get User by ID
    public function getUserById($id){
      $this->db->query('SELECT * FROM users WHERE user_id = :id');
      // Bind value
      $this->db->bind(':id', $id);

      $row = $this->db->single();

      return $row;
    }
}
