<?php require APPROOT . '/Views/inc/header.php'; ?>
  <h1>Shopping Cart</h1>
  <?php if (!empty($data['items'])) : ?>
    <table class="table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Carbon Footprint</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($data['items'] as $item) : ?>
          <tr>
            <td><?php echo $item->productName; ?></td>
            <td>$<?php echo $item->productPrice; ?></td>
            <td><?php echo $item->quantity; ?></td>
            <td><?php echo $item->carbon_footprint * $item->quantity; ?> kg CO₂</td>
            <td>
              <form action="<?php echo URLROOT; ?>/cart/remove/<?php echo $item->cart_item_id; ?>" method="post">
                <input type="submit" value="Remove" class="btn btn-danger">
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <hr>
    <div class="row">
      <div class="col-md-6">
        <h3>Total: $<?php echo $data['total']; ?></h3>
        <h3>Total Carbon Footprint: <?php echo $data['carbon_total']; ?> kg CO₂</h3>
      </div>
      <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/orders/checkout" class="btn btn-success pull-right">Checkout</a>
      </div>
    </div>
  <?php else : ?>
    <p>Your cart is empty.</p>
  <?php endif; ?>
<?php require APPROOT . '/Views/inc/footer.php'; ?>
