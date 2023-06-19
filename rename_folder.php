<?php
require_once('config.php');
session_start();
$username = $_SESSION['username'];

if(isset($_POST['foldername']) && isset($_POST['new_folder_name'])) {
    $old_folder_name = $_POST['foldername'];
    $new_folder_name = $_POST['new_folder_name'];
    $old_folder_path = 'uploads/user_' . $username . '/' . $old_folder_name;
    $new_folder_path = 'uploads/user_' . $username . '/' . $new_folder_name;
    if(!file_exists($new_folder_path)) {
        if(rename($old_folder_path, $new_folder_path)) {
            // Update the folder name in the database
            $mysqli->query("UPDATE folders SET name='$new_folder_name' WHERE user_id = (SELECT id FROM users WHERE username='$username') AND name='$old_folder_name'");
            echo "<p>Folder $old_folder_name renamed to $new_folder_name successfully!</p>";
            header("Location: main.php"); // redirect to main.php
            exit();
        } else {
            echo "<p>Error: Could not rename folder $old_folder_name to $new_folder_name!</p>";
        }
    } else {
        echo "<p>Error: Folder $new_folder_name already exists!</p>";
    }
} elseif(isset($_GET['foldername'])) {
    $foldername = $_GET['foldername'];
    if($foldername == '') {
        echo "<p>Error: Folder name not specified!</p>";
    } else {
        echo "<h2>Rename Folder: $foldername</h2>";
        echo "<script>
                function showRenameInput() {
                    var renameBtn = document.getElementById('renameBtn');
                    var renameForm = document.getElementById('renameForm');
                    renameBtn.style.display = 'none';
                    renameForm.style.display = 'block';
                }
             </script>";
        echo "<button id='renameBtn' onclick='showRenameInput();'>Rename Folder</button>";
        echo "<form id='renameForm' style='display: none;' action='rename_folder.php' method='post'><input type='hidden' name='foldername' value='$foldername'><input type='text' name='new_folder_name' required><button type='submit'>Rename</button></form>";
    }
} else {
    echo "<p>Error: Folder name not specified!</p>";
}
?>
