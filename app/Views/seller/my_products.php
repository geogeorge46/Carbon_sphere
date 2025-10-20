<?php require APPROOT . '/Views/seller/layout.php'; ?>

<div class="row mt-4">
  <div class="col-md-8">
    <h1 class="page-title">
      <i class="fa fa-product-hunt"></i> My Products
    </h1>
  </div>
  <div class="col-md-4 text-right">
    <a href="<?php echo URLROOT; ?>/seller/addProduct" class="btn btn-primary">
      <i class="fa fa-plus-circle"></i> Add Product
    </a>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-12">
    <?php if (!empty($data['products'])) : ?>
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">
            <i class="fa fa-list"></i> 
            Total Products: <span class="badge badge-light"><?php echo count($data['products']); ?></span>
          </h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="bg-light">
                <tr>
                  <th>Image</th>
                  <th>Product Name</th>
                  <th>Category</th>
                  <th>Price</th>
                  <th>Carbon Footprint</th>
                  <th>Created</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($data['products'] as $product) : ?>
                  <tr>
                    <td style="vertical-align: middle;">
                      <?php if (!empty($product->image_url)) : ?>
                        <img src="<?php echo $product->image_url; ?>" alt="<?php echo $product->product_name; ?>" style="width: 50px; height: 50px; border-radius: 4px; object-fit: cover; cursor: pointer;" data-toggle="tooltip" title="<?php echo $product->product_name; ?>">
                      <?php else : ?>
                        <div style="width: 50px; height: 50px; border-radius: 4px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center;">
                          <i class="fa fa-image" style="color: #adb5bd;"></i>
                        </div>
                      <?php endif; ?>
                    </td>
                    <td>
                      <strong><?php echo $product->product_name; ?></strong>
                      <br>
                      <small class="text-muted"><?php echo substr($product->description, 0, 50); ?>...</small>
                    </td>
                    <td>
                      <span class="badge badge-info">
                        <?php 
                          $categories = [
                            1 => 'Electronics',
                            2 => 'Clothing',
                            3 => 'Food & Beverage',
                            4 => 'Home & Garden',
                            5 => 'Sports & Outdoors',
                            6 => 'Books & Media'
                          ];
                          echo $categories[$product->category_id] ?? 'Other';
                        ?>
                      </span>
                    </td>
                    <td>
                      <strong class="text-success">$<?php echo number_format($product->price, 2); ?></strong>
                    </td>
                    <td>
                      <span class="badge badge-danger">
                        <i class="fa fa-leaf"></i> <?php echo number_format($product->carbon_footprint, 2); ?> kg
                      </span>
                    </td>
                    <td>
                      <small class="text-muted">
                        <?php echo date('M d, Y', strtotime($product->created_at)); ?>
                      </small>
                    </td>
                    <td>
                      <div class="btn-group btn-group-sm" role="group">
                        <a href="<?php echo URLROOT; ?>/seller/editProduct/<?php echo $product->product_id; ?>" class="btn btn-warning">
                          <i class="fa fa-edit"></i> Edit
                        </a>
                        <form action="<?php echo URLROOT; ?>/seller/deleteProduct/<?php echo $product->product_id; ?>" method="POST" style="display:inline;">
                          <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?');">
                            <i class="fa fa-trash"></i> Delete
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Summary Stats -->
      <div class="row mt-4">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title text-muted">Average Carbon Footprint</h6>
              <h4 class="text-danger">
                <?php 
                  $totalCarbon = 0;
                  foreach ($data['products'] as $p) {
                    $totalCarbon += $p->carbon_footprint;
                  }
                  $avgCarbon = count($data['products']) > 0 ? $totalCarbon / count($data['products']) : 0;
                  echo number_format($avgCarbon, 2);
                ?> kg CO₂
              </h4>
              <small class="text-muted">Per product on average</small>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title text-muted">Total Carbon Footprint</h6>
              <h4 class="text-danger">
                <?php echo number_format($totalCarbon, 2); ?> kg CO₂
              </h4>
              <small class="text-muted">Of all your products</small>
            </div>
          </div>
        </div>
      </div>

    <?php else : ?>
      <div class="card">
        <div class="card-body text-center py-5">
          <i class="fa fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
          <h4 class="mt-3 text-muted">No Products Yet</h4>
          <p class="text-muted">You haven't added any products yet. Start by creating your first product!</p>
          <a href="<?php echo URLROOT; ?>/seller/addProduct" class="btn btn-primary btn-lg mt-3">
            <i class="fa fa-plus-circle"></i> Add Your First Product
          </a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require APPROOT . '/Views/seller/layout_end.php'; ?>