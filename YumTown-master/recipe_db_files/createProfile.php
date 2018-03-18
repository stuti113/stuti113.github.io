<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    session_start();
?>

<!DOCTYPE html>
<head>
  <title>Register</title>
</head>

<body>
    
<h1>Register</h1>

<p>Fields marked with an asterisk are required.</p>    
    
<form action="" method=POST>
    Username:*<br>
    <input type=text name="username" required="required"> <br>
    Name:*<br>
    <input type=text name="name" required="required"> <br>
    Date of Birth:<br>
    <input type=date name="dob"><br>
    Gender:<br>
    <input type=text name="gender"><br>
    Profession:<br>
    <input type=text name="profession"><br>
    Affiliation:<br>
    <input type=text name="affiliation"><br>
    Pass:*<br>
    <input type="password" name="pass" required="required">
    <br><br>
    <input type="submit" name="submit">
    
</form>

<a href=login.php>For registered users, log in here!</a>

<?php
if(isset($_POST['submit'])){
    include "./secure/database.php";
    include "./profileFunctions.php";
    //Initiate a mysqli connection with the database
    $mysqli = new mysqli($HOST, $USERNAME, $PASSWORD, $DBNAME);
    
    if($mysqli->connect_errno){
        echo "Connection failed on line 5";
        exit();
    }
    
    #Use htmlspecialchars() to sanitize the user input.
    $username = htmlspecialchars($_POST['username']);
    $name = htmlspecialchars($_POST['name']);
    $dob = htmlspecialchars($_POST['dob']);
    $gender = htmlspecialchars($_POST['gender']);
    $profession = htmlspecialchars($_POST['profession']);
    $affiliation = htmlspecialchars($_POST['affiliation']);
    $password = htmlspecialchars($_POST['pass']);
    $userid = ""; //Will be used later to get the userid from the PROFILE table and put it into the LOGIN table.
    //Call the checkUsername function to see if the username already exists in the database. If so, this means we cannot add the user to the database.
    $exists = checkUsername($username, $mysqli);
    //The variable exists will return a number, we want to verify whether it will be zero or one. If it's zero, we will need to insert the user into the database.
    if($exists == 0){
	    //Call the function to insert the users into the PROFILE table.
        $userid = inputProfile($username, $name, $dob, $gender, $profession, $affiliation, $password, $mysqli);
        //Checks for both functions are done inside the functions.
        inputLogin($userid, $username, $password, $mysqli);
        //At this point, all of the data should be added into the PROFILE and LOGIN tables. We should be good to print out that the user is created now.	
        echo "<hr>User created<br>";
    } else {
        echo "<hr>User name taken";
    }
    #We want to close our statement and mysqli objects that we opened up to reduce the load on the server. It's not neccessary, however it is pertinent.
    $mysqli->close();
}
    
?>
