<?php

class  adminback
{
    //changed to public from private
    public $connection;
    function __construct()
    {
        $dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "";
        $dbname = "ecommerce";

        $this->connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

        if (!$this->connection) {
            die("Databse connection error!!!");
        }
    }

    public function display_package()
    {
        $query = "SELECT * FROM Package";
        $result = $this->connection->query($query);

        if ($result) {
            return $result;
        } else {
            return false; // Handle error accordingly
        }
    }

    public function create_order($cust_id, $order_date, $package_id, $dish_count, $total_amount, $staff_count, $service_address, $service_date)
    {
        $query = "INSERT INTO orders (Cust_id, order_date, type, package_id, dish_count, total_amount, staff_count, status, service_address, service_date) 
                  VALUES (?, ?, 'Package', ?, ?, ?, ?, 'Pending', ?, ?)";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("isiiisss", $cust_id, $order_date, $package_id, $dish_count, $total_amount, $staff_count, $service_address, $service_date);
        
        if ($stmt->execute()) {
            return true; // Order successfully created
        } else {
            return false; // Handle error accordingly
        }
    }


    //for cart
    public function display_cart_by_customer($customer_id)
{
    // Query to select cart records for a specific customer
    $query = "SELECT p.name,p.id,p.image,p.price FROM package as p join cart on p.id = cart.package_id WHERE customer_id = ?";
    $stmt = $this->connection->prepare($query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the query is successful, return the result set
    if ($result) {
        return $result;
    } else {
        // If the query fails, return false
        return false;
    }
}

    public function getItemsByIds($ids)
    {
        $query = "SELECT Name, CategoryID FROM items WHERE Id IN ($ids)";
        return mysqli_query($this->connection, $query);
    }


    public function getCategoryById($categoryId)
    {
        $query = "SELECT name FROM catagory WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('i', $categoryId);
        $stmt->execute();
        return $stmt->get_result();
    }


    public function getCategoriess()
    {
        $query = "SELECT * FROM catagory";
        return mysqli_query($this->connection, $query); // Return result set
    }

    public function display_packageByID($id)
    {
        $query = "SELECT * FROM Package WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            return $result->fetch_assoc();  // Return the package data as an associative array
        } else {
            return false; // Handle error accordingly
        }
    }

    public function getCartItemsByCustomerId($customerId) {
        $query = "SELECT c.id, c.package_id, p.name, p.price 
                  FROM cart c 
                  JOIN Package p ON c.package_id = p.id 
                  WHERE c.custpackage_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $cartItems = [];
        while ($row = $result->fetch_assoc()) {
            $cartItems[] = $row; // Add package details to the cart items
        }
    
        $stmt->close();
        return $cartItems;
    }
    public function getCartTotalByCustomerId($customerId) {
        $query = "SELECT SUM(p.price) AS total 
                  FROM cart c 
                  JOIN Package p ON c.package_id = p.id 
                  WHERE c.custpackage_id = ?";
        
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();
        
        return $total ? $total : 0; // Return total if not null, otherwise return 0
    }
    public function addToCart($customerId, $packageId) {
        // Check if the item is already in the cart
        $checkQuery = "SELECT * FROM cart WHERE customer_id = ? AND package_id = ?";
        $checkStmt = $this->connection->prepare($checkQuery);
        $checkStmt->bind_param("ii", $customerId, $packageId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
    
        if ($checkResult->num_rows > 0) {
            // Item already exists in the cart
            $checkStmt->close();
            return "Item already exists in the cart.";
        } else {
            // Insert the new item into the cart
            $insertQuery = "INSERT INTO cart (customer_id, package_id) VALUES (?, ?)";
            $insertStmt = $this->connection->prepare($insertQuery);
            $insertStmt->bind_param("ii", $customerId, $packageId);
            if ($insertStmt->execute()) {
                $insertStmt->close();
                return "Item added to cart successfully.";
            } else {
                $insertStmt->close();
                return "Failed to add item to cart.";
            }
        }
        $checkStmt->close();
    }
    
}    
