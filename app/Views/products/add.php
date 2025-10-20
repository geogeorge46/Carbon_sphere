<?php require APPROOT . '/Views/inc/header.php'; ?>
  <a href="<?php echo URLROOT; ?>/products" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
  <div class="card card-body bg-light mt-5">
    <h2>Add Product</h2>
    <p>Create a product with this form</p>
    <form action="<?php echo URLROOT; ?>/products/add" method="post">
      <div class="form-group">
        <label for="product_name">Product Name: <sup>*</sup></label>
        <input type="text" name="product_name" class="form-control form-control-lg <?php echo (!empty($data['product_name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['product_name']; ?>">
        <span class="invalid-feedback"><?php echo $data['product_name_err']; ?></span>
      </div>
      <div class="form-group">
        <label for="description">Description: <sup>*</sup></label>
        <textarea name="description" class="form-control form-control-lg <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>"><?php echo $data['description']; ?></textarea>
        <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
      </div>
      <div class="form-group">
        <label for="price">Price: <sup>*</sup></label>
        <input type="text" name="price" class="form-control form-control-lg <?php echo (!empty($data['price_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['price']; ?>">
        <span class="invalid-feedback"><?php echo $data['price_err']; ?></span>
      </div>
      <div class="form-group">
        <label for="carbon_footprint">Carbon Footprint: <sup>*</sup></label>
        <input type="text" name="carbon_footprint" class="form-control form-control-lg <?php echo (!empty($data['carbon_footprint_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['carbon_footprint']; ?>">
        <span class="invalid-feedback"><?php echo $data['carbon_footprint_err']; ?></span>
      </div>
      <input type="submit" class="btn btn-success" value="Submit">
    </form>
  </div>
<?php require APPROOT . '/Views/inc/footer.php'; ?>
