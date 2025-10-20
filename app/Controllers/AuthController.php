<?php
class AuthController extends Controller {
    public function __construct() {
        $this->userModel = $this->model('User');
    }

    public function register() {
        if (isLoggedIn()) {
            header('location:' . URLROOT . '/pages/index');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'email' => trim($_POST['email']),
                'phone_number' => trim($_POST['phone_number']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'first_name_err' => '',
                'last_name_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Server-side validation
            $data['first_name_err'] = $this->userModel->validateFirstName($data['first_name']);
            $data['last_name_err'] = $this->userModel->validateLastName($data['last_name']);
            $data['email_err'] = $this->userModel->validateEmail($data['email']);
            $data['phone_number_err'] = $this->userModel->validatePhoneNumber($data['phone_number']);
            $data['password_err'] = $this->userModel->validatePassword($data['password']);
            $data['confirm_password_err'] = $this->userModel->validateConfirmPassword($data['password'], $data['confirm_password']);

            if (empty($data['first_name_err']) && empty($data['last_name_err']) && empty($data['email_err']) && empty($data['phone_number_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if ($this->userModel->register($data)) {
                    // Auto-login the user after successful registration
                    $user = $this->userModel->findUserByEmail($data['email']);
                    if ($user) {
                        $this->createUserSession($user);
                    } else {
                        // Fallback: redirect to login with success message
                        flash('register_success', 'You are registered and can log in');
                        header('location: ' . URLROOT . '/auth/login');
                    }
        } else {
          // Surface model error to view
          $err = $this->userModel->getLastError();
          if ($err) {
            // Detect MySQL duplicate key error
            if (strpos($err, 'Integrity constraint violation') !== false || strpos($err, 'Duplicate entry') !== false) {
              // Try to map to a field (email or phone)
              if (strpos($err, 'email') !== false) {
                $data['email_err'] = 'Email is already taken.';
              } elseif (strpos($err, 'phone_number') !== false || strpos($err, 'phone') !== false) {
                $data['phone_number_err'] = 'Phone number is already taken.';
              } else {
                $data['general_err'] = 'A record with the same value already exists.';
              }
            } else {
              $data['general_err'] = $err;
            }
          } else {
            $data['general_err'] = 'Something went wrong during registration.';
          }
          $this->view('auth/register', $data);
        }
      } else {
        $this->view('auth/register', $data);
      }
    } else {
            $data = [
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'phone_number' => '',
                'password' => '',
                'confirm_password' => '',
                'first_name_err' => '',
                'last_name_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            $this->view('auth/register', $data);
        }
    }

    public function login(){
      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process form
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        $data =[
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password']),
          'email_err' => '',
          'password_err' => '',      
        ];

        // Validate Email
        if(empty($data['email'])){
          $data['email_err'] = 'Please enter email';
        }

        // Validate Password
        if(empty($data['password'])){
          $data['password_err'] = 'Please enter password';
        }

        // Check for user/email
        if($this->userModel->findUserByEmail($data['email'])){
          // User found
        } else {
          // User not found
          $data['email_err'] = 'No user found';
        }

        // Make sure errors are empty
        if(empty($data['email_err']) && empty($data['password_err'])){
          // Validated
          // Check and set logged in user
          $loggedInUser = $this->userModel->login($data['email'], $data['password']);

          if($loggedInUser){
            // Create Session
            $this->createUserSession($loggedInUser);
          } else {
            $data['password_err'] = 'Password incorrect';

            $this->view('auth/login', $data);
          }
        } else {
          // Load view with errors
          $this->view('auth/login', $data);
        }


      } else {
        // Init data
        $data =[    
          'email' => '',
          'password' => '',
          'email_err' => '',
          'password_err' => '',        
        ];

        // Load view
        $this->view('auth/login', $data);
      }
    }

    public function createUserSession($user){
      // Align session keys with users table columns (user_id, first_name, last_name)
      $_SESSION['user_id'] = $user->user_id ?? ($user->id ?? null);
      $_SESSION['user_email'] = $user->email ?? null;
      $_SESSION['user_name'] = trim((($user->first_name ?? '') . ' ' . ($user->last_name ?? '')));
      // store role if present
      if (isset($user->role)) {
        // normalize role to lowercase string for consistent checks
        $_SESSION['user_role'] = strtolower($user->role);
      }
      // Also set a simple display name fallback
      if (empty($_SESSION['user_name'])) {
        $_SESSION['user_name'] = $user->email ?? 'User';
      }
      // Redirect admins to admin dashboard
      if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        header('location:' . URLROOT . '/admin');
        exit;
      } else {
        header('location:' . URLROOT . '/pages/index');
        exit;
      }
    }

    public function logout(){
      unset($_SESSION['user_id']);
      unset($_SESSION['user_email']);
      unset($_SESSION['user_name']);
      unset($_SESSION['user_role']);
      session_destroy();
      header('location:' . URLROOT . '/auth/login');
    }
}
