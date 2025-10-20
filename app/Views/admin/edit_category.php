<?php require APPROOT . '/Views/admin/layout.php'; ?>

<h2 class="mt-4">Edit Category</h2>
<form method="post" action="<?php echo URLROOT; ?>/admin/editCategory/<?php echo $data['category']->category_id; ?>">
  <div class="form-group">
    <label>Category Name</label>
    <input name="category_name" class="form-control" value="<?php echo $data['category']->category_name; ?>">
  </div>
  <button class="btn btn-primary">Save</button>
</form>

<?php require APPROOT . '/Views/admin/layout_end.php'; ?>
