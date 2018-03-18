<?php

function checkUsername($username, $mysqli) {
        //We'll be using this statement to see if the username exists or not. One might ask, "Andrew, why are you querying the LOGIN table and not the PROFILE table?" The reason for that is because it's added to the LOGIN and PROFILE at the same time theoretically (even though PROFILE is added first, then LOGIN), because all of the checks I do should make sure the username is valid before I add it to the LOGIN *and* the PROFILE page, thus eliminating the risk that a row could be added in the profile data but not having a corresponding row in the LOGIN table. In addition, if the query can't add the data to the LOGIN table, it'll delete it from the PROFILE table before exiting. Lastly, I checked on LOGIN because I wanted to query for the username, and I didn't add it in the PROFILE page until later. Live and learn, I guess lol
        $query = "SELECT * FROM LOGIN WHERE username=?";
        #Here we are initializing our statement. This is letting the mysqli know that we are initializing the prepared statement.
        //echo "Got here too!";
        $stmt = $mysqli->stmt_init();
        #This if statement will make sure that the SQL statement that we are trying to prepare is valid. If it is not, we will exit.
        if(!$stmt->prepare($query))
        {
            //print "Got inside the if statement.";
            exit();
        }
        #This process is called binding the parameter, we do it to this statement. Doing this will bind our name to "s". We chose s because it stands for string, if we used an int we could use i.
        $stmt->bind_param("s", $username);
        #Finally, we will need to bind our statement. We will need to follow all of these steps every time for each prepared statement.
        $stmt->execute();
        #now, we assign our statement result to result. We are trying to see if our PK is filled and unique; if result is 1, we have it filled out and know we have one user stored in there. 
        $result = $stmt->get_result();
        #The number of rows is returned here, we need it to determine if the PK exists as stated in the previous comment.
        $exists = $result->num_rows;
        //Close the stmt to reduce load on the server.
        $stmt->close();
        return $result->num_rows;
    }
    
    function inputProfile($username, $name, $dob, $gender, $profession, $affiliation, $password, $mysqli) {
        #Here we are inserting the values into the database. First, we'll insert the data into the PROFILE table.
        $query1 = "INSERT INTO PROFILE VALUES('NULL',?,?,?,?,?,?)";
        $stmt = $mysqli->stmt_init(); //This first init will be used for preparing the insert into PROFILE.
        if(!$stmt->prepare($query1)){
            echo "Could not prepare query1 properly";
            exit();
        }
        $stmt->bind_param("ssssss", $username, $name, $dob, $gender, $profession, $affiliation);
        if($stmt->execute() == false) {
            echo "PHP query 1 was not executed properly";
        }
        //Now that the data is inserted into the PROFILE table, we'll insert it into the LOGIN table.
        //To input the data in the LOGIN table, we'll need to get the profile's ID first. We can just get it with the username. That's actually why I added the username to the PROFILE database, because I needed a way to get the table we just created, and I didn't know the UID because that was generated in the database with the SQL. 
        $query2 = "SELECT userid FROM PROFILE WHERE username=?";
        $stmt = $mysqli->stmt_init(); //Initializing the statement again
        if(!$stmt->prepare($query2)){
            echo "Could not prepare query2 properly";
            exit();
        }
        $stmt->bind_param("s", $username);
        if($stmt->execute() == false) {
            echo "PHP query 2 was not executed properly";
        }
        //After executing the statement, we can get the result and put it into our result array. We'll need to use it to get the userid.
        $result = $stmt->get_result();
        //Get the result from the statement so that we can get the UID to insert into the LOGIN table. 
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            //Should only be one loop through this, not really sure if a loop is necessary, but this is the way that I'm used to, so this is the way I'm doing it.
            foreach ($row as $r)
            {
                $userid = $r; //Here, we assign userid from the query's result, we can now insert it into the LOGIN table along with the username and password fields.
            }
        }
        $stmt->close();
        return $userid;
    }
    
    function inputLogin($userid, $username, $password, $mysqli) {
        $query3 = "INSERT INTO LOGIN VALUES(?,?,?)";
        $stmt = $mysqli->stmt_init(); //This second init will be used for preparing the insert into LOGIN. I'm not actually sure if we need to initialize again and again, but I'm doing it anyway just to make sure it's initialized.
        if(!$stmt->prepare($query3)){ //Same error checking for query3 as the others. It's getting repetitive at this point lol
            echo "Could not prepare query3 properly";
            //Since the statement couldn't be prepared, we'll have a hanging PROFILE row without a corresponding LOGIN row. We'll need to delete that. I'll make it a function later. 
            exit();
        }
        #Fortunately, someone already created a function to hash the password for us. We can call it to hash it, using the PASSWORD_DEFAULT hash (this one is the default hash, hence the name). We store the hash to convert the password back to plaintext later, I think. THIS MAY MAKE THE LOGIN PAGE A PAIN IN THE ASS (hopefully not, there should be an unhash function)! We'll see though lol, that'll be a problem for next week ;)
	    #Here we are binding the name and hash to ss.
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $userid, $username, $hash);
        if($stmt->execute() == false) {
            echo "PHP query 3 was not executed properly";
            //Will also need to delete the row here if it's been added to the PROFILE table.
            exit();
        }
        $stmt->close();
    }
?>
