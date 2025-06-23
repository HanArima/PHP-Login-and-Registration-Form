<?php
require_once __DIR__ . '/config.php'; // Connect to the database

// Initialize variables for form data and errors
$first_name = $last_name = $email = $password = $country = $phone = "";
$first_name_err = $last_name_err = $email_err = $password_err = $terms_err = $email_exists_err = "";
$registration_success = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate First Name input
    if (empty(trim($_POST["first_name"]))) {
        $first_name_err = "Please enter your first name.";
    } else {
        $first_name = trim($_POST["first_name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $first_name)) {
            $first_name_err = "Only letters and white space allowed.";
        }
    }

    // Validate Last Name input
    if (empty(trim($_POST["last_name"]))) {
        $last_name_err = "Please enter your last name.";
    } else {
        $last_name = trim($_POST["last_name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $last_name)) {
            $last_name_err = "Only letters and white space allowed.";
        }
    }

    // Validate Email format and check if it's already registered
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email address.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address.";
    } else {
        $email = trim($_POST["email"]);

        // Check if the email already exists in the database
        $sql = "SELECT id FROM registered_users WHERE email = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_exists_err = "This email is already registered.";
                }
            } else {
                error_log("Error checking email existence: " . mysqli_stmt_error($stmt));
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        } else {
            error_log("Error preparing email check statement: " . mysqli_error($link));
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Validate Password input
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check if the user accepted the terms
    if (!isset($_POST["terms"])) {
        $terms_err = "You must agree to the Terms of Use & Privacy Policy.";
    }

    // Optional fields: country and phone
    $country = $_POST["country"] ?? '';
    $phone = trim($_POST["phone"] ?? '');

    // Insert data into database only if there are no errors
    if (empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($password_err) && empty($terms_err) && empty($email_exists_err)) {

        // Insert user into database
        $sql = "INSERT INTO registered_users (first_name, last_name, email, password, country, phone) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssss", $param_first_name, $param_last_name, $param_email, $param_password, $param_country, $param_phone);

            // Assign values to parameters
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_email = $email;

            // Secure password before storing it in the database
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            $param_country = $country;
            $param_phone = $phone;

            if (mysqli_stmt_execute($stmt)) {
                $registration_success = "Registration successful! You can now log in.";

                // Clear input values after successful registration
                $first_name = $last_name = $email = $password = $country = $phone = "";
            } else {
                error_log("MySQLi Insert Error: " . mysqli_stmt_error($stmt));
                echo "Error during registration. Please try again.";
            }
            mysqli_stmt_close($stmt);
        } else {
            error_log("MySQLi Prepare Error: " . mysqli_error($link));
            echo "Error preparing registration statement. Please try again.";
        }
    }

    // Close the database connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Intellipaat</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="../images/intellipaat_logo.png" alt="Intellipaat Logo" class="intellipaat-logo">
        </div>
    </header>

    <div class="container">
        <div class="form-section">
            <h2>Sign Up</h2>

            <?php
            // Display success message if registration was successful
            if (!empty($registration_success)) {
                echo '<div class="success-msg">' . $registration_success . '</div>';
            }
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="registration-form">
                <div class="form-row">
                    <div class="form-group <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                        <span class="help-block"><?php echo $first_name_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                        <span class="help-block"><?php echo $last_name_err; ?></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group <?php echo (!empty($email_err) || !empty($email_exists_err)) ? 'has-error' : ''; ?>">
                        <label for="email">Email address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        <span class="help-block"><?php echo $email_err; ?><?php echo $email_exists_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label for="password">Password <span class="required">*</span></label>
                        <input type="password" id="password" name="password" required>
                        <span class="help-block"><?php echo $password_err; ?></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="country">Country</label>
                        <select id="country" name="country">
                            <option value="">Select a country...</option>
                            <option value="India" <?php echo ($country == 'India') ? 'selected' : ''; ?>>India</option>
                            <option value="USA" <?php echo ($country == 'USA') ? 'selected' : ''; ?>>USA</option>
                            <option value="UK" <?php echo ($country == 'UK') ? 'selected' : ''; ?>>United Kingdom</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                    </div>
                </div>

                <div class="form-group checkbox-group <?php echo (!empty($terms_err)) ? 'has-error' : ''; ?>">
                    <input type="checkbox" id="terms" name="terms" <?php echo (isset($_POST['terms'])) ? 'checked' : ''; ?> required>
                    <label for="terms">By providing your contact details, you agree to our <a href="#">Terms of Use</a> & <a href="#">Privacy Policy</a></label>
                    <span class="help-block"><?php echo $terms_err; ?></span>
                </div>

                <button type="submit" class="register-button">Register</button>
            </form>
            <p class="form-footer-link">Already have an account? <a href="../Login System/login_page.php">Login here</a></p>
        </div>
    </div>
</body>
</html>