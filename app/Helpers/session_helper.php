<?php
// Simple session helper
function isLoggedIn(){
    if(isset($_SESSION['user_id'])){
        return true;
    } else {
        return false;
    }
}

function isAdmin(){
    if(isset($_SESSION['user_id']) && isset($_SESSION['user_email'])){
        // Try to read role from session if stored
        if(isset($_SESSION['user_role']) && strtolower($_SESSION['user_role']) === 'admin'){
            return true;
        }

        // Fallback: query DB for the user's role
        try {
            $db = DB::getInstance();
            $db->query('SELECT role FROM users WHERE user_id = :id');
            $db->bind(':id', $_SESSION['user_id']);
            $row = $db->single();
            if($row && isset($row->role) && strtolower($row->role) === 'admin'){
                // cache in session as lowercase
                $_SESSION['user_role'] = 'admin';
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    return false;
}

// Flash message function - set and retrieve one-time messages
function flash($name = '', $message = '', $class = 'alert alert-success'){
    if(!empty($name)){
        if(!empty($message) && empty($_SESSION[$name])){
            // Setting a message
            $_SESSION[$name] = [
                'message' => $message,
                'class' => $class
            ];
        } elseif(empty($message) && !empty($_SESSION[$name])){
            // Getting a message
            $flashMessage = $_SESSION[$name];
            unset($_SESSION[$name]);
            return $flashMessage;
        }
    }
    return false;
}
