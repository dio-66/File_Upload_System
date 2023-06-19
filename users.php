<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FileSys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <!-- Nav Bar -->
    <nav class="navbar navbar-expand-lg navbar-primary" style="background-color: #fdcf01;">
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
</body>

<!-- PHP CODE -->

<?php
// Connect to the database    
require_once('config.php');
session_start();


// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$id = $_SESSION['id'];
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] == 'delete') {
        $user_id = $_POST['user_id'];

        if ($user_id == $id) {

            // Define the delete user folder directory function
            function deleteFolder($folderPath)
            {
                if (is_dir($folderPath)) {
                    // Open the directory
                    $dirHandle = opendir($folderPath);

                    // Iterate over the contents of the folder
                    while (($file = readdir($dirHandle)) !== false) {
                        if ($file != "." && $file != "..") {
                            $filePath = $folderPath . "/" . $file;

                            // Check if the current item is a file or a folder
                            if (is_dir($filePath)) {
                                // Recursively delete subfolders and their contents
                                deleteFolder($filePath);
                            } else {
                                // Delete the file
                                unlink($filePath);
                            }
                        }
                    }

                    // Close the directory handle
                    closedir($dirHandle);

                    // Delete the empty folder
                    rmdir($folderPath);
                }
            }

            // Get the user_id from the form submission or any other source
            $user_id = $_POST['user_id'];

            // Connect to the database and retrieve the username based on the user_id

            $user_id = mysqli_real_escape_string($mysqli, $user_id);
            $sql = "SELECT username FROM users WHERE id='$user_id'";
            $result = mysqli_query($mysqli, $sql);
            if (!$result) {
                die("Query failed: " . mysqli_error($mysqli));
            }
            $user = mysqli_fetch_assoc($result);
            if (!$user) {
                die("User not found");
            }
            $username = $user['username'];

            // Define the path to the user's folder
            $folderPath = "uploads/user_" . $username;

            // Check if the folder exists
            if (is_dir($folderPath)) {
                // Delete the folder and its contents recursively
                deleteFolder($folderPath);
                echo "User folder deleted successfully.";
            } else {
                echo "User folder does not exist.";
            }
            //end of  test

            // deletes all files database
            $delete_files_sql = "DELETE FROM files WHERE user_id='$user_id'";
            $delete_files_result = mysqli_query($mysqli, $delete_files_sql);

            // deletes all folders database
            $delete_folder_sql = "DELETE FROM folders WHERE user_id='$user_id'";
            $delete_folder_result = mysqli_query($mysqli, $delete_folder_sql);


            // delete user_id
            $delete_user_sql = "DELETE FROM users WHERE id='$user_id'";
            $delete_user_result = mysqli_query($mysqli, $delete_user_sql);

            // Check if the user is logged in


            header('Location: logout.php');
            exit;
        }


        if ($user_id != $id) {

            // Define the delete user folder directory function
            function deleteFolder($folderPath)
            {
                if (is_dir($folderPath)) {
                    // Open the directory
                    $dirHandle = opendir($folderPath);

                    // Iterate over the contents of the folder
                    while (($file = readdir($dirHandle)) !== false) {
                        if ($file != "." && $file != "..") {
                            $filePath = $folderPath . "/" . $file;

                            // Check if the current item is a file or a folder
                            if (is_dir($filePath)) {
                                // Recursively delete subfolders and their contents
                                deleteFolder($filePath);
                            } else {
                                // Delete the file
                                unlink($filePath);
                            }
                        }
                    }

                    // Close the directory handle
                    closedir($dirHandle);

                    // Delete the empty folder
                    rmdir($folderPath);
                }
            }

            // Get the user_id from the form submission or any other source
            $user_id = $_POST['user_id'];

            // Connect to the database and retrieve the username based on the user_id

            $user_id = mysqli_real_escape_string($mysqli, $user_id);
            $sql = "SELECT username FROM users WHERE id='$user_id'";
            $result = mysqli_query($mysqli, $sql);
            if (!$result) {
                die("Query failed: " . mysqli_error($mysqli));
            }
            $user = mysqli_fetch_assoc($result);
            if (!$user) {
                die("User not found");
            }
            $username = $user['username'];

            // Define the path to the user's folder
            $folderPath = "uploads/user_" . $username;

            // Check if the folder exists
            if (is_dir($folderPath)) {
                // Delete the folder and its contents recursively
                deleteFolder($folderPath);
                echo "User folder deleted successfully.";
            } else {
                echo "User folder does not exist.";
            }
            //end of  test

            // deletes all files database
            $delete_files_sql = "DELETE FROM files WHERE user_id='$user_id'";
            $delete_files_result = mysqli_query($mysqli, $delete_files_sql);

            // deletes all folders database
            $delete_folder_sql = "DELETE FROM folders WHERE user_id='$user_id'";
            $delete_folder_result = mysqli_query($mysqli, $delete_folder_sql);


            // delete user_id
            $delete_user_sql = "DELETE FROM users WHERE id='$user_id'";
            $delete_user_result = mysqli_query($mysqli, $delete_user_sql);

            // Check if the user is logged in


            header('Location: users.php');
            exit;
        }
    } elseif ($_POST['action'] == 'save') {
        $user_id = $_POST['user_id'];
        $new_fullname = mysqli_real_escape_string($mysqli, $_POST['fullname']);
        $new_username = mysqli_real_escape_string($mysqli, $_POST['username']);
        $new_user_type = $_POST['user_type'];
        // Implement update functionality here
        $update_sql = "UPDATE users SET fullname='$new_fullname', username='$new_username', user_type='$new_user_type' WHERE id='$user_id'";
        $update_result = mysqli_query($mysqli, $update_sql);
        if (!$update_result) {
            die("Query failed: " . mysqli_error($mysqli));
        }
        header('Location: logout.php');
    }
}

// Check if a user is being edited
if (isset($_GET['page']) && $_GET['page'] == 'edit' && isset($_GET['user_id'])) {
    // Retrieve the user information from the database
    $user_id = $_GET['user_id'];
    $edit_sql = "SELECT * FROM users WHERE id='$user_id'";
    $edit_result = mysqli_query($mysqli, $edit_sql);
    if (!$edit_result) {
        die("Query failed: " . mysqli_error($mysqli));
    }
    $user = mysqli_fetch_assoc($edit_result);
    if (!$user) {
        die("User not found");
    }
    // Display a form to edit the user's information
    echo "<div class='wrapper'><h2>Edit User</h2>";
    echo "<form method='post' action='users.php'>";
    echo "<input type='hidden' name='user_id' value='" . $user["id"] . "'>";
    echo "<label>Fullname:</label>";
    echo "<input type='text' name='fullname' value='" . $user["fullname"] . "'><br>";
    echo "<label>Username:</label>";
    echo "<input type='text' name='username' value='" . $user["username"] . "'><br>";
    echo "<label>User Type:</label>";
    echo "<select name='user_type'>";
    echo "<option value='2'" . ($user["user_type"] == 2 ? " selected" : "") . ">User</option>";
    echo "<option value='1'" . ($user["user_type"] == 1 ? " selected" : "") . ">Admin</option>";
    echo "</select><br>";
    echo "<div class='center'><button type='submit' name='action' value='save'>Save Changes</button></div>";
    echo "</form></div>";
} elseif (isset($_GET['page']) && $_GET['page'] == 'files_edit') {
    //test block

    // Check if a user is being edited
    if (isset($_GET['page']) && $_GET['page'] == 'files_edit') {

        // Prepare and execute the query
        $sql = "SELECT f.*, u.username, u.fullname FROM files f JOIN users u ON f.user_id = u.id";
        $result = mysqli_query($mysqli, $sql);
        if (!$result) {
            die("Query failed: " . mysqli_error($mysqli));
        }

        echo "<div class='table-settings'>";
        // Display the data in a table
        echo "<div class='box'>";
        echo "<table>";
        echo "<tr><th>Fullname</th><th>Username</th><th>User ID</th><th>Name</th><th>Type</th><th>Date</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["fullname"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
            echo "<td>" . $row["user_id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["type"] . "</td>";
            echo "<td>" . $row["date"] . "</td>";
            echo '</tr>';
        }
        echo '</table></div></div>';
    }

    //test block end
} else {
    // Retrieve all users from the database
    $sql = "SELECT * FROM users";
    $result = mysqli_query($mysqli, $sql);
    if (!$result) {
        die("Query failed: " . mysqli_error($mysqli));
    }
    // Display the table 
    echo "<div class='wrapper'>";
    echo "<h2>User List</h2>";
    echo "<table class='table-settings'>";
    echo "<tr><th>Fullname</th><th>Username</th><th>User Type</th><th>Action</th></tr>";
    // Loop through the result set and display each user's information
    while ($row = mysqli_fetch_assoc($result)) {
        // Display a form to edit the user's information
        echo "<tr>";
        echo "<td>" . $row["fullname"] . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>" . ($row["user_type"] == 1 ? "Admin" : "User") . "</td>";
        echo "<td>";
        echo "<button type='button' style='margin-right: 10px;' onclick='location.href=\"users.php?page=edit&user_id=" . $row["id"] . "\";'>Edit</button>";
        echo "<form method='post' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this user?\")'>";
        echo "<input type='hidden' name='user_id' value='" . $row["id"] . "'>";
        echo "<button type='submit' style='margin-left: 10px;' name='action' value='delete'>Delete</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    // Close the table
    echo "</table></div>";
}

// Check if the current page is the users.php page and the page parameter is set to edit or files_edit
if (basename($_SERVER['PHP_SELF']) == 'users.php' && isset($_GET['page']) && ($_GET['page'] == 'edit' || $_GET['page'] == 'files_edit')) {
    // Display the return button
    echo "<div class='center'><a href='users.php'>Return</a></div>";
} else {
    // Display the home and files button
    echo "<div class='center'><button type='button' onclick='location.href=\"users.php?page=files_edit" . "\";'>Files</button></div>";
}


// Close the connection to the database
mysqli_close($mysqli);
?>

<!-- PHP CODE END -->

</html>