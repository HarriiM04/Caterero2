<?php
session_start(); // Start the session


require 'db_connect.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    // Retrieve and sanitize user input
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $entered_captcha = $_POST['captcha_input'];

    // Check if CAPTCHA matches the session value
    if ($entered_captcha !== $_SESSION['captcha']) {
        $error_message = 'Invalid CAPTCHA! Please try again.';
    } else {
        // Proceed with login logic if CAPTCHA is correct
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        // Bind email parameter and execute query
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify the provided password against the hashed password stored in the database
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true); // Regenerate session ID for security

                // Store user data in session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];

                // Redirect to a generic dashboard
                header('Location: ../index.php');
                exit(); // Stop further script execution after redirection
            } else {
                $error_message = 'Invalid password! Please try again.';
            }
        } else {
            $error_message = 'User not found! Please check your email.';
        }

        $stmt->close(); // Close the prepared statement
    }

    $conn->close(); // Close the database connection
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
            background-image: url('login_background.jpg'); /* Add your background image */
            background-size: 100%; /* Reduce the size of the background image to 50% */
            background-repeat: no-repeat; /* Prevent the image from repeating */
            background-position: center; /* Center the background image */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        .container {
            background: rgba(255, 255, 255, 0.85); /* Slight transparency for background */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
            animation: fadeIn 1.5s ease;
        }

        .logo {
            width: 100px; /* Reduced logo size */
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
                width: 80px; /* Further reduced logo size for smaller screens */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="black-logo.png" alt="Logo" class="logo">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Password:</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>

            <!-- CAPTCHA Image -->
            <div class="form-group">
                <label for="captcha"><i class="fas fa-key"></i> CAPTCHA:</label>
                <img src="captcha.php" alt="CAPTCHA" class="mb-3">
                <input type="text" class="form-control" name="captcha_input" id="captcha" required placeholder="Enter the CAPTCHA">
            </div>

            <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger mt-3">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
        </form>

        <div class="mt-3">
            <a href="change_password.php">Change Password</a> | 
            <a href="forgetpassword.php">Forget Password</a> 
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
