<!DOCTYPE html>
<head>
  <title>Display Profile</title>
</head>

<body>

<?php
    //So, this time around, we're going to need to display the user's profile! Shouldn't be too hard, just need to grab the information from the database and populate a page.
    //Start our mysqli connection, as usual.
    include "./secure/database.php";
    $mysqli = new mysqli($HOST, $USERNAME, $PASSWORD, $DBNAME);
    if($mysqli->connect_errno){
        echo "Connection failed on line 5";
        exit();
    }
    //Write our query to get all of the information from user, where user is equal to the session variable we will bind using prepared statements.
    
    //FOR TESTING ONLY!!! Hardcoding the session variable
    //$_SESSION['user'] = "CowboyTitanium";
    $_SESSION['user'] = "AAAA";
    
    $query = "SELECT * FROM PROFILE WHERE username=?";
    $stmt = $mysqli->stmt_init();
    if(!$stmt->prepare($query))
    {
        echo "Statement was unable to be prepared.";
        exit();
    }
    //Bind the user's username session variable using prepared statements.
    if(!isset($_SESSION['user'])) {
        echo "User session variable was not set.";
        exit();
    }
    $stmt->bind_param("s", $_SESSION['user']);
    //Execute the query
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows;
    if($exists == 0) {
        echo "Result has 0 rows. Something went wrong.";
        exit();
    }
    //Now that we have our result from the database, we can use it to populate our webpage with HTML corresponding to the database values.
    while ($row = $result->fetch_assoc()) {
        echo '<div>';
        echo "<b>Username:</b> ". $row['username'];
        echo "<br><b>Full name:</b> ".$row['name'];
        if(!empty($row['dob'])) {
            echo "<br><b>Date of birth:</b> ".$row['dob'];
        }
        else {
            echo "<br><b>Date of birth:</b> N/A";

        }
        if(!empty($row['gender'])) {
            echo "<br><b>Gender:</b> ".$row['gender'];
        }
        else {
            echo "<br><b>Gender:</b> N/A";
        }
        if(!empty($row['profession'])) {
            echo "<br><b>Profession:</b> ".$row['profession'];
        }
        else {
            echo "<br><b>Profession:</b> N/A";
        }
        if(!empty($row['affiliation'])) {
            echo "<br><b>Affiliation:</b> ".$row['affiliation'];
        }
        else {
            echo "<br><b>Affiliation:</b> N/A";
        }
        echo "</div>";
    }
    $stmt->close();
    $mysqli->close();

?>

</body>