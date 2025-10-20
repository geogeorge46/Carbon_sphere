<?php require APPROOT . '/Views/admin/layout.php'; ?>

<h2 class="mt-4">Dashboard</h2>
<div class="row">
  <div class="col-md-3">
    <div class="card mb-3"><div class="card-body"><h5>Total Users</h5><p><?php echo $data['metrics']->total_users ?? 0; ?></p></div></div>
  </div>
  <div class="col-md-3">
    <div class="card mb-3"><div class="card-body"><h5>Total Products</h5><p><?php echo $data['metrics']->total_products ?? 0; ?></p></div></div>
  </div>
  <div class="col-md-3">
    <div class="card mb-3"><div class="card-body"><h5>Total Revenue</h5><p>$<?php echo $data['metrics']->total_revenue ?? 0; ?></p></div></div>
  </div>
  <div class="col-md-3">
    <div class="card mb-3"><div class="card-body"><h5>Total Carbon</h5><p><?php echo $data['metrics']->total_carbon ?? 0; ?> kg CO₂</p></div></div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h5>Carbon Emitted (last 12 months)</h5>
        <canvas id="carbonChart" width="400" height="200"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card mb-3"><div class="card-body"><h5>Revenue (last 12 months)</h5><canvas id="revenueChart" width="400" height="200"></canvas></div></div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-8">
    <div class="card">
      <div class="card-body">
        <h5>Top Products by Carbon</h5>
        <canvas id="topProductsChart" width="400" height="200"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card mb-3"><div class="card-body"><h5>Carbon by Category</h5>
      <ul>
        <?php foreach($data['carbonByCategory'] as $c) : ?>
          <li><?php echo $c->category_name; ?>: <?php echo $c->total_carbon; ?> kg</li>
        <?php endforeach; ?>
      </ul>
    </div></div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Prepare PHP data for JS
const carbonByMonth = <?php echo json_encode(array_reverse(array_map(function($r){ return ['month'=>$r->month, 'value'=> (float)$r->total_carbon]; }, $data['carbonByMonth']))); ?>;
const revenueByMonth = <?php echo json_encode(array_reverse(array_map(function($r){ return ['month'=>$r->month, 'value'=> (float)$r->total_revenue]; }, $data['revenueByMonth']))); ?>;
const topProducts = <?php echo json_encode(array_map(function($p){ return ['name'=>$p->product_name, 'value'=>(float)$p->total_carbon]; }, $data['topProducts'])); ?>;

function renderBarChart(canvasId, labels, values, labelText, colors){
  const ctx = document.getElementById(canvasId);
  if(!ctx) return;
  new Chart(ctx, {
    type: 'bar',
    data: { labels: labels, datasets: [{ label: labelText, data: values, backgroundColor: colors }] },
    options: { responsive: true }
  });
}

// Carbon by month
renderBarChart('carbonChart', carbonByMonth.map(x=>x.month), carbonByMonth.map(x=>x.value), 'kg CO2', '#4e73df');
// Revenue by month
renderBarChart('revenueChart', revenueByMonth.map(x=>x.month), revenueByMonth.map(x=>x.value), 'USD', '#1cc88a');
// Top products
renderBarChart('topProductsChart', topProducts.map(x=>x.name), topProducts.map(x=>x.value), 'kg CO2', '#36b9cc');
</script>

<?php require APPROOT . '/Views/admin/layout_end.php'; ?>
