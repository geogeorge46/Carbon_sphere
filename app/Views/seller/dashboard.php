<?php require APPROOT . '/Views/seller/layout.php'; ?>

<div class="row mt-4">
  <div class="col-md-12">
    <h1 class="page-title">
      <i class="fa fa-dashboard"></i> Seller Dashboard
    </h1>
    <p class="text-muted">Welcome back, <?php echo $_SESSION['user_name']; ?>! Here's your performance overview.</p>
  </div>
</div>

<!-- Key Metrics Row -->
<div class="row mt-4">
  <!-- Total Revenue Card -->
  <div class="col-md-3 mb-3">
    <div class="card metric-card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="card-title text-muted">Total Revenue</h6>
            <h3 class="metric-value">
              $<?php echo number_format($data['totalSales']->total_revenue ?? 0, 2); ?>
            </h3>
            <small class="text-success">
              <i class="fa fa-arrow-up"></i> from <?php echo $data['totalSales']->total_orders ?? 0; ?> orders
            </small>
          </div>
          <div class="metric-icon bg-success">
            <i class="fa fa-money"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Products Card -->
  <div class="col-md-3 mb-3">
    <div class="card metric-card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="card-title text-muted">Products Listed</h6>
            <h3 class="metric-value"><?php echo $data['productCount']; ?></h3>
            <small class="text-info">
              <a href="<?php echo URLROOT; ?>/seller/myProducts">View all</a>
            </small>
          </div>
          <div class="metric-icon bg-info">
            <i class="fa fa-product-hunt"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Items Sold Card -->
  <div class="col-md-3 mb-3">
    <div class="card metric-card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="card-title text-muted">Items Sold</h6>
            <h3 class="metric-value"><?php echo $data['totalSales']->total_items_sold ?? 0; ?></h3>
            <small class="text-warning">
              <i class="fa fa-shopping-cart"></i> Total units
            </small>
          </div>
          <div class="metric-icon bg-warning">
            <i class="fa fa-shopping-bag"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Carbon Emitted Card -->
  <div class="col-md-3 mb-3">
    <div class="card metric-card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="card-title text-muted">Carbon Emitted</h6>
            <h3 class="metric-value"><?php echo number_format($data['totalSales']->total_carbon_emitted ?? 0, 2); ?> kg</h3>
            <small class="text-danger">
              <i class="fa fa-leaf"></i> CO₂ footprint
            </small>
          </div>
          <div class="metric-icon bg-danger">
            <i class="fa fa-cloud"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Charts Row -->
<div class="row mt-4">
  <!-- Revenue & Carbon Chart -->
  <div class="col-md-8 mb-4">
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fa fa-bar-chart"></i> Monthly Sales & Carbon Trend</h5>
      </div>
      <div class="card-body">
        <canvas id="monthlySalesChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Average Carbon Card -->
  <div class="col-md-4 mb-4">
    <div class="card">
      <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fa fa-pie-chart"></i> Product Eco Performance</h5>
      </div>
      <div class="card-body">
        <div class="text-center mb-3">
          <h2 class="text-success"><?php echo number_format($data['avgCarbon'], 2); ?> kg</h2>
          <p class="text-muted">Average Carbon per Product</p>
        </div>
        <canvas id="carbonDistributionChart" style="max-height: 250px;"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Recent Orders -->
<div class="row mt-4">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header bg-secondary text-white">
        <h5 class="mb-0"><i class="fa fa-history"></i> Recent Orders</h5>
      </div>
      <div class="card-body">
        <?php if (!empty($data['recentOrders'])) : ?>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead class="bg-light">
                <tr>
                  <th>Order ID</th>
                  <th>Customer</th>
                  <th>Amount</th>
                  <th>Carbon Impact</th>
                  <th>Status</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($data['recentOrders'] as $order) : ?>
                  <tr>
                    <td><strong>#<?php echo $order->order_id; ?></strong></td>
                    <td><?php echo $order->first_name . ' ' . $order->last_name; ?></td>
                    <td><strong>$<?php echo number_format($order->total_amount, 2); ?></strong></td>
                    <td>
                      <span class="badge badge-danger">
                        <?php echo number_format($order->total_carbon, 2); ?> kg CO₂
                      </span>
                    </td>
                    <td>
                      <?php 
                        $statusClass = $order->payment_status === 'paid' ? 'badge-success' : 'badge-warning';
                        $statusIcon = $order->payment_status === 'paid' ? 'check-circle' : 'clock-o';
                      ?>
                      <span class="badge <?php echo $statusClass; ?>">
                        <i class="fa fa-<?php echo $statusIcon; ?>"></i> <?php echo ucfirst($order->payment_status); ?>
                      </span>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($order->created_at)); ?></td>
                    <td>
                      <a href="<?php echo URLROOT; ?>/seller/orderDetails/<?php echo $order->order_id; ?>" class="btn btn-sm btn-primary">
                        <i class="fa fa-eye"></i> View
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="mt-3 text-center">
            <a href="<?php echo URLROOT; ?>/seller/myOrders" class="btn btn-outline-primary">
              View All Orders <i class="fa fa-arrow-right"></i>
            </a>
          </div>
        <?php else : ?>
          <div class="alert alert-info">
            <i class="fa fa-info-circle"></i> No orders yet. Start by adding products!
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4 mb-5">
  <div class="col-md-12">
    <h5 class="mb-3"><i class="fa fa-flash"></i> Quick Actions</h5>
    <div class="d-flex gap-2">
      <a href="<?php echo URLROOT; ?>/seller/addProduct" class="btn btn-primary">
        <i class="fa fa-plus"></i> Add New Product
      </a>
      <a href="<?php echo URLROOT; ?>/seller/myProducts" class="btn btn-info">
        <i class="fa fa-list"></i> Manage Products
      </a>
      <a href="<?php echo URLROOT; ?>/seller/report" class="btn btn-warning">
        <i class="fa fa-chart-line"></i> View Reports
      </a>
    </div>
  </div>
</div>

<script>
// Monthly Sales Chart
<?php if (!empty($data['monthlySales'])) : ?>
  const monthlySales = <?php echo json_encode(array_reverse($data['monthlySales'])); ?>;
  const months = monthlySales.map(item => item.month);
  const revenues = monthlySales.map(item => item.revenue);
  const carbons = monthlySales.map(item => item.carbon);

  const ctx = document.getElementById('monthlySalesChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: months,
      datasets: [
        {
          label: 'Revenue ($)',
          data: revenues,
          borderColor: '#28a745',
          backgroundColor: 'rgba(40, 167, 69, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.4,
          yAxisID: 'y'
        },
        {
          label: 'Carbon Emitted (kg)',
          data: carbons,
          borderColor: '#dc3545',
          backgroundColor: 'rgba(220, 53, 69, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.4,
          yAxisID: 'y1'
        }
      ]
    },
    options: {
      responsive: true,
      interaction: {
        mode: 'index',
        intersect: false
      },
      scales: {
        y: {
          type: 'linear',
          display: true,
          position: 'left',
          title: { display: true, text: 'Revenue ($)' }
        },
        y1: {
          type: 'linear',
          display: true,
          position: 'right',
          title: { display: true, text: 'Carbon (kg)' },
          grid: { drawOnChartArea: false }
        }
      }
    }
  });
<?php endif; ?>

// Carbon Distribution Chart
const carbonCtx = document.getElementById('carbonDistributionChart').getContext('2d');
new Chart(carbonCtx, {
  type: 'doughnut',
  data: {
    labels: ['Sold Products', 'Unsold Inventory'],
    datasets: [{
      data: [<?php echo $data['totalSales']->total_carbon_emitted ?? 0; ?>, <?php echo max(0, $data['avgCarbon'] * $data['productCount'] - ($data['totalSales']->total_carbon_emitted ?? 0)); ?>],
      backgroundColor: ['#dc3545', '#e9ecef'],
      borderColor: '#fff',
      borderWidth: 2
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
      legend: { position: 'bottom' }
    }
  }
});
</script>

<?php require APPROOT . '/Views/seller/layout_end.php'; ?>