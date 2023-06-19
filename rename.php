<?php
require_once('config.php');
session_start();

if (isset($_POST['submit'])) {
    $username = $_SESSION['username'];
    $filename = $_POST['filename'];
    $newfilename = $_POST['newfilename'];

    // Get the user id from the database based on the username
    $result = $mysqli->query("SELECT id FROM users WHERE username='$username'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row["id"];
    } else {
        echo "No user found with the username '$username'.";
        exit;
    }

    //get subfolder name

     //retrieve folder_name 
     $sql_1 = "SELECT folder_id FROM files WHERE name='$filename'";
     $result_1 = $mysqli->query($sql_1);
     
     if ($result_1->num_rows > 0) {
         $row = $result_1->fetch_assoc();
         $folder_id = $row['folder_id'];
     } else {
         $folder_id = ""; // or some other default value
     }
     
     $sql_2 = "SELECT name FROM folders WHERE id='$folder_id'";
     $result_2 = $mysqli->query($sql_2);
     
     if ($result_2->num_rows > 0) {
         $row = $result_2->fetch_assoc();
         $folder_name = $row['name'];
     } else {
         $folder_name = ""; // or some other default value
     }

    //end of get subfolder name

    // Check if the file exists and rename it
    if (file_exists("uploads/user_" . $username ."/$folder_name". "/$filename")) {
        $filetype = pathinfo("uploads/user_" . $username ."/$folder_name". "/$filename", PATHINFO_EXTENSION);
        $newfilename .= "." . $filetype;
        $oldFilePath = 'uploads/user_' . $username . '/' . $folder_name . '/' . $filename;
        $newFilePath = 'uploads/user_' . $username . '/' . $folder_name . '/' . $newfilename;
        if (rename($oldFilePath, $newFilePath)) {
            $sql = "UPDATE files SET name='$newfilename' WHERE name='$filename' AND user_id='$user_id'";
            if ($mysqli->query($sql) === TRUE) {
                echo "<script>alert('File renamed successfully.')</script>";
                header("Location: main.php"); // redirect back to main.php
                exit();
            } else {
                echo "<div style='color: red;'>Error renaming file: " . $mysqli->error . "</div>";
            }
        } else {
            echo "<div style='color: red;'>Error renaming file.</div>";
        }
    } else {
        echo "<div style='color: red;'>File not found.</div>";
    }
}

$mysqli->close();

?>

<!DOCTYPE html>
<html>
<head>
    <title>FileSys</title>
 <!--   <link rel="stylesheet" type="text/css" href="style.css"> -->
</head>
<body>
    <div class="container">
        <h1>Rename File</h1>
        <form action="" method="post">
            <?php
            if (isset($_GET['rename'])) {
                $filename = $_GET['rename'];
                echo "<input type='hidden' name='filename' value='$filename'>";
                echo "<label for='newfilename'>New Name:</label>";
                echo "<input type='text' id='newfilename' name='newfilename' required>";
                echo "<input type='submit' name='submit' value='Rename'>";
            } else {
                echo "No file selected to rename.";
            }
            ?>
        </form>
    </div>
</body>
</html>
