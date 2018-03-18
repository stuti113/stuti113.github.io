<?php
    //Used for printing PHP errors, will want to remove upon moving to production, I believe
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!--        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width" />-->
        <meta name="description" content="SE Project">
        <meta name="author"  content="Stuti, John, Andrew, Alexander">
        
        <title>YumTown</title>
        
        <script
            src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="Main.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        
        <script type="text/javascript" src="https://spoonacular.com/cdn/spoonacular-1.6.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="js/mustache.js"></script>
        <script src="js/functions.js"></script>
    </head>
    <body onload="loadPre()" class="container-fluid">        
        <div class="row">  
        <div class="sidenav col-2"> <!--https://www.w3schools.com/howto/howto_js_sidenav.asp-->
            <div id ="buttonContainer" onload="searchPage()">  
                <script>
                    //Get session variable
                    <?php 
                    //unset($_SESSION['user']);
                    ?>
                    var sessionVar = "<?php 
                    if(isset($_SESSION['user'])) {
                        echo $_SESSION['user'];
                    }
                    else {
                        echo "NULL";
                    }
                    ?>";
                    console.log(sessionVar);
                    if(sessionVar != "NULL"){
                        logInToolbar();
                    }
                    else {
                        //homePageDisplay();
                        logOutButton();   
                    }                    
                </script>

            </div>
        </div>
        
        <div id="main" class="col-10">
<!--            <h1 id="header">YumTown</h1>-->
            <img src="YumTown.svg.png">
            <br><br>
            <div id ="display"></div>  
        </div>
        
        <script id="template" type="x-tmpl-mustache">
                    {{#main}}
                        <div id="searchDish">
                            <input id="dishSearch" type="text" placeholder="Enter Dish">
                            <button class='btn-default' onclick='getRecipeList()'id='submit'>Search</button>
                            <br><br>
                            <div id='recipeCardContainer' class="card-columns"></div>
                        </div>
                    {{/main}}
                    
                    {{#loginPage}}
                        <div class="container-fluid">
                            <form action="" method=POST>
                                <label for='name'>Username:</label><br>
                                <input type="text" name="name" required="required"><br>
                                <label for='password'>Password:</label><br>
                                <input type="password" name="pass" required="required">
                                <br><br>
                                <input class='btn-default' type="submit" name="submit" value="login">
                            </form>
                        </div>
                    {{/loginPage}}
                    
                    {{#createAccount}}
                        <div class='col'>
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
                                <input type="submit" name="submit" value="register">
        
                            </form>
                       </div>
                    {{/createAccount}}
                    
                    {{#details}}
                        <div class="row">                        
                        <h2>Recipe Details</h2>
                        <p>Name: {{name}}</p>
                        <p>Servings: {{servings}}</p>
                        <p>Time: {{length}} Minutes</p>
                        <img src="{{image}}"></img>
                        <p>Ingredients: </p>
                        <ul>
                        {{#ingredients}}
                            <li>{{.}}</li>
                        {{/ingredients}}
                        </ul><br>
                        <p>Recipe Steps: </p>
                        <ul>
                        {{#steps}}
                            <li>{{.}}</li>
                        {{/steps}}
                        </ul><br>
                        <div id="spoonacular-price-estimator"></div>
                        <pre id="spoonacular-ingredients"></pre>
                        </div>
                    {{/details}}
                    
                    {{#editProfile}}
                        <h1>Edit your profile:</h1>

                        <form id="editPage" action="" method=POST>
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
                            <input type="submit" name="submit" value="editProfile">
                        </form>
                    {{/editProfile}}
                    
                    {{#viewProfile}}
                        <h1>Your Profile is as follows:</h1>
                        <div id="hidden_form_container" style="display:none;"></div>
                    {{/viewProfile}}
                    
                    {{#logoutUser}}
                        <div id="hidden_form_container_logout" style="display:none;"></div>
                    {{/logoutUser}}
                    
                    <!-- Display User Profile (Needs to be above the spoonacular ingredients and price estimator divs) -->
                
        </script>
        
        <?php
            //So, this time around, we're going to need to display the user's profile! Shouldn't be too hard, just need to grab the information from the database and populate a page.
            if(isset($_POST['btnSubmit']) && $_POST['btnSubmit'] == 'viewProfile') {
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
            //$_SESSION['user'] = "AAAA";

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
                echo '<div style="padding-left: 50%;" id="displayUserProfileDiv">';
                echo "<br>Your Profile is as follows:<br>";
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
            }

                ?>
        
        <pre id="spoonacular-ingredients" style="display: none;"></pre>
        <div id="spoonacular-price-estimator" class="container-fluid" style="display: none;"></div>
        
        <script id="Detailstemplate" type="x-tmpl-mustache">

                        <h2>Recipe Details</h2>
                        <p>Name: {{name}}</p>
                        <p>Servings: {{servings}}</p>
                        <p>Ready in: {{time}} minutes</p>
                        <img src="{{image}}"></img>
                        <p>Ingredients: </p>
                        <ul>
                        {{#ingredients}}
                            <li>{{.}}</li>
                        {{/ingredients}}
                        </ul><br>
                        <p>Recipe Steps: </p>
                        <ul>
                        {{#steps}}
                            <li>{{.}}</li>
                        {{/steps}}
                        </ul>
                        <br>
         
        </script>
        
        <!-- PHP Login code -->
        
        <?php
            if(isset($_POST['submit']) && $_POST['submit'] == 'login')
            {
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
            $dbPasswordHash = 0; //Used to get the hashed password from the numeric array in the database. 
            while ($row = $result->fetch_array(MYSQLI_NUM))
                {
                    foreach ($row as $r)
                    {
                        $dbPasswordHash = $r;
                    }
                }
            //Check if the password the user inputs in the login.php page is equal to the password in the database by using the password_verify() function to unhash the database password and compare it to the password input by the user. I also learned you can just use $r instead of $dbPasswordHash since there's only one thing in the array, which is pretty cool, but I made $r be assigned to $dbPasswordHash so that it makes more sense if anyone actually reads this code.
            if(password_verify($password, $dbPasswordHash)) {
                //print "These passwords match!";
                $password_correct = 1;
            }
            #If the username exists, this means the user can be logged in. Verify if the password is correct.
            if($password_correct == 0){
                $message = "Username or password invalid, please try again.";
                echo "<script type='text/javascript'>alert('$message');</script>";
                exit();
            } else {
                //echo "<hr>User sucessfully logged in!";
                #Assign name to session variable.
                $_SESSION['user'] = $username;
                #Assign password to session variable.
                $_SESSION['pass'] = $password;
                //Redirect to the profile.php page after being logged in.
//                echo '<script type="text/javascript">',
//                'logInPage();',
//                '</script>';
                echo '<script type="text/javascript">',
            'toolbarToggle(true);',
            '</script>';
            }
            $stmt->close();
            $mysqli->close();
            echo '<script type="text/javascript">',
            'toolbarToggle(true);',
            '</script>';
        }
        ?>
        <!--Create Profile Page-->
        <?php
        if(isset($_POST['submit']) && $_POST['submit'] == 'register')
        {
            include "./secure/database.php";
            include "./recipe_db_files/profileFunctions.php";
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
                $message = "User created!";
                echo "<script type='text/javascript'>alert('$message');</script>";
                $mysqli->close();
                //Probably need to switch this to the login page, because the session variables aren't set yet.
//                echo '<script type="text/javascript">',
//                'toolbarToggle(true);',
//                '</script>';
                echo '<script type="text/javascript">',
                'logOutButton();',
                '</script>';
                
            } else {
                $message = "Username taken, please choose another.";
                echo "<script type='text/javascript'>alert('$message');</script>";
                echo '<script type="text/javascript">',
                'toolbarToggle(false);',
                '</script>';
                $mysqli->close();
            }
            #We want to close our statement and mysqli objects that we opened up to reduce the load on the server. It's not neccessary, however it is pertinent.
        }
        ?>
        
        

        
        <!-- Edit user profile -->
        
        <?php
    //Here, we're going to be editing the user's profile.
    //I'll probably do an update on each item in the row that's set, that way we're not overwriting existing data.
    //CANNOT CHANGE USERNAME FOR NOW! It's easier just to update the non-PK data and not worry about data that's required to be unique. I will, however, need a way to change the password. Will probably edit this later to fix that.
        if(isset($_POST['submit']) && $_POST['submit'] == 'editProfile') {
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
            //$_SESSION['user'] = "AAAA";

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
                    //echo "Username successfully updated!" . "<br>";
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
                    //echo "Date of birth successfully updated!" . "<br>";
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
                    //echo "Gender successfully updated!" . "<br>";
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
                    //echo "Profession successfully updated!" . "<br>";
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
                echo '<script type="text/javascript">',
                'alert("Profile updated!");',
                'loggedInPage();',
                '</script>';
        }
    ?>
        
        <!-- Logout user -->
        <?php
//        $var = 0;
//        if($var == 1) {
        if(isset($_POST['logoutSubmit']) && $_POST['logoutSubmit'] == "logoutUser") {
//The logout is a small piece of logic that unsets the session variables and returns to the home page with the user logged out.

// Delete certain session
unset($_SESSION['user']);
unset($_SESSION['pass']);

echo '<script type="text/javascript">',
'toolbarToggle(false);',
'</script>';

echo '<script type="text/javascript">alert("Logged out user successfully!");</script>';
}
//}

?>
    </div>
    </body>
</html>
