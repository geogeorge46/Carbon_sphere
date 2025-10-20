<?php require APPROOT . '/Views/admin/layout.php'; ?>

<h2 class="mt-4">Products</h2>
<table class="table table-striped">
  <thead><tr><th>ID</th><th>Name</th><th>Seller</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>
  <tbody>
    <?php foreach($data['products'] as $p) : ?>
      <tr>
        <td><?php echo $p->product_id; ?></td>
        <td><?php echo $p->product_name; ?></td>
        <td><?php echo $p->first_name . ' ' . $p->last_name; ?></td>
        <td>$<?php echo $p->price; ?></td>
        <td><?php echo $p->status ?? 'pending'; ?></td>
        <td>
          <a href="<?php echo URLROOT; ?>/admin/approveProduct/<?php echo $p->product_id; ?>" class="btn btn-sm btn-success">Approve</a>
          <a href="<?php echo URLROOT; ?>/admin/deactivateProduct/<?php echo $p->product_id; ?>" class="btn btn-sm btn-warning">Deactivate</a>
          <a href="<?php echo URLROOT; ?>/admin/deleteProduct/<?php echo $p->product_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete product?');">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php require APPROOT . '/Views/admin/layout_end.php'; ?>
