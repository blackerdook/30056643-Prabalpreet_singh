<?php
session_start();
include "connection.php";

// Check if the user is logged in
$loggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'];
$showWelcome = !isset($_GET['link']) && !isset($_GET['delete']); 

// Initialize alert message for delete action
$alertMessage = '';

// Delete record if requested
if (isset($_GET['delete']) && $loggedIn) {
    $delID = $_GET['delete'];
    $delete = $connection->prepare("DELETE FROM scp WHERE id = ?");
    $delete->bind_param("i", $delID);
    
    if ($delete->execute()) {
        $alertMessage = "<div class='alert alert-warning mt-3'>SCP file deleted...</div>";
    } else {
        $alertMessage = "<div class='alert alert-danger mt-3'>Error deleting file: " . $delete->error . "</div>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SCP Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Dark Theme Styling */
        body {
            background-color: #0a0a0a;
            color: #d0d0d0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background-color: #101010;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
        }
        .container {
            flex: 1;
            max-width: 90%;
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        /* Top Section */
        .top-section {
            display: flex;
            flex: 1;
            gap: 20px;
        }
        .logo-image {
            flex: 1;
            background: url('image/LOGO.jpg') no-repeat center center;
            background-size: cover;
            border-radius: 8px;
            min-height: 250px;
        }
        .welcome-message {
            flex: 2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            padding: 20px;
            background-color: #1e1e1e;
            border-radius: 8px;
        }
        .welcome-message h1 {
            color: #8b2f2b;
        }
        .welcome-message p {
            color: #d0d0d0;
            font-size: 1.1em;
        }
        /* SCP Details Section */
        .scp-details {
            background-color: #1e1e1e;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .scp-details img {
            width: 100%;
            max-width: 500px;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        /* Bottom Section */
        .scrollable-cards-container {
            margin-top: 20px;
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding: 10px 0;
        }
        .card {
            min-width: 200px;
            max-width: 250px;
            background-color: #141414;
            color: #d0d0d0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            flex: 0 0 auto;
        }
        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .card-body {
            padding: 15px;
            text-align: center;
        }
        .card-title {
            color: #8b2f2b;
        }
        .btn-glow {
            background-color: #8b2f2b;
            color: white;
            font-weight: bold;
            border-radius: 30px;
            padding: 8px 20px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: none;
            outline: none;
            border: none;
        }
        .btn-glow:hover {
            background-color: #732724;
            box-shadow: 0 0 10px rgba(139, 47, 43, 0.7);
        }
        /* Update and Delete Button Styling */
        .btn-update, .btn-delete {
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 30px;
            display: inline-block;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.3s ease;
            border: none;
            outline: none;
        }
        /* Update button in green */
        .btn-update {
            background: linear-gradient(145deg, #28a745, #218838);
        }
        .btn-update:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.6);
        }
        /* Delete button in dark red */
        .btn-delete {
            background: linear-gradient(145deg, #8b2f2b, #732724);
        }
        .btn-delete:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 10px rgba(139, 47, 43, 0.7);
        }
        /* Scrollbar Styling */
        .scrollable-cards-container::-webkit-scrollbar {
            height: 8px;
        }
        .scrollable-cards-container::-webkit-scrollbar-track {
            background: #1a1a1a;
            border-radius: 10px;
        }
        .scrollable-cards-container::-webkit-scrollbar-thumb {
            background-color: #8b2f2b;
            border-radius: 10px;
            border: 2px solid #1a1a1a;
        }
        .scrollable-cards-container::-webkit-scrollbar-thumb:hover {
            background-color: #732724;
        }
        /* Responsive */
        @media (max-width: 768px) {
            .top-section {
                flex-direction: column;
            }
            .logo-image, .welcome-message {
                min-height: 200px;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">SCP Management</a>
        <div class="d-flex">
            <a href="index.php" class="btn btn-outline-light me-2">Home</a>
            <?php if ($loggedIn): ?>
                <button id="logout-btn" class="btn btn-outline-light me-2" onclick="logout()">Logout</button>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-light me-2">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">
    <?php if ($showWelcome): ?>
        <!-- Top Section: Logo Image and Welcome Message -->
        <div class="top-section">
            <div class="logo-image"></div>
            <div class="welcome-message">
                <h1>Welcome to the SCP Management System</h1>
                <p>Manage SCP records with Create, Read, Update, and Delete functionalities.</p>
                <?php if ($loggedIn): ?>
                    <a href="create.php" class="btn btn-glow mt-3">Create New SCP</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Display SCP Details if an SCP is selected -->
    <?php
    if (isset($_GET['link'])) {
        $model = $_GET['link'];
        $stmt = $connection->prepare("SELECT * FROM scp WHERE scp_id = ?");
        $stmt->bind_param("s", $model);
        
        if ($stmt->execute()) {
            $scpResult = $stmt->get_result();
            if ($scpResult->num_rows > 0) {
                $row = $scpResult->fetch_assoc();
                echo '<div class="scp-details">';
                echo '    <h2>' . htmlspecialchars($row['scp_id']) . '</h2>';
                echo '    <h4>Class: ' . htmlspecialchars($row['class']) . '</h4>';
                echo '    <img src="' . htmlspecialchars(!empty($row['image']) ? $row['image'] : 'image/placeholder.png') . '" alt="' . htmlspecialchars($row['scp_id']) . '" class="img-fluid mb-3">';
                echo '    <h5>Containment Procedures</h5>';
                echo '    <p>' . htmlspecialchars($row['containment']) . '</p>';
                echo '    <h5>Description</h5>';
                echo '    <p>' . htmlspecialchars($row['description']) . '</p>';
                if ($loggedIn) {
                    echo '<a href="update.php?update=' . htmlspecialchars($row['id']) . '" class="btn btn-update me-2">Update</a>';
                    echo '<a href="index.php?delete=' . htmlspecialchars($row['id']) . '" class="btn btn-delete" onclick="return confirm(\'Are you sure?\')">Delete</a>';
                }
                echo '</div>';
            } else {
                echo '<p class="text-center text-muted">No record found for this SCP.</p>';
            }
        }
    }
    ?>

    <!-- Bottom Section: Scrollable Cards and Alert Message -->
    <div class="scrollable-cards-container">
        <?php
        $AllRecords = $connection->prepare("SELECT * FROM scp");
        $AllRecords->execute();
        $result = $AllRecords->get_result();

        while ($row = $result->fetch_assoc()) {
            $imageSrc = !empty($row['image']) ? htmlspecialchars($row['image']) : 'image/placeholder.png';
            echo '<div class="card">';
            echo '    <img src="' . $imageSrc . '" alt="SCP Image">';
            echo '    <div class="card-body">';
            echo '        <h5 class="card-title">' . htmlspecialchars($row['scp_id']) . '</h5>';
            echo '        <a href="index.php?link=' . htmlspecialchars($row['scp_id']) . '" class="btn btn-primary btn-glow">Read More</a>';
            echo '    </div>';
            echo '</div>';
        }
        ?>
    </div>

    <!-- Display alert message after SCP deletion -->
    <?php if ($alertMessage): ?>
        <div class="mt-3">
            <?php echo $alertMessage; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Logout function
    function logout() {
        fetch('logout.php').then(response => response.text()).then(data => {
            if (data === "logged_out") {
                window.location.reload();
            }
        }).catch(error => console.error('Error logging out:', error));
    }
</script>
</body>
</html>
