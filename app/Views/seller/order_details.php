<?php require APPROOT . '/Views/seller/layout.php'; ?>

<div class="row mt-4">
  <div class="col-md-8">
    <h1 class="page-title">
      <i class="fa fa-receipt"></i> Order #<?php echo $data['order']->order_id; ?> Details
    </h1>
  </div>
  <div class="col-md-4 text-right">
    <a href="<?php echo URLROOT; ?>/seller/myOrders" class="btn btn-outline-secondary">
      <i class="fa fa-arrow-left"></i> Back to Orders
    </a>
  </div>
</div>

<div class="row mt-4">
  <!-- Order Info -->
  <div class="col-md-8">
    <div class="card mb-3">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fa fa-info-circle"></i> Order Information</h5>
      </div>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-6">
            <h6 class="text-muted">Order Date</h6>
            <p><?php echo date('F d, Y \a\t h:i A', strtotime($data['order']->created_at)); ?></p>
          </div>
          <div class="col-md-6">
            <h6 class="text-muted">Payment Status</h6>
            <p>
              <?php 
                $statusClass = $data['order']->payment_status === 'paid' ? 'badge-success' : 'badge-warning';
                $statusIcon = $data['order']->payment_status === 'paid' ? 'check-circle' : 'clock-o';
              ?>
              <span class="badge <?php echo $statusClass; ?> py-2 px-3">
                <i class="fa fa-<?php echo $statusIcon; ?>"></i> <?php echo ucfirst($data['order']->payment_status); ?>
              </span>
            </p>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-6">
            <h6 class="text-muted">Order Total</h6>
            <h3 class="text-success">$<?php echo number_format($data['order']->total_amount, 2); ?></h3>
          </div>
          <div class="col-md-6">
            <h6 class="text-muted">Carbon Footprint</h6>
            <h3 class="text-danger">
              <i class="fa fa-leaf"></i> <?php echo number_format($data['order']->total_carbon, 2); ?> kg CO₂
            </h3>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Items -->
    <div class="card">
      <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fa fa-list"></i> Items in Order</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="bg-light">
              <tr>
                <th>Product Name</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Carbon per Unit</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $subtotal = 0;
                foreach ($data['orderItems'] as $item) : 
                  $itemTotal = $item->item_total;
                  $subtotal += $itemTotal;
              ?>
                <tr>
                  <td>
                    <strong><?php echo $item->product_name; ?></strong>
                  </td>
                  <td>$<?php echo number_format($item->price, 2); ?></td>
                  <td><?php echo $item->quantity; ?></td>
                  <td>
                    <strong>$<?php echo number_format($itemTotal, 2); ?></strong>
                  </td>
                  <td>
                    <span class="badge badge-danger">
                      <?php echo number_format($item->carbon_footprint, 2); ?> kg
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="p-3 bg-light text-right">
          <h5>Subtotal: <strong class="text-success">$<?php echo number_format($subtotal, 2); ?></strong></h5>
        </div>
      </div>
    </div>
  </div>

  <!-- Customer Info -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header bg-secondary text-white">
        <h5 class="mb-0"><i class="fa fa-user"></i> Customer Information</h5>
      </div>
      <div class="card-body">
        <h6 class="text-muted">Name</h6>
        <p><strong><?php echo $data['order']->first_name . ' ' . $data['order']->last_name; ?></strong></p>

        <h6 class="text-muted mt-3">Email</h6>
        <p>
          <a href="mailto:<?php echo $data['order']->email; ?>">
            <?php echo $data['order']->email; ?>
          </a>
        </p>

        <hr>

        <h6 class="text-muted">Order Summary</h6>
        <ul class="list-unstyled">
          <li class="mb-2">
            <strong>Items:</strong> <?php echo count($data['orderItems']); ?>
          </li>
          <li class="mb-2">
            <strong>Total:</strong> <span class="text-success">$<?php echo number_format($data['order']->total_amount, 2); ?></span>
          </li>
          <li class="mb-2">
            <strong>Carbon:</strong> <span class="text-danger"><?php echo number_format($data['order']->total_carbon, 2); ?> kg</span>
          </li>
        </ul>

        <hr>

        <div class="alert alert-info small">
          <i class="fa fa-info-circle"></i> 
          This order contains items sold by you. The customer purchased <?php echo count($data['orderItems']); ?> product(s) with a total carbon footprint of <?php echo number_format($data['order']->total_carbon, 2); ?> kg CO₂.
        </div>
      </div>
    </div>

    <!-- Carbon Impact Info -->
    <div class="card mt-3">
      <div class="card-header bg-warning text-white">
        <h5 class="mb-0"><i class="fa fa-leaf"></i> Carbon Impact</h5>
      </div>
      <div class="card-body">
        <div class="text-center mb-3">
          <h3 class="text-danger"><?php echo number_format($data['order']->total_carbon, 2); ?> kg</h3>
          <p class="text-muted mb-0">Total CO₂ for this order</p>
        </div>

        <div class="bg-light p-3 rounded">
          <small>
            <strong>Did you know?</strong> This order has a carbon footprint equivalent to:
            <ul class="mb-0 mt-2">
              <li><?php echo round($data['order']->total_carbon / 21, 2); ?> miles driven by a car</li>
              <li><?php echo round($data['order']->total_carbon / 0.05, 0); ?> plastic bags</li>
            </ul>
          </small>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require APPROOT . '/Views/seller/layout_end.php'; ?>