<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/seller-dashboard.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
  <title>Seller Dashboard - <?php echo SITENAME; ?></title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?php echo URLROOT; ?>">
        <i class="fa fa-leaf"></i> <?php echo SITENAME; ?>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSeller">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSeller">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>">
              <i class="fa fa-home"></i> Home
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URLROOT; ?>/products">
              <i class="fa fa-shopping-bag"></i> Browse Products
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
              <i class="fa fa-user"></i> <?php echo $_SESSION['user_name']; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="<?php echo URLROOT; ?>/auth/logout">
                <i class="fa fa-sign-out"></i> Logout
              </a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid seller-dashboard">
    <div class="row">
      <!-- Sidebar -->
      <nav class="col-md-2 d-none d-md-block bg-dark sidebar">
        <div class="sidebar-sticky pt-3">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/seller/') && strpos($_SERVER['REQUEST_URI'], '/seller/index') !== false || strpos($_SERVER['REQUEST_URI'], '/seller') && !strpos($_SERVER['REQUEST_URI'], '/seller/') ? '' : ''); ?>" href="<?php echo URLROOT; ?>/seller">
                <i class="fa fa-dashboard"></i> Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT; ?>/seller/myProducts">
                <i class="fa fa-product-hunt"></i> My Products
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT; ?>/seller/addProduct">
                <i class="fa fa-plus-circle"></i> Add Product
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT; ?>/seller/myOrders">
                <i class="fa fa-shopping-cart"></i> My Orders
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT; ?>/seller/report">
                <i class="fa fa-bar-chart"></i> Reports
              </a>
            </li>
            <hr class="bg-secondary">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT; ?>/auth/logout">
                <i class="fa fa-sign-out"></i> Logout
              </a>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Main Content -->
      <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <!-- Flash Messages -->
        <?php if (isset($_SESSION['product_created'])) : ?>
          <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="fa fa-check-circle"></i> <?php echo $_SESSION['product_created']; unset($_SESSION['product_created']); ?>
            <button type="button" class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['product_updated'])) : ?>
          <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="fa fa-check-circle"></i> <?php echo $_SESSION['product_updated']; unset($_SESSION['product_updated']); ?>
            <button type="button" class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['product_deleted'])) : ?>
          <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="fa fa-check-circle"></i> <?php echo $_SESSION['product_deleted']; unset($_SESSION['product_deleted']); ?>
            <button type="button" class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['product_error'])) : ?>
          <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['product_error']; unset($_SESSION['product_error']); ?>
            <button type="button" class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
          </div>
        <?php endif; ?>