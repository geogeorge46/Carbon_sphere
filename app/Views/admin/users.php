<?php require APPROOT . '/Views/admin/layout.php'; ?>

<h2 class="mt-4">Users</h2>
<table class="table table-striped">
  <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Created</th><th>Actions</th></tr></thead>
  <tbody>
    <?php foreach($data['users'] as $u) : ?>
      <tr>
        <td><?php echo $u->user_id; ?></td>
        <td><?php echo $u->first_name . ' ' . $u->last_name; ?></td>
        <td><?php echo $u->email; ?></td>
        <td><?php echo $u->role; ?></td>
        <td><?php echo $u->created_at; ?></td>
        <td>
          <a href="<?php echo URLROOT; ?>/admin/editUser/<?php echo $u->user_id; ?>" class="btn btn-sm btn-primary">Edit</a>
          <a href="<?php echo URLROOT; ?>/admin/deleteUser/<?php echo $u->user_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete user?');">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php require APPROOT . '/Views/admin/layout_end.php'; ?>
