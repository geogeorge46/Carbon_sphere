<?php require APPROOT . '/Views/admin/layout.php'; ?>

<h2 class="mt-4">Categories <a href="<?php echo URLROOT; ?>/admin/addCategory" class="btn btn-sm btn-primary">Add</a></h2>
<table class="table table-striped">
  <thead><tr><th>ID</th><th>Name</th><th>Actions</th></tr></thead>
  <tbody>
    <?php foreach($data['categories'] as $c) : ?>
      <tr>
        <td><?php echo $c->category_id; ?></td>
        <td><?php echo $c->category_name; ?></td>
        <td>
          <a href="<?php echo URLROOT; ?>/admin/editCategory/<?php echo $c->category_id; ?>" class="btn btn-sm btn-primary">Edit</a>
          <a href="<?php echo URLROOT; ?>/admin/deleteCategory/<?php echo $c->category_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete category?');">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php require APPROOT . '/Views/admin/layout_end.php'; ?>
