<?php
require_once('config.php');
session_start();
$username = $_SESSION['username'];


//folder rename
if (isset($_POST['rename'])) {
    $folder_name = $_POST['rename'];
    $new_folder_name = $_POST['new_folder_name'];
    $folder_path = 'uploads/user_' . $username . '/' . $folder_name;
    $new_folder_path = 'uploads/user_' . $username . '/' . $new_folder_name;
    if (rename($folder_path, $new_folder_path)) {
        echo "<p>Folder $folder_name renamed to $new_folder_name successfully!</p>";
        header("Location: main.php"); // redirect to main.php
        exit(); // stop executing the script
    } else {
        echo "<p>Error: Could not rename folder $folder_name!</p>";
    }
}


//folder move
if (isset($_POST['filename']) && isset($_POST['folder'])) {
    $filename = $_POST['filename'];
    $folder = $_POST['folder'];
    $oldFilePath = 'uploads/user_' . $_SESSION['username'] . '/' . $filename;
    $newFilePath = 'uploads/user_' . $_SESSION['username'] . '/' . $folder . '/' . $filename;
    if (rename($oldFilePath, $newFilePath)) {

        //test block

        // Get the id and name from the folders table
        $query = "SELECT id FROM folders WHERE name = '$folder'";
        $result = $mysqli->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $folder_id = $row['id'];
        } else {
            echo "Error: Folder not found.";
            exit;
        }

        // Update the folder ID in the files table
        $sql = "UPDATE files SET folder_id = '$folder_id' WHERE name = '$filename'";
        if ($mysqli->query($sql) === TRUE) {
            echo "File moved successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }

        //end of test block

    } else {
        echo 'Error moving file.';
    }
}


?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FileSys</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="wrapper">
        <h2>File Transfer</h2>
        <form method="post" action="">
            <label for="folder">Choose a Folder:</label>
            <select name="folder" id="folder">
                <?php
                $folders = glob('uploads/user_' . $_SESSION['username'] . '/*', GLOB_ONLYDIR);
                foreach ($folders as $folder) {
                    $folder_name = basename($folder);
                    echo '<option value="' . $folder_name . '">' . $folder_name . '</option>';
                }
                ?>
            </select>
            <br>
            <div style="margin-top:5%; margin-bottom:5%">
                <label for="file">Choose File to Move:</label>
                <select name="filename" id="file">
                    <?php
                    $files = glob('uploads/user_' . $_SESSION['username'] . '/*.*');
                    foreach ($files as $file) {
                        $file_name = basename($file);
                        echo '<option value="' . $file_name . '">' . $file_name . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="center">
                <input type="submit" name="move" value="Move">
                <button style="margin-left: 50px;" type="button" onclick='location.href="main.php"'>Return</button>
            </div>
        </form>
    </div>
</body>

</html>