<?php
// Make sure we have the cart data
$cartItems = $data['cart_items'] ?? [];
$cartTotal = $data['cart_total'] ?? 0;
?>

<div class="container mt-5">
    <h1 class="mb-4">Your Shopping Cart</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']); 
            ?>
        </div>
    <?php endif; ?>

    <?php if (empty($cartItems)): ?>
        <div class="alert alert-info">
            Your cart is empty. <a href="index.php?controller=product&action=index">Continue shopping</a>
        </div>
    <?php else: ?>

        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if (!empty($item->image_path)): ?>
                                            <img src="<?php echo $item->image_path; ?>" alt="<?php echo htmlspecialchars($item->product_name); ?>" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                        <?php endif; ?>
                                        <span><?php echo htmlspecialchars($item->product_name); ?></span>
                                    </div>
                                </td>
                                <td><?php echo number_format($item->price, 0, ',', '.'); ?> VND</td>
                                <td>
                                    <form method="post" action="index.php?controller=cart&action=update" class="d-flex align-items-center">
                                        <input type="hidden" name="cart_id" value="<?php echo $item->cart_id; ?>">
                                        <input type="number" name="quantity" value="<?php echo $item->quantity; ?>" min="1" max="99" class="form-control" style="width: 70px;">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary ms-2">Update</button>
                                    </form>
                                </td>
                                <td><?php echo number_format($item->price * $item->quantity, 0, ',', '.'); ?> VND</td>
                                <td>
                                    <form method="post" action="index.php?controller=cart&action=remove">
                                        <input type="hidden" name="cart_id" value="<?php echo $item->cart_id; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total:</td>
                            <td colspan="2" class="fw-bold"><?php echo number_format($cartTotal, 0, ',', '.'); ?> VND</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="index.php?controller=product&action=index" class="btn btn-secondary">Continue Shopping</a>
                <div>
                    <form method="post" action="index.php?controller=cart&action=clear" class="d-inline">
                        <button type="submit" class="btn btn-warning">Clear Cart</button>
                    </form>
                    <a href="index.php?controller=order&action=checkout" class="btn btn-primary ms-2">Checkout</a>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>