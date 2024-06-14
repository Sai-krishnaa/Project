<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection details
$serverName = 'localhost';
$userName = 'root';
$password = '';
$databaseName = 'election_voting';

// Connect to the database
$con = mysqli_connect($serverName, $userName, $password, $databaseName);

// Check if connection was successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form was submitted using POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if both fields are set
    if (isset($_POST['Epic']) && isset($_POST['Password'])) {
        // Sanitize user input to prevent SQL injection
        $epic = mysqli_real_escape_string($con, $_POST['Epic']);
        $password = $_POST['Password'];

        // Fetch user details from the database based on Epic No., Mobile No., or Email
        $query = "SELECT * FROM user where EmailAddress = '$epic'";
        

        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            // Verify the password
            if ($password==$user['Password']) {
                // Password is correct, redirect to dashboard after 5 seconds
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'dashboard.html';
                        }, 5000);
                      </script>";
            } else {
                echo "<script>alert('Invalid credentials. Please try again.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('User not found. Please sign up.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request method.'); window.history.back();</script>";
}
?>
