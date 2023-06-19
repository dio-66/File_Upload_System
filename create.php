<?php
require_once('config.php');
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
$username = $_SESSION['username'];

// Get the user id from the database based on the username
$result = $mysqli->query("SELECT id FROM users WHERE username='$username'");
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row["id"];
} else {
    echo "No user found with the username '$username'.";
    exit;
}

if (isset($_POST['submit_docs'])) {
    $doc_body = $_POST['doc_body'];
    $filename = $_POST['filename'];

    $target_dir = "uploads/user_" . $username . "/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . $filename . ".doc";
    $file = fopen($target_file, 'w');

    fwrite($file, "<html>{$doc_body}</html>");
    fclose($file);

    $folder_id = 0;

    // Save file information to the database
    $date_uploaded = date('Y-m-d H:i:s');
    $conn = mysqli_connect('localhost', 'root', '', 'filesys_db');
    $sql = "INSERT INTO files (user_id, name, type, date, folder_id) VALUES ('$user_id', '$filename.doc', 'text/html', '$date_uploaded', '$folder_id')";
    mysqli_query($conn, $sql);

    echo "File saved successfully.";
}

$doc_body = "";
$mysqli->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>FileSys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>
</head>

<body>
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
                        <a class="nav-link" href="index.php">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="main.php">UPLOAD</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">LOG OUT</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- End of Nav Bar -->
    <div class="wrapper">
        <h2>NOTES</h2>
        <form name="proposal_form" action="<?php echo ($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="filename">File name:</label><br>
            <input type="text" name="filename" id="filename" value=""><br><br>
            <label for="doc_body">Document body:</label><br>
            <textarea name="doc_body" id="editor"><?php echo $doc_body; ?></textarea><br><br>
            <script>
                ClassicEditor
                    .create(document.querySelector('#editor'))
                    .catch(error => {
                        console.error(error);
                    });
            </script>
            <div class="center"><input type="submit" name="submit_docs" value="Export as MS Word" /></div>
    </div>
</body>

</html>