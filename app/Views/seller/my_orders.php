<?php require APPROOT . '/Views/seller/layout.php'; ?>

<div class="row mt-4">
  <div class="col-md-12">
    <h1 class="page-title">
      <i class="fa fa-shopping-cart"></i> My Orders
    </h1>
    <p class="text-muted">View and manage all orders for your products.</p>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-12">
    <?php if (!empty($data['orders'])) : ?>
      <!-- Filter/Status Summary -->
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title text-muted">Total Orders</h6>
              <h3 class="text-primary"><?php echo count($data['orders']); ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title text-muted">Pending Payment</h6>
              <h3 class="text-warning">
                <?php 
                  $pending = 0;
                  foreach ($data['orders'] as $order) {
                    if ($order->payment_status === 'pending') $pending++;
                  }
                  echo $pending;
                ?>
              </h3>
            </div>
          </div>
        </div>
      </div>

      <!-- Orders Table -->
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="fa fa-list"></i> Order History</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="bg-light">
                <tr>
                  <th>Order ID</th>
                  <th>Customer</th>
                  <th>Amount</th>
                  <th>Items</th>
                  <th>Carbon Impact</th>
                  <th>Payment Status</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($data['orders'] as $order) : ?>
                  <tr>
                    <td>
                      <strong>#<?php echo $order->order_id; ?></strong>
                    </td>
                    <td>
                      <div>
                        <strong><?php echo $order->first_name . ' ' . $order->last_name; ?></strong>
                        <br>
                        <small class="text-muted"><?php echo $order->email; ?></small>
                      </div>
                    </td>
                    <td>
                      <strong class="text-success">$<?php echo number_format($order->total_amount, 2); ?></strong>
                    </td>
                    <td>
                      <span class="badge badge-info">
                        Multiple items
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-danger">
                        <i class="fa fa-leaf"></i> <?php echo number_format($order->total_carbon, 2); ?> kg
                      </span>
                    </td>
                    <td>
                      <?php 
                        if ($order->payment_status === 'paid') {
                          $statusClass = 'badge-success';
                          $statusText = 'Paid';
                          $statusIcon = 'check-circle';
                        } else {
                          $statusClass = 'badge-warning';
                          $statusText = 'Pending';
                          $statusIcon = 'clock-o';
                        }
                      ?>
                      <span class="badge <?php echo $statusClass; ?>">
                        <i class="fa fa-<?php echo $statusIcon; ?>"></i> <?php echo $statusText; ?>
                      </span>
                    </td>
                    <td>
                      <small class="text-muted">
                        <?php echo date('M d, Y h:i A', strtotime($order->created_at)); ?>
                      </small>
                    </td>
                    <td>
                      <a href="<?php echo URLROOT; ?>/seller/orderDetails/<?php echo $order->order_id; ?>" class="btn btn-sm btn-primary">
                        <i class="fa fa-eye"></i> View Details
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="row mt-4">
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title text-muted">Average Order Value</h6>
              <h3 class="text-success">
                $<?php 
                  $totalAmount = 0;
                  foreach ($data['orders'] as $order) {
                    $totalAmount += $order->total_amount;
                  }
                  $avgAmount = count($data['orders']) > 0 ? $totalAmount / count($data['orders']) : 0;
                  echo number_format($avgAmount, 2);
                ?>
              </h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title text-muted">Total Revenue</h6>
              <h3 class="text-success">$<?php echo number_format($totalAmount, 2); ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title text-muted">Total Carbon Impact</h6>
              <h3 class="text-danger">
                <?php 
                  $totalCarbon = 0;
                  foreach ($data['orders'] as $order) {
                    $totalCarbon += $order->total_carbon;
                  }
                  echo number_format($totalCarbon, 2);
                ?> kg
              </h3>
            </div>
          </div>
        </div>
      </div>

    <?php else : ?>
      <div class="card">
        <div class="card-body text-center py-5">
          <i class="fa fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
          <h4 class="mt-3 text-muted">No Orders Yet</h4>
          <p class="text-muted">You haven't received any orders yet. Start by adding products to your store!</p>
          <a href="<?php echo URLROOT; ?>/seller/addProduct" class="btn btn-primary btn-lg mt-3">
            <i class="fa fa-plus-circle"></i> Add Products
          </a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require APPROOT . '/Views/seller/layout_end.php'; ?>