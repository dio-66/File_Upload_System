<?php
require_once('config.php');
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

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $foldername = $_POST['foldername'];
  if(empty($foldername)){
      $errors[] = "Please provide a folder name.";
  } else {
      $foldername = preg_replace('/\s+/', '_', $foldername);
      $foldername = preg_replace('/[^A-Za-z0-9_\-]/', '', $foldername);
  }

  // Check if the folder already exists
  $result = $mysqli->query("SELECT id FROM folders WHERE user_id='$user_id' AND name='$foldername'");
  if ($result->num_rows > 0) {
    $errors[] = "Folder already exists.";
  }

  if(isset($errors)){
    foreach($errors as $error){
      echo "<p class='text-danger'>$error</p>";
    }
  } else {
    // Create the folder on the server
    $target_dir = "uploads/user_" . $username . "/";
    if (!file_exists($target_dir)) {
      mkdir($target_dir, 0777, true);
    }
    $target_folder = $target_dir . $foldername;
    if (!file_exists($target_folder)) {
      mkdir($target_folder, 0777, true);
    }

    // Save folder information to the database
    $date_created = date('Y-m-d H:i:s');
    $sql = "INSERT INTO folders (user_id, name, date_created) VALUES ('$user_id', '$foldername', '$date_created')";
    if ($mysqli->query($sql) === TRUE) {
      echo "<script>alert('Folder created successfully.');</script>";
    } else {
      echo "Error creating folder: " . $mysqli->error;
    }
  }
}

// Get the list of folders associated with the user
$result = $mysqli->query("SELECT name FROM folders WHERE user_id='$user_id'");
if ($result->num_rows > 0) {
  echo "<table class='table'>";
  echo "<thead><tr><th>Folder Name</th><th>Actions</th></tr></thead>";
  echo "<tbody>";
  while ($row = $result->fetch_assoc()) {
    $foldername = $row["name"];
    echo "<tr>";
    echo "<td><a href='view_folder.php?foldername=$foldername'>$foldername</a></td>";
   echo "<td><a href='delete_folder.php?foldername=$foldername'>Delete</a> | <a href='rename_folder.php?foldername=$foldername'>Rename</a></td>";

       
    echo "</tr>";
  }
  echo "</tbody>";
  echo "</table>";
} else {
  echo "<p>No folders found.</p>";
}
$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>FileSys</title>
 
  <style>
    body{ font: 14px sans-serif; text-align: center; }
 

  </style>
</head>
<body>
<div class="text-center">
<br><a href="move_folder.php?file=$name">Move</a>
        </div>


</body>
</html>
