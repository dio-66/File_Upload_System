<?php

// Get the user id from the database based on the username
$result = $mysqli->query("SELECT id FROM users WHERE username='$username'");
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row["id"];
} else {
    echo "No user found with the username '$username'.";
    exit;
}

if(isset($_GET['delete']) && !empty($_GET['delete'])){

    $filename = $_GET['delete'];
    $sql = "DELETE FROM files WHERE name='$filename'";

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
    //end of retrieve folder_name

    if ($mysqli->query($sql) === TRUE) {
        unlink("uploads/user_" . $username ."/$folder_name". "/$filename"); //delete file from directory
        echo "<script>alert('File deleted successfully.')</script>";
        header("Location: main.php");
    } else {
        echo "<div style='color: red;'>Error deleting file: " . $mysqli->error . "</div>";
    }
}

// Set the number of items per page
$items_per_page = 10;

// Get the total number of files for the current user
$total_files_query = "SELECT COUNT(*) as count FROM files WHERE user_id='$user_id'";
$total_files_result = $mysqli->query($total_files_query);
$total_files = $total_files_result->fetch_assoc()['count'];

// Calculate the total number of pages
$total_pages = ceil($total_files / $items_per_page);

// Get the current page number
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the offset for the current page
$offset = ($current_page - 1) * $items_per_page;

// Construct the SQL query
$sql = "SELECT * FROM files WHERE user_id='$user_id'  AND folder_id = 0";
$sql_sub = "SELECT * FROM files WHERE user_id='$user_id'";
// Sorting files
if (isset($_GET['sort']) && $_GET['sort'] == 'asc') {
    $sql .= " ORDER BY name DESC";
    $sort = 'desc';
    $arrow = '&#9660;';
} elseif (isset($_GET['sort']) && $_GET['sort'] == 'desc') {
    $sql .= " ORDER BY name ASC";
    $sort = 'asc';
    $arrow = '&#9650;';
} elseif (isset($_GET['sort_type']) && $_GET['sort_type'] == 'asc') {
    $sql .= " ORDER BY type DESC";
    $sort = 'desc';
    $arrow = '&#9650;';
} elseif (isset($_GET['sort_type']) && $_GET['sort_type'] == 'desc') {
    $sql .= " ORDER BY type ASC";
    $sort = 'asc';
    $arrow = '&#9650;';
}elseif (isset($_GET['sort_date']) && $_GET['sort_date'] == 'asc') {
    $sql .= " ORDER BY date DESC";
    $sort = 'desc';
    $arrow = '&#9650;';
} elseif (isset($_GET['sort_date']) && $_GET['sort_date'] == 'desc') {
    $sql .= " ORDER BY date ASC";
    $sort = 'asc';
    $arrow = '&#9650;';
} else {
    $sql .= " ORDER BY name ASC";
    $sort = 'asc';
    $arrow = '&#9650;';
}
// Add the limit and offset clauses for pagination
$sql .= " LIMIT $items_per_page OFFSET $offset";
$sql_sub .= " LIMIT $items_per_page OFFSET $offset";

$result = $mysqli->query($sql);
$result_sub = $mysqli->query($sql_sub);
?>