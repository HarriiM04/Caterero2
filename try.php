<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
    <script>
        function calculateStaffCount() {
            const dishCount = parseInt(document.getElementById("dish_count").value);
            let staffCount = 0;

            if (dishCount <= 500) {
                staffCount = 20; // 20 staff for 500 or fewer dishes
            } else {
                staffCount = Math.ceil(dishCount / 500) * 20; // 20 staff for every 500 dishes
            }

            document.getElementById("staff_count").value = staffCount;
        }
    </script>
</head>
<body>

<h1>Create Order</h1>
<form action="submit_order.php" method="POST">
    <label for="cust_id">Customer ID:</label>
    <input type="number" id="cust_id" name="cust_id" required><br><br>

    <label for="order_date">Order Date:</label>
    <input type="date" id="order_date" name="order_date" required><br><br>



    <label for="package_id">Package ID:</label>
    <input type="number" id="package_id" name="package_id"><br><br>

    <label for="package_id">Package Name:</label>
    <input type="number" id="package_id" name="package_id"><br><br>

    <label for="package_id">Package description:</label>
    <input type="number" id="package_id" name="package_id"><br><br>

    <label for="package_id">Package price:</label>
    <input type="number" id="package_id" name="package_id"><br><br>

    <label for="dish_count">Dish Count:</label>
    <input type="number" id="dish_count" name="dish_count" oninput="calculateStaffCount()" required><br><br>

    <label for="staff_count">Staff Count:</label>
    <input type="number" id="staff_count" name="staff_count" readonly><br><br>

    <label for="total_amount">Total Amount:</label>
    <input type="number" id="total_amount" name="total_amount" required><br><br>


    <label for="service_address">Service Address:</label>
    <input type="text" id="service_address" name="service_address" required><br><br>

    <label for="service_date">Service Date:</label>
    <input type="date" id="service_date" name="service_date" required><br><br>

    <input type="submit" value="Submit Order">
</form>

</body>
</html>
