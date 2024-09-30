<?php
session_start();
include("db_connect.php");
require("forgetMail.php");

if (isset($_POST['sendOtp'])) {
    $email = $_POST['email'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        sendMail($email); // Send OTP to the user's email
        $_SESSION['sesEmail'] = $email;
        echo "<script>alert('OTP has been sent to your email.');window.location.replace('verify_otp.php');</script>";
    } else {
        $error_message = 'Invalid email.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Password Reset</title>
    <link rel="icon" href="fevicon.png" type="image/png"> <!-- Favicon link -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
    body {
        background:  
                    url('login_background.jpg'); /* Add the background image */
        background-size: cover; /* Ensure the image covers the entire screen */
        background-position: center; /* Center the background image */
        background-repeat: no-repeat; /* Prevent the image from repeating */
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
        <h2>Request Password Reset</h2>
        <form action="#" method="POST">
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="sendOtp">Send OTP</button>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger mt-3">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
