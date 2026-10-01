<?php
    // Create an array to store the values entered in the form.
    // Default values are assigned before the form is submitted.
    $form_data = [
        'customer_name' => '',
        'food_item' => '',
        'price' => 0,
        'quantity' => 0,
        'amount_paid' => 0
    ];

    // Check if the user clicked the "Calculate Order" submit button.
    if(isset($_POST['submit'])) {

        // Get the values submitted from the HTML form
        // and store them inside the form_data array.
        $form_data['customer_name'] = $_POST['customer_name'];
        $form_data['food_item'] = $_POST['food_item'];
        $form_data['price'] = $_POST['price'];
        $form_data['quantity'] = $_POST['quantity'];
        $form_data['amount_paid'] = $_POST['amount_paid'];

        // Convert the customer's name to uppercase letters.
        // Example: "Juan Dela Cruz" becomes "JUAN DELA CRUZ".
        $customerName = strtoupper($form_data['customer_name']);

        // Capitalize the first letter of each word in the food item.
        // Example: "grilled chicken" becomes "Grilled Chicken".
        $foodItem = ucwords($form_data['food_item']);

        // Calculate the subtotal by multiplying the price
        // per serving by the quantity ordered.
        $subtotal = $form_data['price'] * $form_data['quantity'];

        // Apply a 10% festival discount if the subtotal
        // is greater than or equal to 500.
        if ($subtotal >= 500)
            $discount = $subtotal * 0.10;
        else
            // No discount is given if the subtotal is below 500.
            $discount = 0;

        // Calculate the final total after subtracting the discount.
        $total = $subtotal - $discount;

        // Calculate the customer's change.
        $change = $form_data['amount_paid'] - $total;
    }
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>Food Festival Order Calculator</title>
            <link rel="stylesheet" href="assets/css/order.css">
        </head>
    <body>
        <h1>Food Festive Order Calculator</h1>
        <form method="POST">
            <label for="customer_name">Customer Name:</label>
            <input type="text" id="customer_name" name="customer_name" required>

            <label for="food_item">Food Item:</label>
            <select id="food_item" name="food_item" required>
                <option value="">-- Select Food Item --</option>
                <option value="lechon">Lechon</option>
                <option value="pancit">Pancit</option>
                <option value="barbecue">Barbecue</option>
                <option value="lumpia">Lumpia</option>
                <option value="halo-halo">Halo-Halo</option>
            </select>

            <label for="price">Price per Serving:</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>
            <br><br>

            <label for="amount_paid">Amount Paid:</label>
            <input type="number" id="amount_paid" name="amount_paid" step="0.01" required>
            <br><br>

            <input type="submit" name="submit" value="Calculate Order">
        </form>
        <div id="order-summary">
            <?php if(isset($_POST['submit'])) { ?>
                    <h2>Order Summary</h2>
                    <ul class="order-list">
                        <li>Customer Name: <span><?= $customerName ?></span></li>
                        <li>Food Item: <span><?= $foodItem ?></span></li>
                        <li>Price per Serving: <span>₱<?= number_format($form_data['price'], 2) ?></span></li>
                        <li>Quantity: <span><?= $form_data['quantity'] ?></span></li>
                        <li>Subtotal: <span>₱<?= number_format($subtotal, 2) ?></span></li>
                        <li>Discount: <span>₱<?= number_format($discount, 2) ?></span></li>
                        <li class="total">Total: <span>₱<?= number_format($total, 2) ?></span></li>
                        <li>Amount Paid: <span>₱<?= number_format($form_data['amount_paid'], 2) ?></span></li>
                        <li class="change">Change: <span>₱<?= number_format($change, 2) ?></span></li>
                    </ul>
            <?php } else { ?>
               <p class="empty-summary">Please fill out the form and click "Calculate Order" to see the order summary.</p>
            <?php } ?>
        </div>
    </body>
</html>