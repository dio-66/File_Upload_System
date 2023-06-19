<?php
require_once('config.php');
session_start();
$username = $_SESSION['username'];

if(isset($_GET['foldername'])) {
    $folder_name = $_GET['foldername'];
    $folder_path = 'uploads/user_' . $username . '/' . $folder_name;
    if(is_dir($folder_path)) {
        if(PHP_OS_FAMILY === 'Windows') {
            exec("rd /s /q ".escapeshellarg($folder_path)); // For Windows OS
        } else {
            exec("rm -rf ".escapeshellarg($folder_path)); // For Linux/MacOS
        }
        // Delete the folder record from the database
            $user_id = $mysqli->query("SELECT id FROM users WHERE username='$username'")->fetch_assoc()['id'];
            $folder_id = $mysqli->query("SELECT id FROM folders WHERE user_id=$user_id AND name='$folder_name'")->fetch_assoc()['id'];
            $mysqli->query("DELETE FROM folders WHERE user_id=$user_id AND name='$folder_name'");
            $mysqli->query("DELETE FROM files WHERE folder_id=$folder_id");

        echo "<p>Folder $folder_name deleted successfully!</p>";
    } else {
        echo "<p>Error: Folder $folder_name does not exist!</p>";
    }
}
header("Location: main.php"); // redirect to main.php
exit();
?>