<?php
 require_once('config.php');
 session_start();
 $username = $_SESSION['username'];

 include 'sort.php';

 if(isset($_GET['foldername'])){
    // Retrieve the value of the "folder_id" key
    $foldername = $_GET['foldername'];

} else {
    // Display an error message if the "folder_id" key is not present
    echo "Error: No foldername specified in the URL.";
}

 if ($result_sub->num_rows > 0) {    
    echo "<div class='center'><input type='text' id='myInput' class='search-bar' onkeyup='myFunction()' placeholder='Search for files..' title='Type in a name'></div>";
    
    echo "<div class='table-settings'><table><thead><tr>
    <th>Name</th><th>Type</th><th>Date Uploaded</th><th>Action</th></tr></thead><tbody>";

    while($row = $result_sub->fetch_assoc()) {
        $name = htmlspecialchars($row["name"], ENT_QUOTES);
        $type = htmlspecialchars($row["type"], ENT_QUOTES);
        $date = date("F d, Y", strtotime($row["date"]));
        $folder_id = htmlspecialchars($row["folder_id"], ENT_QUOTES);
        $sql_2 = "SELECT name FROM folders WHERE id='$folder_id'";
        $result_2 = $mysqli->query($sql_2);
        
        if ($result_2->num_rows > 0) {
            $row = $result_2->fetch_assoc();
            $folder_name = $row['name'];
        } else {
            $folder_name = ""; // or some other default value
        }
        $filepath = "uploads/user_" . htmlspecialchars($username, ENT_QUOTES)."/$folder_name"."/$name";
        if($folder_name == $foldername){
                //end of test block
               
                if (in_array($type, array('pdf', 'doc', 'docx', 'txt'))) {
                    echo "<tr><td><a href='$filepath' target='_blank'>$name</a></td><td>$type</td><td>$date</td><td><a href='main.php?download=$name'>Download</a> | <a href='main.php?delete=$name' onclick=\"return confirm('Are you sure you want to delete $name?');\">Delete</a> | <a href='rename.php?rename=$name'>Rename</a> |</td></tr>";
                }else {
                    echo "<tr><td><a href='$filepath' target='_blank' onclick=\"return confirm('Are you sure you want to view $name?');\">$name</a></td><td>$type</td><td>$date</td><td><a href='$filepath' download='$name'>Download</a> | <a href='main.php?delete=$name' onclick=\"return confirm('Are you sure you want to delete $name?');\">Delete</a> | <a href='rename.php?rename=$name'>Rename</a></td></tr>";
                }
        
            }
        }
            echo "</tbody></table></div>";
            
        
        } else {
            echo "No files found for user ".htmlspecialchars($username, ENT_QUOTES).".";
        }
        
      /*  
        for ($i=1; $i<=$total_pages; $i++) {
            echo "<a href='main.php?page=$i' class='page-buttons'";
            if ($current_page == $i) {
                echo "class='active'";
            }
            echo ">$i</a>";
        }
        */
        $mysqli->close();
        ?>
        
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>FileSys</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <link rel="stylesheet" href="assets/style.css"> 
        
        </head>
        <body>
                <a href="main.php" class="center">BACK</a>  
                <script src="assets/search.js"></script>
        </body>
        </html>