<?php
session_start();

// Include your database connection
$serverName = 'localhost';
$userName = 'root';
$password = '';
$databaseName = 'election_voting';
$con = mysqli_connect($serverName, $userName, $password, $databaseName);

if (!$con) {
    echo "Sorry, the connection was not successful.";
    exit;
}

// Check if user ID is set in session
if (!isset($_SESSION['user_id'])) {
    echo "User is not logged in.";
    exit;
}

// Fetch user data from the userdata table
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM userdata WHERE id = ?";
$stmt = $con->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $con->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "User not found.";
    exit;
}

$userDetails = $result->fetch_assoc();
$userStatus = isset($userDetails['status']) ? $userDetails['status'] : 'Not Voted';

// Check if the user has already voted
if ($userStatus === 'Voted') {
    echo 'You have already voted.';
    exit;
}

// Update vote count
if (isset($_GET['party'])) {
    $party = $_GET['party'];
    
    // Update vote count in the votes table
    $updateVoteCountSql = "UPDATE votes SET vote_count = vote_count + 1 WHERE party = ?";
    $stmt = $con->prepare($updateVoteCountSql);
    
    if ($stmt === false) {
        die("Prepare failed: " . $con->error);
    }
    
    $stmt->bind_param("s", $party);
    $stmt->execute();
    
    // Update user status to 'Voted'
    $updateUserStatusSql = "UPDATE userdata SET status = 'Voted' WHERE id = ?";
    $stmt = $con->prepare($updateUserStatusSql);
    
    if ($stmt === false) {
        die("Prepare failed: " . $con->error);
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    echo 'Congratulations! You have successfully voted for ' . htmlspecialchars($party);
} else {
    echo 'Invalid request.';
}
?>
