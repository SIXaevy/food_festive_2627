<?php
    include "session.php";
    include "require_login.php";

    $form_data = [
        'customer_name' => '',
        'food_item' => '',
        'price' => 0,
        'quantity' => 0,
        'amount_paid' => 0
    ];

    $customerName = '';
    $foodItem = '';
    $subtotal = 0;
    $discount = 0;
    $total = 0;
    $change = 0;

    if (isset($_POST['submit'])) {

        $form_data['customer_name'] = $_POST['customer_name'] ?? '';
        $form_data['food_item'] = $_POST['food_item'] ?? '';
        $form_data['price'] = $_POST['price'] ?? 0;
        $form_data['quantity'] = $_POST['quantity'] ?? 0;
        $form_data['amount_paid'] = $_POST['amount_paid'] ?? 0;

        $customerName = strtoupper($form_data['customer_name']);
        $foodItem = ucwords($form_data['food_item']);

        $price = (float) $form_data['price'];
        $quantity = (int) $form_data['quantity'];
        $amountPaid = (float) $form_data['amount_paid'];

        $subtotal = $price * $quantity;

        if ($subtotal >= 500) {
            $discount = $subtotal * 0.10;
        } else {
            $discount = 0;
        }

        $total = $subtotal - $discount;

        $change = $amountPaid - $total;
    }
?>

<?php include "layouts/_header.php"; ?>

<?php include "layouts/_navigation.php"; ?>

<main class="account">
    <section id="order" class="container">

        <div id="account-container">

            <?php include "layouts/_account-navigation.php"; ?>

            <div id="account-preview">

                <div id="account-preview-heading">
                    <h2>
                        <i class="fa-solid fa-clipboard"></i>
                        Orders
                    </h2>
                </div>

                <div class="order-page">

                    <h1>Food Festive Order Calculator</h1>

                    <form method="POST" action="/order">

                        <label for="customer_name">
                            Customer Name:
                        </label>

                        <input
                            type="text"
                            id="customer_name"
                            name="customer_name"
                            required
                        >

                        <label for="food_item">
                            Food Item:
                        </label>

                        <select
                            id="food_item"
                            name="food_item"
                            required
                        >
                            <option value="">
                                -- Select Food Item --
                            </option>

                            <option value="lechon">
                                Lechon
                            </option>

                            <option value="pancit">
                                Pancit
                            </option>

                            <option value="barbecue">
                                Barbecue
                            </option>

                            <option value="lumpia">
                                Lumpia
                            </option>

                            <option value="halo-halo">
                                Halo-Halo
                            </option>
                        </select>

                        <label for="price">
                            Price per Serving:
                        </label>

                        <input
                            type="number"
                            id="price"
                            name="price"
                            step="0.01"
                            min="0"
                            required
                        >

                        <label for="quantity">
                            Quantity:
                        </label>

                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            min="1"
                            required
                        >

                        <label for="amount_paid">
                            Amount Paid:
                        </label>

                        <input
                            type="number"
                            id="amount_paid"
                            name="amount_paid"
                            step="0.01"
                            min="0"
                            required
                        >

                        <input
                            type="submit"
                            name="submit"
                            value="Calculate Order"
                        >

                    </form>

                    <div id="order-summary">

                        <?php if (isset($_POST['submit'])) { ?>

                            <h2>Order Summary</h2>

                            <ul class="order-list">

                                <li>
                                    Customer Name:
                                    <span>
                                        <?= htmlspecialchars($customerName) ?>
                                    </span>
                                </li>

                                <li>
                                    Food Item:
                                    <span>
                                        <?= htmlspecialchars($foodItem) ?>
                                    </span>
                                </li>

                                <li>
                                    Price per Serving:
                                    <span>
                                        ₱<?= number_format($price, 2) ?>
                                    </span>
                                </li>

                                <li>
                                    Quantity:
                                    <span>
                                        <?= $quantity ?>
                                    </span>
                                </li>

                                <li>
                                    Subtotal:
                                    <span>
                                        ₱<?= number_format($subtotal, 2) ?>
                                    </span>
                                </li>

                                <li>
                                    Discount:
                                    <span>
                                        ₱<?= number_format($discount, 2) ?>
                                    </span>
                                </li>

                                <li class="total">
                                    Total:
                                    <span>
                                        ₱<?= number_format($total, 2) ?>
                                    </span>
                                </li>

                                <li>
                                    Amount Paid:
                                    <span>
                                        ₱<?= number_format($amountPaid, 2) ?>
                                    </span>
                                </li>

                                <li class="change">
                                    Change:
                                    <span>
                                        ₱<?= number_format($change, 2) ?>
                                    </span>
                                </li>

                            </ul>

                        <?php } else { ?>

                            <p class="empty-summary">
                                Please fill out the form and click
                                "Calculate Order" to see the order summary.
                            </p>

                        <?php } ?>

                    </div>

                </div>

            </div>

        </div>

    </section>
</main>

<?php include "layouts/_footer.php"; ?>