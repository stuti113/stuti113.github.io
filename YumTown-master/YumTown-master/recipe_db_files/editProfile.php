<h1>Edit your profile:</h1>

<form action="" method=POST>
    Name:<br>
    <input type=text name="name"> <br>
    Date of Birth:<br>
    <input type=date name="dob"><br>
    Gender:<br>
    <input type=text name="gender"><br>
    Profession:<br>
    <input type=text name="profession"><br>
    Affiliation:<br>
    <input type=text name="affiliation"><br>
    Pass:<br>
    <input type="password" name="pass">
    <br><br>
    <input type="submit" name="submit">
    
</form>

<?php
    //Here, we're going to be editing the user's profile.
    //I'll probably do an update on each item in the row that's set, that way we're not overwriting existing data.
    //CANNOT CHANGE USERNAME FOR NOW! It's easier just to update the non-PK data and not worry about data that's required to be unique. I will, however, need a way to change the password. Will probably edit this later to fix that.
    include "./secure/database.php";
    $mysqli = new mysqli($HOST, $USERNAME, $PASSWORD, $DBNAME);
    if($mysqli->connect_errno){
        echo "Connection failed on line 5";
        exit();
    }
    //Sanitize the user input.
    $name = htmlspecialchars($_POST['name']);
    $dob = htmlspecialchars($_POST['dob']);
    $gender = htmlspecialchars($_POST['gender']);
    $profession = htmlspecialchars($_POST['profession']);
    $affiliation = htmlspecialchars($_POST['affiliation']);
    $password = htmlspecialchars($_POST['pass']);

    //USED ONLY FOR TESTING PURPOSES!!!!!!!!! Hardcoding the username session variable
    $_SESSION['user'] = "AAAA";

    if(!empty($name)) {
        $query = "UPDATE PROFILE SET name=? WHERE username=?";
        $stmt = $mysqli->stmt_init();
        //Prepare the UPDATE statement such that the database is updated to the new full name.
        if(!$stmt->prepare($query))
        {
            echo "Statement was not properly prepared.";
            exit();
        } 
        $stmt->bind_param("ss", $name, $_SESSION['user']);
        $stmt->execute();
        if($stmt->affected_rows > 0) {
            echo "Username successfully updated!" . "<br>";
        }
        else {
            //This could either be because the user is trying to update the field with the same info, or is inputting invalid characters.
            echo "Username not updated.". "<br>";
        }
        $stmt->close();
    }
    if(!empty($dob)) {
        $query = "UPDATE PROFILE SET dob=? WHERE username=?";
        $stmt = $mysqli->stmt_init();
        //Prepare the UPDATE statement such that the database is updated to the new date of birth.
        if(!$stmt->prepare($query))
        {
            echo "Statement was not properly prepared.";
            exit();
        } 
        $stmt->bind_param("ss", $dob, $_SESSION['user']);
        $stmt->execute();
        if($stmt->affected_rows > 0) {
            echo "Date of birth successfully updated!" . "<br>";
        }
        else {
            echo "Date of birth not updated." . "<br>";
        }
        $stmt->close();
    }
    if(!empty($gender)) {
        $query = "UPDATE PROFILE SET gender=? WHERE username=?";
        $stmt = $mysqli->stmt_init();
        //Prepare the UPDATE statement such that the database is updated to the new gender.
        if(!$stmt->prepare($query))
        {
            echo "Statement was not properly prepared.";
            exit();
        } 
        $stmt->bind_param("ss", $gender, $_SESSION['user']);
        $stmt->execute();
        if($stmt->affected_rows > 0) {
            echo "Gender successfully updated!" . "<br>";
        }
        else {
            echo "Gender not updated." . "<br>";
        }
        $stmt->close();
    }
    if(!empty($profession)) {
        $query = "UPDATE PROFILE SET profession=? WHERE username=?";
        $stmt = $mysqli->stmt_init();
        //Prepare the UPDATE statement such that the database is updated to the new profession.
        if(!$stmt->prepare($query))
        {
            echo "Statement was not properly prepared.";
            exit();
        } 
        $stmt->bind_param("ss", $profession, $_SESSION['user']);
        $stmt->execute();
        if($stmt->affected_rows > 0) {
            echo "Profession successfully updated!" . "<br>";
        }
        else {
            echo "Profession not updated!" . "<br>";
        }
        $stmt->close();
    }
    if(!empty($affiliation)) {
        $query = "UPDATE PROFILE SET affiliation=? WHERE username=?";
        $stmt = $mysqli->stmt_init();
        //Prepare the UPDATE statement such that the database is updated to the new affiliation.
        if(!$stmt->prepare($query))
        {
            echo "Statement was not properly prepared.";
            exit();
        } 
        $stmt->bind_param("ss", $affiliation, $_SESSION['user']);
        $stmt->execute();
        if($stmt->affected_rows > 0) {
            echo "Affiliation successfully updated!" . "<br>";
        }
        else {
            echo "Affiliation not updated." . "<br>";
        }
        $stmt->close();
    }
    //Updating the password will need to be formatted a bit differently so that we can ensure it is hashed.
    if(!empty($password)) {
        $query = "UPDATE LOGIN SET password=? WHERE username=?";
        $stmt = $mysqli->stmt_init();
        //Prepare the UPDATE statement such that the database is updated to the new password.
        if(!$stmt->prepare($query))
        {
            echo "Statement was not properly prepared.";
            exit();
        }
        //So I should probably have the user enter their old password before they change it, but I can do that in the next sprint if needed.
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("ss", $hash, $_SESSION['user']);
        $stmt->execute();
        if($stmt->affected_rows > 0) {
            echo "Password successfully changed!" . "<br>";
        }
        else {
            echo "Password not changed." . "<br>";
        }
        $stmt->close();
    }
    
?>
