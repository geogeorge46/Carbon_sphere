<?php require APPROOT . '/Views/inc/header.php'; ?>
<div class="container-fluid admin-page">
  <div class="row">
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
      <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/admin">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/admin/users">Users</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/admin/products">Products</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/admin/categories">Categories</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/admin/orders">Orders</a></li>
        </ul>
      </div>
    </nav>
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
