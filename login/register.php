<?php
session_start();

require 'db_connect.php'; // Ensure this file connects to your database correctly
require 'registerotp.php'; // Include the file containing the sendMail() function

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $captcha = $_POST['captcha'];

    // Input validation for first name
    if (!preg_match("/^[A-Za-z\s]+$/", $firstname)) {
        $errors[] = "First name must contain only alphabets and spaces.";
    }

    // Input validation for last name
    if (!preg_match("/^[A-Za-z\s]+$/", $lastname)) {
        $errors[] = "Last name must contain only alphabets and spaces.";
    }

    // Input validation for contact
    if (!preg_match("/^[6789]\d{9}$/", $contact)) {
        $errors[] = "Please enter a correct contact number.";
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match!";
    }

    // CAPTCHA verification
    if (strcasecmp($captcha, $_SESSION['captcha']) !== 0) {
        $errors[] = "CAPTCHA verification failed!";
    }

    // Proceed if there are no validation errors
    if (empty($errors)) {
        // Check for duplicate email
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Email already exists! Please choose a different one.";
        } else {
            // Send OTP to the email
            sendMail($email);

            // Store the user data temporarily in session (excluding password)
            $_SESSION['temp_user_data'] = [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'contact' => $contact,
                'email' => $email,
                'hashed_password' => password_hash($password, PASSWORD_DEFAULT)
            ];

            // Redirect to OTP verification page
            header("Location: verify_otp_register.php");
            exit();
        }

        $stmt->close();
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<script>document.getElementById('error-message').innerText += '$error\\n';</script>";
        }
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #3498db, #8e44ad);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-control {
            border-radius: 25px;
            padding: 8px 12px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .btn-primary {
            border-radius: 25px;
            background: linear-gradient(120deg, #2980b9, #8e44ad);
            border: none;
            padding: 8px 12px;
        }

        .alert {
            border-radius: 25px;
            padding: 8px 12px;
            margin-top: 10px;
            display: none;
            font-size: 12px;
        }

        .alert-danger {
            background-color: #e74c3c;
            color: #fff;
        }

        .alert-success {
            background-color: #2ecc71;
            color: #fff;
        }
    </style>
    <script>
        function validateForm() {
            const firstname = document.getElementById('firstname').value;
            const lastname = document.getElementById('lastname').value;
            const contact = document.getElementById('contact').value;
            const captcha = document.getElementById('captcha').value;
            const nameRegex = /^[A-Za-z\s]+$/;
            const contactRegex = /^[6789]\d{9}$/;

            if (!nameRegex.test(firstname)) {
                showMessage('error', 'First name must contain only alphabets.');
                return false;
            }
            if (!nameRegex.test(lastname)) {
                showMessage('error', 'Last name must contain only alphabets.');
                return false;
            }
            if (!contactRegex.test(contact)) {
                showMessage('error', 'Please enter a valid contact number.');
                return false;
            }
            if (captcha.trim() === '') {
                showMessage('error', 'CAPTCHA is required.');
                return false;
            }
            return true;
        }

        function showMessage(type, message) {
            const messageDiv = document.getElementById(type + '-message');
            messageDiv.innerText = message;
            messageDiv.style.display = 'block';
        }

        <?php if (!empty($errors)) { ?>
            showMessage('error', '<?php echo implode('<br>', $errors); ?>');
        <?php } ?>

        <?php if (!empty($success)) { ?>
            showMessage('success', '<?php echo $success; ?>');
        <?php } ?>
    </script>
</head>

<body>
    <div class="container">
        <h2>Register</h2>
        <form action="register.php" method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="firstname">First Name:</label>
                <input type="text" class="form-control" name="firstname" id="firstname" required>
            </div>
            <div class="form-group">
                <label for="lastname">Last Name:</label>
                <input type="text" class="form-control" name="lastname" id="lastname" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact:</label>
                <input type="text" class="form-control" name="contact" id="contact" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
            </div>
            <div class="form-group">
                <label for="captcha">CAPTCHA:</label>
                <div>
                    <img src="captcha.php" alt="CAPTCHA Image" id="captcha-image">
                </div>
                <input type="text" class="form-control" name="captcha" id="captcha" required>
            </div>
            <button type="submit" name="register" class="btn btn-primary btn-block">Register</button>
        </form>
        <div id="error-message" class="alert alert-danger mt-3"></div>
        <div id="success-message" class="alert alert-success mt-3"></div>
        <div class="mt-3">
            <span>Already have an account? </span>
            <a href="login.php">Login here</a>
        </div>
    </div>
</body>

</html>
