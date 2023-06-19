<?php
//Include sort.php for sorting
include 'sort.php';

if ($result->num_rows > 0) {    
    echo "<input type='text' id='myInput' onkeyup='myFunction()' placeholder='Search for files..' title='Type in a name'>"; 
    //sorting
    echo "<div class='table-settings'><table><thead><tr>
    <th><a href='main.php?page=" .htmlspecialchars($current_page, ENT_QUOTES)."&sort=".htmlspecialchars($sort, ENT_QUOTES)."'>Name ".$arrow."</a></th>
    <th><a href='main.php?page=" .htmlspecialchars($current_page, ENT_QUOTES)."&sort_type=".htmlspecialchars($sort, ENT_QUOTES)."'>Type ".$arrow."</a></th>
    <th><a href='main.php?page=" .htmlspecialchars($current_page, ENT_QUOTES)."&sort_date=".htmlspecialchars($sort, ENT_QUOTES)."'>Date Uploaded ".$arrow."</a></th>
    <th>Action</th></tr></thead><tbody>";
    //end of sorting
    while($row = $result->fetch_assoc()) {
        $name = htmlspecialchars($row["name"], ENT_QUOTES);
        $type = htmlspecialchars($row["type"], ENT_QUOTES);
        $date = date("F d, Y", strtotime($row["date"]));
        $folder_id = htmlspecialchars($row["folder_id"], ENT_QUOTES);
        //test block
        $sql_2 = "SELECT name FROM folders WHERE id='$folder_id'";
        $result_2 = $mysqli->query($sql_2);
        
        if ($result_2->num_rows > 0) {
            $row = $result_2->fetch_assoc();
            $folder_name = $row['name'];
        } else {
            $folder_name = ""; // or some other default value
        }
        $filepath = "uploads/user_" . htmlspecialchars($username, ENT_QUOTES)."/$folder_name"."/$name";
if($folder_id == 0){
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
for ($i=1; $i<=$total_pages; $i++) {
    echo "<a href='main.php?page=$i' class='page-number'";
    if ($current_page == $i) {
        echo "class='active'";
    }
    echo ">$i</a>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FileSys</title>
</head>
<body>
    <br>
        <script src="assets/search.js"></script>
</body>
</html>