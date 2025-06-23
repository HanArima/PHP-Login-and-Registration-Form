<?php
// Start the session
session_start();

// Check if the user is logged in, if not then redirect to login page
// login_page.php is in the same 'Login System/' directory as dashboard.php, so path is correct.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login_page.php");
    exit;
}

// Include config file to get database connection for fetching user details.
// dashboard.php is in 'Login System/'. config.php is in 'Registration System/'.
// So, go up one level (../) from 'Login System/' to the project root,
// then into 'Registration System/', then 'config.php'.
require_once __DIR__ . '/../Registration System/config.php';

$user_details = []; // Array to store fetched user details

// Fetch user details from the database
if (isset($_SESSION["id"])) {
    $sql = "SELECT first_name, last_name, email, country, phone FROM registered_users WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = $_SESSION["id"];

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $first_name, $last_name, $email, $country, $phone);
                if (mysqli_stmt_fetch($stmt)) {
                    $user_details = [
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $email,
                        'country' => $country,
                        'phone' => $phone
                    ];
                }
            }
        } else {
            error_log("Dashboard: Error fetching user details: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Dashboard: Error preparing user details statement: " . mysqli_error($link));
    }
    mysqli_close($link); // Close connection after fetching details
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Intellipaat</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Additional styles for the dashboard and profile section */
        .dashboard-container {
            max-width: 900px;
            margin: 50px auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .dashboard-container h2 {
            color: #333;
            margin-bottom: 25px;
            font-size: 2em;
        }
        .welcome-message {
            font-size: 1.5em;
            color: #57147d; /* Intellipaat purple */
            margin-bottom: 30px;
        }
        .profile-section {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 30px;
            margin-top: 30px;
            text-align: left;
        }
        .profile-section h3 {
            color: #57147d;
            margin-bottom: 20px;
            font-size: 1.6em;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .profile-detail {
            margin-bottom: 15px;
            font-size: 1.1em;
            color: #555;
        }
        .profile-detail strong {
            display: inline-block;
            width: 120px; /* Align labels */
            color: #333;
        }
        .dashboard-links {
            margin-top: 40px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .dashboard-links a {
            background-color: #57147d;
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .dashboard-links a:hover {
            background-color: #43105f;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="../images/intellipaat_logo.png" alt="Intellipaat Logo" class="intellipaat-logo">
        </div>
    </header>

    <div class="dashboard-container">
        <h2>Dashboard</h2>
        <p class="welcome-message">Welcome, <b><?php echo htmlspecialchars($_SESSION["first_name"]); ?></b> to Intellipaat!</p>

        <div class="profile-section">
            <h3>Your Profile Details</h3>
            <?php if (!empty($user_details)): ?>
                <div class="profile-detail">
                    <strong>First Name:</strong> <?php echo htmlspecialchars($user_details['first_name']); ?>
                </div>
                <div class="profile-detail">
                    <strong>Last Name:</strong> <?php echo htmlspecialchars($user_details['last_name']); ?>
                </div>
                <div class="profile-detail">
                    <strong>Email:</strong> <?php echo htmlspecialchars($user_details['email']); ?>
                </div>
                <div class="profile-detail">
                    <strong>Country:</strong> <?php echo htmlspecialchars($user_details['country']); ?>
                </div>
                <div class="profile-detail">
                    <strong>Phone:</strong> <?php echo htmlspecialchars($user_details['phone']); ?>
                </div>
            <?php else: ?>
                <p>No profile details found or an error occurred.</p>
            <?php endif; ?>
        </div>

        <div class="dashboard-links">
            <a href="reset_password.php">Reset Your Password</a>
            <a href="logout.php">Sign Out of Your Account</a>
        </div>
    </div>
</body>
</html>