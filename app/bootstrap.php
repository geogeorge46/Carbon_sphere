<?php
// Start Session
session_start();

// Load Config
require_once 'Config/config.php';

// Load Helpers
require_once 'Helpers/session_helper.php';

// Autoload Core Libraries
spl_autoload_register(function($className){
    $paths = ['Core', 'Controllers', 'Models', 'Helpers', 'Repositories', 'Services'];
    foreach($paths as $path){
        $file = APPROOT . '/' . $path . '/' . $className . '.php';
        if(file_exists($file)){
            require_once $file;
            return;
        }
    }
});
