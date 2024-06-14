<?php
session_start();
// Include your database connection
$serverName = 'localhost';
$userName = 'root';
$password = '';
$databaseName = 'election_voting';
$con = mysqli_connect($serverName, $userName, $password, $databaseName);
if ($con) {
    echo "connection done successfully";
} else {
    echo "Sorry the connection not done!!";
}

// Database connection
$serverName = 'localhost';
$userName = 'root';
$password = '';
$databaseName = 'election_voting';
$con = new mysqli($serverName, $userName, $password, $databaseName);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $voterid = $_POST['voterid']; // corrected variable name

    // Insert user data into userdata table
    $sql = "INSERT INTO `userdata`(`Name`, `Mobile`, `State`, `City`, `Voter Id`, `Status`) 
    VALUES (?, ?, ?, ?, ?, 'not voted')";
    $stmt = $con->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $con->error);
    }
    $stmt->bind_param("sssss", $name, $mobile, $state, $city, $voterid);
    $stmt->execute();

    // Get the last inserted user ID
    $user_id = $stmt->insert_id;

    // Store user ID in session
    $_SESSION['user_id'] = $user_id;

    // Redirect to voting page
    header("Location: voting.php");
    exit;
} else {
    echo "Invalid request.";
}
?>
