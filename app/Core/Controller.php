<?php
/*
 * Base Controller
 * Loads the models and views
 */
class Controller {
  // Load model
  public function model($model) {
    // Require model file
    require_once '../app/Models/' . $model . '.php';

    // Instatiate model
    return new $model();
  }

  // Load view
  public function view($view, $data = []) {
    // Check for view file
    if (file_exists('../app/Views/' . $view . '.php')) {
      extract($data);
      require_once '../app/Views/' . $view . '.php';
    } else {
      // View does not exist
      die('View does not exist');
    }
  }
}
