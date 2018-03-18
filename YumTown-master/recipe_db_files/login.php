<?php
    //Used for printing PHP errors, will want to remove upon moving to production, I believe
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    session_start();
?>

<!DOCTYPE html>
<head>
  <title>Login</title>
</head>

<body>

<form action="" method=POST>
  Username:<br>
  <input type=text name="name" required="required"> <br>
  Password:<br>
  <input type="text" name="pass" required="required">
  <br><br>
  <input type="submit" name="submit">
</form>
    
<a href="index.php">To create an account, click here!</a>

<?php
if(isset($_POST['submit'])){
    include "./secure/database.php";
    $mysqli = new mysqli($HOST, $USERNAME, $PASSWORD, $DBNAME);
    if($mysqli->connect_errno){
        echo "Connection failed on line 5";
        exit();
    }
    #Do it this way so that you can get the password from the user table where the user field is equal to the user input.
    $query = "SELECT password FROM LOGIN WHERE username=?";
    $stmt = $mysqli->stmt_init();
    if(!$stmt->prepare($query))
    {
        echo "Statement was not properly prepared.";
        exit();
    }
    #Use htmlspecialchars() to sanitize the user input.
    $username = htmlspecialchars($_POST['name']);
    $password = htmlspecialchars($_POST['pass']);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows;
    $r = 0;
    $password_correct = 0;
    $dbPasswordHash; //Used to get the hashed password from the numeric array in the database. 
    while ($row = $result->fetch_array(MYSQLI_NUM))
        {
            foreach ($row as $r)
            {
                $dbPasswordHash = $r;
            }
        }
    //Check if the password the user inputs in the login.php page is equal to the password in the database by using the password_verify() function to unhash the database password and compare it to the password input by the user. I also learned you can just use $r instead of $dbPasswordHash since there's only one thing in the array, which is pretty cool, but I made $r be assigned to $dbPasswordHash so that it makes more sense if anyone actually reads this code.
    if(password_verify($password, $dbPasswordHash)) {
        print "These passwords match!";
        $password_correct = 1;
    }
    #If the username exists, this means the user can be logged in. Verify if the password is correct.
    if($password_correct == 0){
        echo "<hr>Username or password invalid, please try again.<br>";
    } else {
        echo "<hr>User sucessfully logged in!";
        #Assign name to session variable.
        $_SESSION['user'] = $username;
        #Assign password to session variable.
        $_SESSION['pass'] = $password;
        //Redirect to the profile.php page after being logged in.
        header("Location:profile.php");
    }
    $stmt->close();
    $mysqli->close();
}
?>
