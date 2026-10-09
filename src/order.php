<?php
    include "session.php";
    include "models/dish.php";
    include "require_login.php";

    $dish_data = get_all_dishes();
    $dishes = $dish_data['result'];
    $dishes_by_id = [];

    foreach ($dishes as $dish) {
        $dishes_by_id[(int) $dish['id']] = $dish;
    }

    $form_data = [
        'customer_name' => '',
        'food_item' => '',
        'quantity' => 0,
        'amount_paid' => 0
    ];

    $customerName = '';
    $foodItem = '';
    $price = 0;
    $quantity = 0;
    $amountPaid = 0;
    $subtotal = 0;
    $discount = 0;
    $total = 0;
    $change = 0;
    $errors = [];
    $submitted = isset($_POST['submit']);
    $selectedDishId = null;

    if ($submitted) {
        $form_data['customer_name'] = is_string($_POST['customer_name'] ?? null) ? $_POST['customer_name'] : '';
        $form_data['food_item'] = is_string($_POST['food_item'] ?? null) ? $_POST['food_item'] : '';
        $form_data['quantity'] = is_string($_POST['quantity'] ?? null) ? $_POST['quantity'] : '';
        $form_data['amount_paid'] = is_string($_POST['amount_paid'] ?? null) ? $_POST['amount_paid'] : '';

        $selectedDishId = filter_var($form_data['food_item'], FILTER_VALIDATE_INT);
        if ($selectedDishId === false || !isset($dishes_by_id[$selectedDishId])) {
            $errors[] = "Please select a valid food item.";
        }
        if (trim($form_data['customer_name']) === '') {
            $errors[] = "Customer name is required.";
        }
        $validatedQuantity = filter_var($form_data['quantity'], FILTER_VALIDATE_INT);
        if ($validatedQuantity === false || $validatedQuantity < 1) {
            $errors[] = "Quantity must be at least 1.";
        }
        if (!is_numeric($form_data['amount_paid']) || (float) $form_data['amount_paid'] < 0) {
            $errors[] = "Amount paid must be zero or more.";
        }

        if (empty($errors)) {
            $dish = $dishes_by_id[$selectedDishId];
            $customerName = strtoupper(trim($form_data['customer_name']));
            $foodItem = $dish['name'];
            $price = (float) $dish['price'];
            $quantity = (int) $form_data['quantity'];
            $amountPaid = (float) $form_data['amount_paid'];

            $subtotal = $price * $quantity;
            $discount = $subtotal >= 500 ? $subtotal * 0.10 : 0;
            $total = $subtotal - $discount;
            $change = $amountPaid - $total;
        }
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
                            value="<?= htmlspecialchars($form_data['customer_name'], ENT_QUOTES, 'UTF-8') ?>"
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

                            <?php foreach ($dishes as $dish) { ?>
                                <option
                                    value="<?= (int) $dish['id'] ?>"
                                    data-price="<?= htmlspecialchars((string) $dish['price'], ENT_QUOTES, 'UTF-8') ?>"
                                    <?= (string) $form_data['food_item'] === (string) $dish['id'] ? 'selected' : '' ?>
                                >
                                    <?= htmlspecialchars($dish['name'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php } ?>
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
                            value="<?= $price > 0 ? number_format($price, 2, '.', '') : '' ?>"
                            readonly
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
                            step="1"
                            value="<?= htmlspecialchars((string) $form_data['quantity'], ENT_QUOTES, 'UTF-8') ?>"
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
                            value="<?= htmlspecialchars((string) $form_data['amount_paid'], ENT_QUOTES, 'UTF-8') ?>"
                            required
                        >

                        <input
                            type="submit"
                            name="submit"
                            value="Calculate Order"
                        >

                    </form>

                    <script>
                        const foodItemSelect = document.getElementById('food_item');
                        const priceInput = document.getElementById('price');

                        function updateDishPrice() {
                            const selectedOption = foodItemSelect.options[foodItemSelect.selectedIndex];
                            priceInput.value = selectedOption.dataset.price || '';
                        }

                        foodItemSelect.addEventListener('change', updateDishPrice);
                        updateDishPrice();
                    </script>

                    <div id="order-summary">

                        <?php if (!empty($errors)) { ?>
                            <ul class="order-errors">
                                <?php foreach ($errors as $error) { ?>
                                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                                <?php } ?>
                            </ul>
                        <?php } elseif ($submitted) { ?>

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