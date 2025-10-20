<?php require APPROOT . '/Views/admin/layout.php'; ?>

<h2 class="mt-4">Add Category</h2>
<form method="post" action="<?php echo URLROOT; ?>/admin/addCategory">
  <div class="form-group">
    <label>Category Name</label>
    <input name="category_name" class="form-control">
  </div>
  <button class="btn btn-primary">Add</button>
</form>

<?php require APPROOT . '/Views/admin/layout_end.php'; ?>
