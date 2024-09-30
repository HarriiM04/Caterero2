<?php
session_start();
require 'db_connect.php'; // Ensure this file connects to your database correctly

if (!isset($_SESSION['temp_user_data'])) {
    // Redirect to the registration page if no data is found
    header("Location: register.php");
    exit();
}

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verifyOtp'])) {
    $otp = $_POST['otp'];

    // Check if the entered OTP matches the one stored in the session
    if ($otp == $_SESSION['sesFogetOtp']) {
        // OTP is correct, proceed with user registration
        $user_data = $_SESSION['temp_user_data'];

        // Insert user data into the database
        $sql = "INSERT INTO users (firstname, lastname, email, contact, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'sssss',
            $user_data['firstname'],
            $user_data['lastname'],
            $user_data['email'],
            $user_data['contact'],
            $user_data['hashed_password'] // Store hashed password
        );

        if ($stmt->execute()) {
            // Get the newly inserted user's ID
            $user_id = $stmt->insert_id;

            // Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $user_data['email'];

            // Clear temporary data and OTP session
            unset($_SESSION['temp_user_data']);
            unset($_SESSION['sesFogetOtp']);

            // Redirect to client dashboard
            echo "<script>alert('OTP verified successfully. Registration successful!');window.location.replace('../index.php');</script>";
        } else {
            $errors[] = "Registration failed! Please try again.";
        }

        $stmt->close();
    } else {
        $errors[] = "Invalid OTP. Please try again.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maharaja Catering</title>
    <link rel="icon" href="fevicon.png" type="image/png"> <!-- Favicon link -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #2980b9, #8e44ad);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
            animation: fadeIn 1.5s ease;
        }
        .logo {
            width: 120px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-control {
            border-radius: 30px;
            padding: 10px 15px;
            font-size: 16px;
        }
        .btn-primary {
            border-radius: 30px;
            background: linear-gradient(120deg, #2980b9, #8e44ad);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(120deg, #8e44ad, #2980b9);
        }
        .alert {
            border-radius: 30px;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @media (max-width: 576px) {
            .container {
                padding: 20px;
            }
            .logo {
                width: 100px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="black-logo.png" alt="Logo" class="logo">
        <h2>Verify OTP</h2>
        <form action="verify_otp_register.php" method="POST">
            <div class="form-group">
                <label for="otp"><i class="fas fa-key"></i> OTP:</label>
                <input type="text" class="form-control" id="otp" name="otp" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="verifyOtp">Verify OTP</button>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger mt-3">
                    <?php echo implode('<br>', $errors); ?>
                </div>
            <?php endif; ?>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
