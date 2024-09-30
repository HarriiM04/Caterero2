<?php 
session_start();
include("db_connect.php");

if (isset($_POST['verify'])) {
    $email = $_SESSION['sesEmail'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  // Use password_hash instead of md5
    $sql = "UPDATE users SET password='$password' WHERE email='$email'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Password Update Successful!');window.location.replace('./Login.php');</script>";
        setcookie('passsave', '', time() - 3600);  // Clear cookies
        setcookie('emailsave', '', time() - 3600);
    } else {
        echo "<script>alert('Password Update Failed!');window.location.replace('./forgetPassword.php');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Password</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
    body {
        background: 
                    url('login_background.jpg'); /* Add your background image */
        background-size: cover; /* Make sure the background image covers the full screen */
        background-position: center; /* Center the background image */
        background-repeat: no-repeat; /* Do not repeat the background image */
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        font-family: 'Arial', sans-serif;
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
        <h2>New Password</h2>
        <form action="#" method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <div id="passwordError" class="error"></div>
            </div>
            <div class="form-group">
                <label for="txtConfirmPassword"><i class="fas fa-lock"></i> Confirm Password:</label>
                <input type="password" class="form-control" id="txtConfirmPassword" name="confirmPassword" required>
                <div id="confirmPasswordError" class="error"></div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="verify">Verify</button>
        </form>
    </div>

    <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("txtConfirmPassword").value;
            var passwordError = document.getElementById('passwordError');
            var confirmPasswordError = document.getElementById('confirmPasswordError');
            var isValid = true;

            var passwordCheck = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{5,12}$/;

            if (password === "") {
                passwordError.innerHTML = "Please Enter Password";
                isValid = false;
            } else if (!passwordCheck.test(password)) {
                passwordError.innerHTML = "Password must be 5-12 characters, contain at least one number and one special character.";
                isValid = false;
            } else {
                passwordError.innerHTML = "";
            }

            if (confirmPassword === "") {
                confirmPasswordError.innerHTML = "Please Enter Confirm Password";
                isValid = false;
            } else if (confirmPassword !== password) {
                confirmPasswordError.innerHTML = "Confirm Password does not match with Password";
                isValid = false;
            } else {
                confirmPasswordError.innerHTML = "";
            }

            return isValid;
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
