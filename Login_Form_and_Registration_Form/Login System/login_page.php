<?php
// Start a session so we can store messages or user data temporarily
session_start();

// Initialize form input and error message variables
$email = $password = "";
$email_err = $password_err = "";
$login_err = ""; // A general error message if login fails

// Check if a login error message was set by authentication.php
if (isset($_SESSION['login_error'])) {
    $login_err = $_SESSION['login_error']; // Retrieve the message
    unset($_SESSION['login_error']); // Remove it after displaying (flash message behavior)
}

// When the form is submitted using POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate Email field
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate Password field
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // If there are no input errors, forward the request to authentication.php
    if (empty($email_err) && empty($password_err)) {
        require_once __DIR__ . '/authentication.php';
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Intellipaat</title>

    <!-- Main stylesheet for consistent styling -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- Font Awesome icons (optional but useful for UI icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <!-- Header with company logo -->
    <header class="header">
        <div class="logo-container">
            <img src="../images/intellipaat_logo.png" alt="Intellipaat Logo" class="intellipaat-logo">
        </div>
    </header>

    <!-- Main login form container -->
    <div class="container">
        <div class="form-section">
            <h2>Login</h2>

            <!-- Show login error message if it exists -->
            <?php
            if (!empty($login_err)) {
                echo '<div class="error-msg">' . $login_err . '</div>';
            }
            ?>

            <!-- Login form starts -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="login-form">

                <!-- Email Input -->
                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>

                <!-- Password Input -->
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="register-button">Login</button>
            </form>

            <!-- Navigation to registration -->
            <p class="form-footer-link">
                Don't have an account? 
                <a href="../Registration System/registration.php">Sign Up here</a>.
            </p>
        </div>
    </div>
</body>
</html>
