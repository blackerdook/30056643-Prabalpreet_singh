<?php
session_start();
include "connection.php";

$alertMessage = "";

if (isset($_GET['update'])) {
    $id = $_GET['update'];
    $recordID = $connection->prepare("SELECT * FROM scp WHERE id = ?");
    $recordID->bind_param("i", $id);
    $recordID->execute();
    $temp = $recordID->get_result();
    $row = $temp->fetch_assoc();
}

if (isset($_POST['update'])) {
    $update = $connection->prepare("UPDATE scp SET scp_id = ?, class = ?, image = ?, containment = ?, description = ? WHERE id = ?");
    $update->bind_param("sssssi", $_POST['scp_id'], $_POST['class'], $_POST['image'], $_POST['containment'], $_POST['description'], $_POST['id']);
    if ($update->execute()) {
        $alertMessage = "<div class='alert alert-success'>Record successfully updated</div>";
    } else {
        $alertMessage = "<div class='alert alert-danger'>Error: {$update->error}</div>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update SCP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Dark Theme Styling */
        body {
            background-color: #0a0a0a;
            color: #d0d0d0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding-top: 70px; 
        }
        .navbar {
            background-color: #101010;
            padding: 15px;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .form-container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }
        .header-title {
            color: #8b2f2b;
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control {
            background-color: #2b2d2f;
            color: #e0e0e0;
            border: none;
            border-radius: 5px;
        }
        .form-control::placeholder {
            color: #6c757d;
        }
        .btn-submit {
            background-color: #8b2f2b;
            color: white;
            font-weight: bold;
            border-radius: 30px;
            width: 100%;
            padding: 10px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            border: none;
            margin-top: 10px;
        }
        .btn-submit:hover {
            background-color: #732724;
            box-shadow: 0 0 10px rgba(139, 47, 43, 0.7);
        }
    </style>
</head>
<body>
    <!-- Fixed Navbar with Home Button -->
    <nav class="navbar">
        <div class="container-fluid d-flex justify-content-end">
            <a href="index.php" class="btn btn-outline-light">Home</a>
        </div>
    </nav>

    <!-- Update Form -->
    <div class="form-container">
        <h1 class="header-title">Update SCP</h1>
        <?php if ($alertMessage): echo $alertMessage; endif; ?>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
            <div class="mb-3">
                <label for="scp_id" class="form-label">SCP ID</label>
                <input type="text" id="scp_id" name="scp_id" class="form-control" placeholder="e.g., SCP-173" required value="<?php echo htmlspecialchars($row['scp_id']); ?>">
            </div>
            <div class="mb-3">
                <label for="class" class="form-label">Class</label>
                <input type="text" id="class" name="class" class="form-control" placeholder="e.g., Euclid" value="<?php echo htmlspecialchars($row['class']); ?>">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="text" id="image" name="image" class="form-control" placeholder="image/nameofimage" value="<?php echo htmlspecialchars($row['image']); ?>">
            </div>
            <div class="mb-3">
                <label for="containment" class="form-label">Containment</label>
                <textarea id="containment" name="containment" class="form-control" rows="3" placeholder="Containment procedures for the SCP..."><?php echo htmlspecialchars($row['containment']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4" placeholder="Description of the SCP..."><?php echo htmlspecialchars($row['description']); ?></textarea>
            </div>
            <button type="submit" name="update" class="btn btn-submit">Update</button>
        </form>
    </div>
</body>
</html>
