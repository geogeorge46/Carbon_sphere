<?php
class View {
    public static function render($view, $data = []) {
    $viewPath = '../app/Views/' . $view . '.php';
        if (file_exists($viewPath)) {
            extract($data);
            require_once $viewPath;
        } else {
            die('View does not exist');
        }
    }
}
