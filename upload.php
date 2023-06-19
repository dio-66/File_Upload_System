<?php

require_once('config.php');

session_start();
$username = $_SESSION['username'];

if (isset($_POST["submit"])) {
 
    $target_dir = "uploads/user_" . $username . "/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;

    $maxFileSize = 1000 * 1024 * 1024; // 120MB
    if ($_FILES["file"]["size"] > $maxFileSize) {
        echo "Sorry, your file is too large. ";
        $uploadOk = 0;
    }

    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.<br>";
        $uploadOk = 0;
    }

    // Allow only certain file types
    $allowedFileTypes = ["php", "html", "css", "js", "txt", "pdf", "doc", "docx", "xls", "xlsx", "ppt", "pptx", "jpg", "jpeg", "png", "gif", "mp4", "avi", "mov", "wmv", "flv"];
    if (!in_array($fileType, $allowedFileTypes)) {
        echo "Sorry, only certain file types are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded. ";
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $name = basename($_FILES["file"]["name"]);
            $type = mime_content_type($target_file);
            $date = date("Y-m-d H:i:s");
            $folder_id = 0;

            // Get the user_id from the users table
            $query = "SELECT id FROM users WHERE username = '$username'";
            $result = $mysqli->query($query);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_id = $row['id'];
            } else {
                echo "Error: User not found.";
                exit;
            }

            // Insert the file into the files table
            $sql = "INSERT INTO files (name, type, date, user_id, folder_id) 
        VALUES ('$name', '$type', '$date', '$user_id', '$folder_id')";
            if ($mysqli->query($sql) === TRUE) {
                echo "The file ". basename($_FILES["file"]["name"]). " has been uploaded.<br><br>";
            } else {
                echo "Error: " . $sql . "<br>" . $mysqli->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

$mysqli->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FileSys</title>
   <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
        <a href="main.php" class="btn btn-warning ml-3">Back</a>
</body>
</html>