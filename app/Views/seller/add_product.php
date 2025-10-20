<?php require APPROOT . '/Views/seller/layout.php'; ?>

<div class="row mt-4">
  <div class="col-md-8">
    <h1 class="page-title">
      <i class="fa fa-plus-circle"></i> Add New Product
    </h1>
  </div>
  <div class="col-md-4 text-right">
    <a href="<?php echo URLROOT; ?>/seller/myProducts" class="btn btn-outline-secondary">
      <i class="fa fa-arrow-left"></i> Back to Products
    </a>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-8">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fa fa-form"></i> Product Information</h5>
      </div>
      <div class="card-body">
        <?php if (isset($data['general_err'])) : ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-circle"></i> <?php echo $data['general_err']; ?>
            <button type="button" class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <form action="<?php echo URLROOT; ?>/seller/addProduct" method="POST" id="addProductForm">
          <!-- Product Name -->
          <div class="form-group">
            <label for="product_name" class="font-weight-bold">
              <i class="fa fa-tag"></i> Product Name *
            </label>
            <input type="text" 
                   id="product_name" 
                   name="product_name" 
                   class="form-control <?php echo !empty($data['product_name_err']) ? 'is-invalid' : ''; ?>"
                   value="<?php echo $data['product_name']; ?>"
                   placeholder="Enter product name"
                   required>
            <?php if (!empty($data['product_name_err'])) : ?>
              <div class="invalid-feedback"><?php echo $data['product_name_err']; ?></div>
            <?php endif; ?>
          </div>

          <!-- Category -->
          <div class="form-group">
            <label for="category_id" class="font-weight-bold">
              <i class="fa fa-list"></i> Category *
            </label>
            <select id="category_id" 
                    name="category_id" 
                    class="form-control <?php echo !empty($data['category_id_err']) ? 'is-invalid' : ''; ?>"
                    required>
              <option value="">-- Select Category --</option>
              <option value="1" <?php echo $data['category_id'] == 1 ? 'selected' : ''; ?>>Electronics</option>
              <option value="2" <?php echo $data['category_id'] == 2 ? 'selected' : ''; ?>>Clothing</option>
              <option value="3" <?php echo $data['category_id'] == 3 ? 'selected' : ''; ?>>Food & Beverage</option>
              <option value="4" <?php echo $data['category_id'] == 4 ? 'selected' : ''; ?>>Home & Garden</option>
              <option value="5" <?php echo $data['category_id'] == 5 ? 'selected' : ''; ?>>Sports & Outdoors</option>
              <option value="6" <?php echo $data['category_id'] == 6 ? 'selected' : ''; ?>>Books & Media</option>
            </select>
            <?php if (!empty($data['category_id_err'])) : ?>
              <div class="invalid-feedback"><?php echo $data['category_id_err']; ?></div>
            <?php endif; ?>
          </div>

          <!-- Description -->
          <div class="form-group">
            <label for="description" class="font-weight-bold">
              <i class="fa fa-file-text"></i> Description *
            </label>
            <textarea id="description" 
                      name="description" 
                      class="form-control <?php echo !empty($data['description_err']) ? 'is-invalid' : ''; ?>"
                      rows="4"
                      placeholder="Describe your product in detail"
                      required><?php echo $data['description']; ?></textarea>
            <?php if (!empty($data['description_err'])) : ?>
              <div class="invalid-feedback"><?php echo $data['description_err']; ?></div>
            <?php endif; ?>
            <small class="form-text text-muted">Provide detailed information about your product</small>
          </div>

          <!-- Product Image URL -->
          <div class="form-group">
            <label for="image_url" class="font-weight-bold">
              <i class="fa fa-image"></i> Product Image URL
            </label>
            <input type="url" 
                   id="image_url" 
                   name="image_url" 
                   class="form-control <?php echo !empty($data['image_url_err']) ? 'is-invalid' : ''; ?>"
                   value="<?php echo $data['image_url']; ?>"
                   placeholder="https://example.com/image.jpg"
                   onchange="previewImage(this.value)"
                   oninput="previewImage(this.value)">
            <?php if (!empty($data['image_url_err'])) : ?>
              <div class="invalid-feedback"><?php echo $data['image_url_err']; ?></div>
            <?php endif; ?>
            <small class="form-text text-muted">Enter a valid image URL. Preview will appear below. (Optional)</small>
          </div>

          <!-- Image Preview -->
          <div class="form-group">
            <div id="imagePreviewContainer" style="display: none;" class="border rounded p-3 mb-3 bg-light">
              <p class="small text-muted mb-2"><i class="fa fa-eye"></i> Image Preview:</p>
              <img id="imagePreview" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 4px; object-fit: cover;">
            </div>
          </div>

          <!-- Price -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="price" class="font-weight-bold">
                <i class="fa fa-dollar"></i> Price ($) *
              </label>
              <input type="number" 
                     id="price" 
                     name="price" 
                     class="form-control <?php echo !empty($data['price_err']) ? 'is-invalid' : ''; ?>"
                     value="<?php echo $data['price']; ?>"
                     step="0.01"
                     min="0.01"
                     placeholder="0.00"
                     required>
              <?php if (!empty($data['price_err'])) : ?>
                <div class="invalid-feedback"><?php echo $data['price_err']; ?></div>
              <?php endif; ?>
            </div>

            <!-- Carbon Footprint -->
            <div class="form-group col-md-6">
              <label for="carbon_footprint" class="font-weight-bold">
                <i class="fa fa-leaf"></i> Carbon Footprint (kg CO₂) *
              </label>
              <input type="number" 
                     id="carbon_footprint" 
                     name="carbon_footprint" 
                     class="form-control <?php echo !empty($data['carbon_footprint_err']) ? 'is-invalid' : ''; ?>"
                     value="<?php echo $data['carbon_footprint']; ?>"
                     step="0.01"
                     min="0"
                     placeholder="0.00"
                     required>
              <?php if (!empty($data['carbon_footprint_err'])) : ?>
                <div class="invalid-feedback"><?php echo $data['carbon_footprint_err']; ?></div>
              <?php endif; ?>
              <small class="form-text text-muted">CO₂ emissions per unit produced</small>
            </div>
          </div>

          <!-- Carbon Info Box -->
          <div class="alert alert-info mt-3">
            <h6 class="alert-heading"><i class="fa fa-info-circle"></i> Carbon Footprint Guide</h6>
            <ul class="mb-0 small">
              <li><strong>Electronics:</strong> 15-50 kg CO₂ per unit</li>
              <li><strong>Clothing:</strong> 2-10 kg CO₂ per unit</li>
              <li><strong>Food:</strong> 0.5-5 kg CO₂ per unit</li>
              <li><strong>Books:</strong> 0.5-2 kg CO₂ per unit</li>
            </ul>
          </div>

          <!-- Buttons -->
          <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
              <i class="fa fa-save"></i> Add Product
            </button>
            <a href="<?php echo URLROOT; ?>/seller/myProducts" class="btn btn-outline-secondary btn-lg">
              <i class="fa fa-times"></i> Cancel
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Info Sidebar -->
  <div class="col-md-4">
    <div class="card bg-light">
      <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fa fa-lightbulb-o"></i> Tips</h5>
      </div>
      <div class="card-body">
        <h6 class="card-title">Get More Visibility</h6>
        <p class="small">
          Add accurate product information including:
        </p>
        <ul class="small">
          <li>Clear product name</li>
          <li>Detailed description</li>
          <li>Realistic pricing</li>
          <li>Accurate carbon footprint</li>
        </ul>

        <hr>

        <h6 class="card-title">About Carbon Footprint</h6>
        <p class="small">
          The carbon footprint represents the total amount of CO₂ emissions produced during the product's lifecycle, including:
        </p>
        <ul class="small">
          <li>Manufacturing</li>
          <li>Transportation</li>
          <li>Packaging</li>
          <li>Distribution</li>
        </ul>

        <div class="alert alert-warning small mt-3">
          <strong>Pro Tip:</strong> Products with lower carbon footprint tend to attract more eco-conscious buyers!
        </div>
      </div>
    </div>
  </div>
</div>

<?php require APPROOT . '/Views/seller/layout_end.php'; ?>