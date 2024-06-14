<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-color: #f5f5f5;
        }

        .container-fluid {
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 5px;
            margin-top: 20px;
        }

        .border-right {
            border-right: 1px solid #dddddd;
        }

        .my-3 {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row bg-success text-white">
            <div class="col-10">
                <h1 class="ml-3 my-3">Online Voting System</h1>
            </div>
            <div class="col-2 text-right">
                <a href="logout.php" class="btn btn-light my-3">Logout</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 border-right">
                <div class="text-center my-3">
                    <!-- <img src="profile.jpg" alt="Profile Picture" class="img-fluid rounded-circle" style="width: 150px; height: 150px;"> -->
                </div>
                <div class="p-3">
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

                    // Display user details
                    echo "<p><strong>Name:</strong> " . htmlspecialchars($userDetails['Name']) . "</p>";
                    echo "<p><strong>Mobile:</strong> " . htmlspecialchars($userDetails['Mobile']) . "</p>";
                    echo "<p><strong>State:</strong> " . htmlspecialchars($userDetails['State']) . "</p>";
                    echo "<p><strong>City:</strong> " . htmlspecialchars($userDetails['City']) . "</p>";
                    echo "<p><strong>Aadhaar:</strong> " . htmlspecialchars($userDetails['Voter Id']) . "</p>";
                    echo "<p><strong>Status:</strong> <span id='userStatus' class='" . ($userStatus === 'Voted' ? 'text-success' : 'text-danger') . "'>" . htmlspecialchars($userStatus) . "</span></p>";                ?>
                </div>
            </div>

            <div class="col-md-9">
                <div class="my-3">
                    <h3>1. Bhartiya Janata Party (BJP)</h3>
                    <img src="/College project/Image/bjp.webp" alt="BJP Logo" class="img-fluid" style="width: 50px;">
                    <button onclick="vote('BJP')" class="btn btn-success float-right" <?php echo $userStatus === 'Voted' ? 'disabled' : ''; ?>>Vote</button>
                </div>
                <div class="my-3">
                    <h3>2. Congress</h3>
                    <img src="/College project/Image/bjp.webp" alt="Congress Logo" class="img-fluid" style="width: 50px;">
                    <button onclick="vote('Congress')" class="btn btn-success float-right" <?php echo $userStatus === 'Voted' ? 'disabled' : ''; ?>>Vote</button>
                </div>
                <div class="my-3">
                    <h3>3. Janata Dal (United) JD(U)</h3>
                    <img src="/College project/Image/bjp.webp" alt="JD(U) Logo" class="img-fluid" style="width: 50px;">
                    <button onclick="vote('JDU')" class="btn btn-success float-right" <?php echo $userStatus === 'Voted' ? 'disabled' : ''; ?>>Vote</button>
                </div>
                <div class="my-3">
                    <h3>4. Aam Aadmi Party (AAP)</h3>
                    <img src="/College project/Image/bjp.webp" alt="AAP Logo" class="img-fluid" style="width: 50px;">
                    <button onclick="vote('AAP')" class="btn btn-success float-right" <?php echo $userStatus === 'Voted' ? 'disabled' : ''; ?>>Vote</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function vote(party) {
            fetch(`vote.php?party=${party}`)
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    document.querySelectorAll('.btn-success').forEach(button => button.disabled = true);
                    document.getElementById('userStatus').textContent = 'Voted';
                    document.getElementById('userStatus').classList.remove('text-danger');
                    document.getElementById('userStatus').classList.add('text-success');
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
