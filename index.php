<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Check if the user is an admin, if so then show the Users button
$isAdmin = ($_SESSION["user_type"] === 1);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FileSys HOME</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div>
        <!-- Nav Bar -->
        <nav class="navbar navbar-expand-lg navbar-primary" style="background-color: #15bae8;">
            <div class="container-fluid">
                <a class="navbar-brand text-white" href="#">
                    <img class="d-inline-block align-top" style="display: block; margin-left: auto; margin-right: 10%; background-color: white;" src="assets/final_logo.png" alt="" width="30" height="30">FUSy</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="main.php">UPLOAD</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="create.php">CREATE</a>
                        </li>
                        <li class="nav-item">
                            <?php if ($isAdmin) : ?>
                                <a class="nav-link" href="users.php">USERS</a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- -->
        <div class="wrapper">
            <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
            <a href="logout.php">Log Out</a>
            <a href="reset-password.php">Reset Your Password</a>
        </div>
    </div>
</body>

</html>