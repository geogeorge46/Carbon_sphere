<?php require APPROOT . '/Views/admin/layout.php'; ?>

<h2 class="mt-4">Orders</h2>
<table class="table table-striped">
  <thead><tr><th>ID</th><th>User</th><th>Total</th><th>Total Carbon</th><th>Status</th><th>Date</th></tr></thead>
  <tbody>
    <?php foreach($data['orders'] as $o) : ?>
      <tr>
        <td><?php echo $o->order_id; ?></td>
        <td><?php echo $o->first_name . ' ' . $o->last_name; ?></td>
        <td>$<?php echo $o->total_amount; ?></td>
        <td><?php echo $o->total_carbon; ?> kg CO2</td>
        <td><?php echo $o->payment_status; ?></td>
        <td><?php echo $o->created_at; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php require APPROOT . '/Views/admin/layout_end.php'; ?>
