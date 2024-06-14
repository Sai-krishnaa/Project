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
    // Check if all required fields are set
    if (isset($_POST['UN1']) && isset($_POST['UM1']) && isset($_POST['UCP1'])) {
        // Sanitize user inputs to prevent SQL injection
        $name = mysqli_real_escape_string($con, $_POST['UN1']);
        $mobile = mysqli_real_escape_string($con, $_POST['UM1']);
        $email = isset($_POST['UE1']) ? mysqli_real_escape_string($con, $_POST['UE1']) : null;
        $password = $_POST['UCP1'];

        // Hash the password before saving it to the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user details into the database
        $insQur1 = "INSERT INTO `user`(`Id`, `Name`, `Mobile Number`, `EmailAddress`, `Password`) VALUES ('$id','$name','$mobile','$email','$password')";

        // Execute the query
        $exc1 = mysqli_query($con, $insQur1);

        // Check if the query was successful
        if ($exc1) {
            // Fetch the inserted data to display on the webpage
            $last_id = mysqli_insert_id($con);
            $result = mysqli_query($con, "SELECT * FROM user WHERE id = $last_id");
            $user = mysqli_fetch_assoc($result);

            // Display the data on the webpage
            echo "<h3>Registration done successfully. Here are the details:</h3>";
            echo "Name: " . $user['name'] . "<br>";
            echo "Mobile: " . $user['mobile'] . "<br>";
            echo "Email: " . $user['email'] . "<br>";

            // Redirect to the login page after a few seconds
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'loginSimple.html';
                }, 5000); // Redirect after 5 seconds
            </script>";
        } else {
            echo "<script>alert('Registration not done successfully: " . mysqli_error($con) . "');</script>";
        }
    } else {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request method.'); window.history.back();</script>";
}
?>