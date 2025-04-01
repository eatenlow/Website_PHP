<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    $_SESSION['login_redirect'] = 'checkout'; // Store intended destination
    header('Location: /login');
    exit;
}

/* Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    header('Location: /cart');
    exit;
} */

// Calculate total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
// $adoptionFee = 50.00; // Fixed adoption fee
$grandTotal = $total
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | PetAdopt</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <?php include 'inc/navbar.inc.php'; ?>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-8">
                <h1 class="mb-4">Checkout</h1>
                
                <!-- <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Your Adoption Summary</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Pet</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="listingImages/<?= htmlspecialchars($item['image']) ?>" class="checkout-pet-img me-3" alt="<?= htmlspecialchars($item['name']) ?>">
                                                <?= htmlspecialchars($item['name']) ?>
                                            </div>
                                        </td>
                                        <td>$<?= number_format($item['price'], 2) ?></td>
                                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                                        <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div> -->

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Adoption Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="backend/process_checkout.php">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstName" name="firstName" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" name="lastName" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= $_SESSION['email'] ?? '' ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="state" class="form-label">State</label>
                                    <input type="text" class="form-control" id="state" name="state" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="zip" class="form-label">ZIP Code</label>
                                    <input type="text" class="form-control" id="zip" name="zip" required>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Payment Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="cardName" class="form-label">Name on Card</label>
                                        <input type="text" class="form-control" id="cardName" name="cardName" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="cardNumber" class="form-label">Card Number</label>
                                        <input type="text" class="form-control" id="cardNumber" name="cardNumber" required>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="expDate" class="form-label">Expiration Date</label>
                                            <input type="text" class="form-control" id="expDate" name="expDate" placeholder="MM/YY" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="cvv" class="form-label">CVV</label>
                                            <input type="text" class="form-control" id="cvv" name="cvv" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100">Complete Adoption</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card summary-card sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-3">Pets Being Adopted</h6>
                        <ul class="list-group mb-3">
                            <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="listingImages/<?= htmlspecialchars($item['image']) ?>" class="checkout-pet-img me-2" alt="<?= htmlspecialchars($item['name']) ?>">
                                        <span><?= htmlspecialchars($item['name']) ?></span>
                                    </div>
                                    <span class="badge bg-primary rounded-pill"><?= htmlspecialchars($item['quantity']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>$<?= number_format($total, 2) ?></span>
                        </div>
                        <!--<div class="d-flex justify-content-between mb-2">
                            <span>Adoption Fee:</span>
                            <span>$<?= number_format($adoptionFee, 2) ?></span>
                        </div> -->
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total:</span>
                            <span>$<?= number_format($grandTotal, 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'inc/footer.inc.php'; ?>
</body>
</html>