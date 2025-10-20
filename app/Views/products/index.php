<?php require APPROOT . '/Views/inc/header.php'; ?>
  <div class="row mb-3">
    <div class="col-md-6">
      <h1>Products</h1>
    </div>
    <div class="col-md-6">
      <?php if(!empty($data['is_seller']) && $data['is_seller']): ?>
        <a href="<?php echo URLROOT; ?>/products/add" class="btn btn-primary pull-right">
          <i class="fa fa-pencil"></i> Add Product
        </a>
      <?php endif; ?>
    </div>
  </div>

  <?php if(empty($data['products'])) : ?>
    <div class="alert alert-info text-center mt-4">
      <h4>No products available</h4>
      <?php if(!empty($data['is_seller']) && $data['is_seller']): ?>
        <p>Be the first to add a product!</p>
        <a href="<?php echo URLROOT; ?>/products/add" class="btn btn-success">Add a Product</a>
      <?php else: ?>
        <p>Check back soon for amazing eco-friendly products from our sellers!</p>
      <?php endif; ?>
    </div>
  <?php else : ?>
    <?php foreach($data['products'] as $product) : ?>
      <div class="card card-body mb-3">
        <h4 class="card-title"><?php echo htmlspecialchars($product->product_name); ?></h4>
        <div class="bg-light p-2 mb-3">
          Written by <?php echo htmlspecialchars($product->first_name . ' ' . $product->last_name); ?> on <?php echo $product->created_at; ?>
        </div>
        <p class="card-text"><?php echo htmlspecialchars($product->description); ?></p>
        <p class="card-text">Carbon Footprint: <?php echo $product->carbon_footprint; ?> kg CO₂</p>
        <a href="<?php echo URLROOT; ?>/products/show/<?php echo $product->product_id; ?>" class="btn btn-dark">More</a>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
<?php require APPROOT . '/Views/inc/footer.php'; ?>
