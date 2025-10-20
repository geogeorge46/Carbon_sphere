<?php require APPROOT . '/Views/seller/layout.php'; ?>

<div class="row mt-4">
  <div class="col-md-12">
    <h1 class="page-title">
      <i class="fa fa-bar-chart"></i> Earnings & Carbon Report
    </h1>
    <p class="text-muted">Track your revenue and environmental impact over time.</p>
  </div>
</div>

<!-- Key Metrics -->
<div class="row mt-4">
  <div class="col-md-3 mb-3">
    <div class="card metric-card">
      <div class="card-body">
        <h6 class="card-title text-muted">Total Revenue</h6>
        <h3 class="metric-value text-success">
          $<?php echo number_format($data['totalSales']->total_revenue ?? 0, 2); ?>
        </h3>
        <small class="text-muted">
          <i class="fa fa-arrow-up"></i> from all orders
        </small>
      </div>
    </div>
  </div>

  <div class="col-md-3 mb-3">
    <div class="card metric-card">
      <div class="card-body">
        <h6 class="card-title text-muted">Total Orders</h6>
        <h3 class="metric-value text-primary">
          <?php echo $data['totalSales']->total_orders ?? 0; ?>
        </h3>
        <small class="text-muted">
          <i class="fa fa-shopping-cart"></i> completed
        </small>
      </div>
    </div>
  </div>

  <div class="col-md-3 mb-3">
    <div class="card metric-card">
      <div class="card-body">
        <h6 class="card-title text-muted">Items Sold</h6>
        <h3 class="metric-value text-info">
          <?php echo $data['totalSales']->total_items_sold ?? 0; ?>
        </h3>
        <small class="text-muted">
          <i class="fa fa-cube"></i> units
        </small>
      </div>
    </div>
  </div>

  <div class="col-md-3 mb-3">
    <div class="card metric-card">
      <div class="card-body">
        <h6 class="card-title text-muted">Carbon Emitted</h6>
        <h3 class="metric-value text-danger">
          <?php echo number_format($data['totalSales']->total_carbon_emitted ?? 0, 2); ?> kg
        </h3>
        <small class="text-muted">
          <i class="fa fa-cloud"></i> CO₂
        </small>
      </div>
    </div>
  </div>
</div>

<!-- Charts -->
<div class="row mt-4">
  <!-- Monthly Revenue Chart -->
  <div class="col-md-6 mb-4">
    <div class="card">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fa fa-money"></i> Monthly Revenue Trend</h5>
      </div>
      <div class="card-body">
        <canvas id="revenueChart" style="max-height: 300px;"></canvas>
      </div>
    </div>
  </div>

  <!-- Monthly Carbon Chart -->
  <div class="col-md-6 mb-4">
    <div class="card">
      <div class="card-header bg-danger text-white">
        <h5 class="mb-0"><i class="fa fa-leaf"></i> Monthly Carbon Impact</h5>
      </div>
      <div class="card-body">
        <canvas id="carbonChart" style="max-height: 300px;"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Combined Revenue & Carbon Chart -->
<div class="row mt-4">
  <div class="col-md-12 mb-4">
    <div class="card">
      <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fa fa-line-chart"></i> Revenue vs Carbon Trend</h5>
      </div>
      <div class="card-body">
        <canvas id="combinedChart" style="max-height: 350px;"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Product Analysis -->
<div class="row mt-4">
  <div class="col-md-12 mb-4">
    <div class="card">
      <div class="card-header bg-secondary text-white">
        <h5 class="mb-0"><i class="fa fa-product-hunt"></i> Product Analysis</h5>
      </div>
      <div class="card-body p-0">
        <?php if (!empty($data['products'])) : ?>
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="bg-light">
                <tr>
                  <th>Product Name</th>
                  <th>Price</th>
                  <th>Carbon Footprint</th>
                  <th>Carbon-to-Price Ratio</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($data['products'] as $product) : ?>
                  <tr>
                    <td><?php echo $product->product_name; ?></td>
                    <td><strong class="text-success">$<?php echo number_format($product->price, 2); ?></strong></td>
                    <td>
                      <span class="badge badge-danger">
                        <?php echo number_format($product->carbon_footprint, 2); ?> kg
                      </span>
                    </td>
                    <td>
                      <?php 
                        $ratio = $product->price > 0 ? $product->carbon_footprint / $product->price : 0;
                        if ($ratio < 0.1) {
                          $ratioClass = 'text-success';
                          $ratioLabel = 'Low';
                        } elseif ($ratio < 0.5) {
                          $ratioClass = 'text-warning';
                          $ratioLabel = 'Medium';
                        } else {
                          $ratioClass = 'text-danger';
                          $ratioLabel = 'High';
                        }
                      ?>
                      <span class="<?php echo $ratioClass; ?>">
                        <?php echo number_format($ratio, 3); ?> kg/$ (<?php echo $ratioLabel; ?>)
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else : ?>
          <div class="p-4 text-center text-muted">
            <p>No products to analyze. <a href="<?php echo URLROOT; ?>/seller/addProduct">Add products</a> to get started.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Eco Performance Tips -->
<div class="row mt-4 mb-5">
  <div class="col-md-12">
    <div class="card border-success">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fa fa-lightbulb-o"></i> Tips to Reduce Carbon Footprint</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <h6><i class="fa fa-check-circle text-success"></i> Focus on Low-Carbon Products</h6>
            <p class="small text-muted">Prioritize sourcing and selling products with lower carbon footprints. Eco-conscious customers prefer these!</p>
          </div>
          <div class="col-md-6">
            <h6><i class="fa fa-check-circle text-success"></i> Optimize Packaging</h6>
            <p class="small text-muted">Use sustainable and minimal packaging to reduce transportation carbon emissions.</p>
          </div>
          <div class="col-md-6 mt-2">
            <h6><i class="fa fa-check-circle text-success"></i> Local Sourcing</h6>
            <p class="small text-muted">Partner with local suppliers to minimize shipping distances and carbon emissions.</p>
          </div>
          <div class="col-md-6 mt-2">
            <h6><i class="fa fa-check-circle text-success"></i> Track & Report</h6>
            <p class="small text-muted">Regularly monitor your carbon footprint and share eco-performance with customers.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Monthly Sales Data
<?php if (!empty($data['monthlySales'])) : ?>
  const monthlySalesData = <?php echo json_encode(array_reverse($data['monthlySales'])); ?>;
  const months = monthlySalesData.map(item => item.month);
  const revenues = monthlySalesData.map(item => parseFloat(item.revenue) || 0);
  const carbons = monthlySalesData.map(item => parseFloat(item.carbon) || 0);

  // Revenue Chart
  const revenueCtx = document.getElementById('revenueChart').getContext('2d');
  new Chart(revenueCtx, {
    type: 'bar',
    data: {
      labels: months,
      datasets: [{
        label: 'Monthly Revenue ($)',
        data: revenues,
        backgroundColor: 'rgba(40, 167, 69, 0.7)',
        borderColor: '#28a745',
        borderWidth: 1,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      scales: {
        y: {
          beginAtZero: true,
          title: { display: true, text: 'Revenue ($)' }
        }
      },
      plugins: {
        legend: { display: true, position: 'top' }
      }
    }
  });

  // Carbon Chart
  const carbonCtx = document.getElementById('carbonChart').getContext('2d');
  new Chart(carbonCtx, {
    type: 'bar',
    data: {
      labels: months,
      datasets: [{
        label: 'Monthly Carbon (kg CO₂)',
        data: carbons,
        backgroundColor: 'rgba(220, 53, 69, 0.7)',
        borderColor: '#dc3545',
        borderWidth: 1,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      scales: {
        y: {
          beginAtZero: true,
          title: { display: true, text: 'Carbon (kg)' }
        }
      },
      plugins: {
        legend: { display: true, position: 'top' }
      }
    }
  });

  // Combined Chart
  const combinedCtx = document.getElementById('combinedChart').getContext('2d');
  new Chart(combinedCtx, {
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
      maintainAspectRatio: true,
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
</script>

<?php require APPROOT . '/Views/seller/layout_end.php'; ?>