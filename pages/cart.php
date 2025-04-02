<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Adoption Cart</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <?php include 'inc/navbar.inc.php'; ?>
    <main>
    <div class="container my-5">
        <h1>Your Adoption Cart</h1>
        
        <?php if (empty($_SESSION['cart'])): ?>
            <div class="alert alert-info">Your cart is empty</div>
        <?php else: ?>
            <div class="p-4 rounded-3 mb-4" style="background-color: rgba(13, 110, 253, 0.1);">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Pet</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach ($_SESSION['cart'] as $id => $item): 
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="listingImages/<?= htmlspecialchars($item['image']) ?>" class="cart-pet-img me-3" alt="<?= htmlspecialchars($item['name']) ?> photo">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </div>
                                </td>
                                <td>$<?= number_format($item['price'], 2) ?></td>
                                <td>
                                    <form action="backend/update_cart.php" method="post" class="d-inline">
                                        <input type="hidden" name="pet_id" value="<?= $id ?>">
                                        <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="form-control" style="width: 80px;" aria-label="quantity">
                                        <button type="submit" class="btn btn-sm btn-primary mt-1">Update</button>
                                    </form>
                                </td>
                                <td>$<?= number_format($subtotal, 2) ?></td>
                                <td>
                                    <form action="backend/remove_from_cart.php" method="post">
                                        <input type="hidden" name="pet_id" value="<?= $id ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Total</th>
                            <th colspan="2">$<?= number_format($total, 2) ?></th>
                            <!-- <th></th> -->
                        </tr>
                    </tfoot>
                </table>

                <div class="text-end">
                     <?php if (isset($_SESSION['id'])): ?>
                        <a href="/checkout" class="btn btn-success">Proceed to Checkout</a>
                    <?php else: ?>
                        <a href="/login?redirect=checkout" class="btn btn-success">Login to Checkout</a>
                        <?php endif; ?>
                    <a href="backend/empty_cart.php" class="btn btn-danger">Empty Cart</a>
                    </div>
            </div>
        <?php endif; ?>

        <a href="/listings" class="btn btn-primary">Continue Browsing</a>
    </div>
    </main>
    <?php include 'inc/footer.inc.php'; ?>
</body>
</html>