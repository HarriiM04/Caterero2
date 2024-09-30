<?php
session_start();
include 'adminback.php';
//we will retrive it from session
$_SESSION['customer_id'] = 1;
$admin = new adminback();
$packages = $admin->display_package();


$cust_id = $_SESSION['customer_id'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve values from the form
    $order_date = $_POST['order_date'];
    $package_id = $_POST['package_id'];
    $dish_count = $_POST['dish_count'];
    $total_amount = $_POST['total_amount'];
    $staff_count = $_POST['staff_count'];
    $service_address = $_POST['service_address'];
    $service_date = $_POST['service_date'];

    // Create order
    if ($admin->create_order($cust_id, $order_date, $package_id, $dish_count, $total_amount, $staff_count, $service_address, $service_date)) {
        echo "Order created successfully!";
        // Redirect or provide further instructions
    } else {
        echo "Error creating order!";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Home</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php include 'head_style.php' ?>
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

<body class="animsition">

	<?php include 'head.php' ?>




	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="index.html" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				Shoping Cart
			</span>
		</div>
	</div>
		


	<h1>Create Order</h1>
<form action="" method="POST">
    <label for="cust_id">Customer ID:</label>
    <input type="number" id="cust_id" name="cust_id" value="<?php echo $cust_id; ?>" required readonly><br><br>

    <label for="order_date">Order Date:</label>
    <input type="date" id="order_date" name="order_date" required><br><br>

    <label for="package_id">Package ID:</label>
    <input type="number" id="package_id" name="package_id" required><br><br>

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

	
		

	<?php include 'footer.php' ?>
	<?php include 'footer_script.php' ?>

</body>

</html>