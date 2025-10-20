<?php require APPROOT . '/Views/admin/layout.php'; ?>

<h2 class="mt-4">Edit User</h2>
<form method="post" action="<?php echo URLROOT; ?>/admin/editUser/<?php echo $data['user']->user_id; ?>">
  <div class="form-group">
    <label>Role</label>
    <select name="role" class="form-control">
      <option value="buyer" <?php echo ($data['user']->role=='buyer')? 'selected' : ''; ?>>Buyer</option>
      <option value="seller" <?php echo ($data['user']->role=='seller')? 'selected' : ''; ?>>Seller</option>
      <option value="admin" <?php echo ($data['user']->role=='admin')? 'selected' : ''; ?>>Admin</option>
    </select>
  </div>
  <button class="btn btn-primary">Save</button>
</form>

<?php require APPROOT . '/Views/admin/layout_end.php'; ?>
