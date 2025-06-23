<?php
// This file is included by login_page.php and should not be accessed directly.
// Ensure a session is already started by the calling script (e.g., login_page.php)
if (session_status() == PHP_SESSION_NONE) { // Checks if a session is not active
    session_start();
}

// Include config file.
// authentication.php is in 'Login System/'.
// config.php is in 'Registration System/'.
// So, go up one level (../) from 'Login System/' to the project root,
// then into 'Registration System/', then 'config.php'.
require_once __DIR__ . '/../Registration System/config.php';

// Check if form variables are set (basic check, already done in login_page.php)
if (isset($_POST["email"]) && isset($_POST["password"])) {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $login_err = ""; // Initialize error message for authentication

    // Prepare a select statement to retrieve user by email
    $sql = "SELECT id, first_name, email, password FROM registered_users WHERE email = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "s", $param_email);

        // Set parameters
        $param_email = $email;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if email exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $id, $first_name_db, $email_db, $hashed_password);

                if (mysqli_stmt_fetch($stmt)) {
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, start a new session
                        session_regenerate_id(true); // Regenerate session ID for security

                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["email"] = $email_db;
                        $_SESSION["first_name"] = $first_name_db;

                        // Redirect user to dashboard page.
                        // dashboard.php is in the same 'Login System/' directory.
                        header("location: dashboard.php");
                        exit();
                    } else {
                        // Password is not valid
                        $login_err = "Invalid email or password.";
                    }
                }
            } else {
                // Email doesn't exist
                $login_err = "Invalid email or password.";
            }
        } else {
            // Error executing statement
            $login_err = "Oops! Something went wrong. Please try again later.";
            error_log("Authentication Error: mysqli_stmt_execute failed: " . mysqli_stmt_error($stmt));
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        // Error preparing statement
        $login_err = "Oops! Something went wrong. Please try again later.";
        error_log("Authentication Error: mysqli_prepare failed: " . mysqli_error($link));
    }

    // If login failed, store error message in session and redirect back to login page.
    // login_page.php is in the same 'Login System/' directory.
    $_SESSION['login_error'] = $login_err;
    header("location: login_page.php");
    exit();

} else {
    // If accessed directly without POST data (e.g., typing URL)
    // login_page.php is in the same 'Login System/' directory.
    header("location: login_page.php");
    exit();
}
// Close database connection
mysqli_close($link);
?>