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
        <meta name="description" content="">
        <meta name="author" content="">
        
        <title>YumTown</title>
        
        <script
            src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script>
        
        <!-- Bootstrap core CSS -->
        <link href="startbootstrap-simple-sidebar-gh-pages/startbootstrap-simple-sidebar-gh-pages/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap core JavaScript -->
        <script src="startbootstrap-simple-sidebar-gh-pages/startbootstrap-simple-sidebar-gh-pages/vendor/jquery/jquery.min.js"></script>
        <script src="startbootstrap-simple-sidebar-gh-pages/startbootstrap-simple-sidebar-gh-pages/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        
        <link rel="stylesheet" type="text/css" href="Main.css">
        
        <script src="js/mustache.js"></script>
        <script>
            function logIn()
            {
                var Container = document.getElementById("container");
                Container.innerHTML =
                    '<ul class="nav nav-pills nav-stacked"><button id="loggedInNav1" type="button" class="btn btn-default">Search Dishes</button><button id="loggedInNav2" type="button" class="btn btn-default">View Saved Dishes</button><button id="loggedInNav3" type="button" class="btn btn-default">Create New Recipe</button><button id="loggedInNav4" type="button" class="btn btn-default">Edit Profile</button><button id="loggedInNav5" type="button" class="btn btn-default" onclick="logOut()">Log Out</button></ul>'
            }
            
            function logOut()
            {
                var Container = document.getElementById("container");
                Container.innerHTML =
                    '<ul class="nav nav-pills nav-stacked"><button id="generalNav1" type="button" class="btn btn-default" onclick="originalState()">Home</button><button id="generalNav2" type="button" class="btn btn-default" onclick="logInPage()">Log In</button><button id="generalNav3" type="button" class="btn btn-default" onclick="createAccountPage()">Create Account</button></ul>'
            }
        </script>
        <script src="js/mustache.js"></script>
    </head>
    <body onload="originalState()">
        <div id="mySidenav" class="sidenav"> <!--https://www.w3schools.com/howto/howto_js_sidenav.asp-->
            <img id="broccoliLeft" src="broccoli.png">
            <div id ="container" class="container">  
                <script>
                    logOut();
                </script>
<!--
                <ul class="nav nav-pills nav-stacked">
                    <button id="generalNav1" type="button" class="btn btn-default"><a data-toggle="tab" href="#home">Home</a></button>
                    <button id="generalNav2" type="button" class="btn btn-default" onclick="logIn()"><a data-toggle="tab" href="#LogIn">Log In</a></button>
                    <button id="generalNav3" type="button" class="btn btn-default"><a data-toggle="tab" href="#CreateAccount">Create Account</a></button>
                </ul>
-->
            </div>
        </div>
        
        <div id="mySidenavRight" class="sidenavRight"> <!--https://www.w3schools.com/css/css_align.asp-->
            <img id="broccoliRight" src="broccoli.png">
        </div>
        
        <div id="main">
            <h1 id="header">YumTown</h1><br><br>
            <div id ="display" style="padding-left: 100px;"></div>  
        </div>
        <script id="template" type="x-tmpl-mustache">
                    {{#main}}
                        <div id="searchDish">
                        <form action="search.php" method="post">
                            <input id="dishSearch" type="text" placeholder="Enter Dish"><br><br>
                        </form>
                        </div>
                    {{/main}}
                    
                    {{#loginPage}}
                        <form action="" method=POST>
                            Username:<br>
                            <input type="text" name="name" required="required"><br>
                            Password:<br>
                            <input type="password" name="pass" required="required">
                            <br><br>
                            <input type="submit" name="submit" value="login">
                        </form>
                    {{/loginPage}}
                    
                    {{#createAccount}}
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
                    {{/createAccount}}
                    
                    {{#details}}
                        <h2>Recipe Details</h2>
                        <p>Name: {{name}}</p>
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
                        
                    {{/details}}
        </script>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        
        <script>
        function originalState()
        {
//            logOut();
            var state =
            {
                main: true,
                login: false,
                details: false,        
            }
            var template = document.getElementById("template");
            var hash = state;
        
            var output = Mustache.render(template.innerHTML, hash);
        
            var display = document.getElementById("display");
            display.innerHTML = output;
        }
            
        function logInPage()
        {
            var logIn =
            {
                loginPage: true,
            }
            var template = document.getElementById("template");
            var hash = logIn;
        
            var output = Mustache.render(template.innerHTML, hash);
        
            var display = document.getElementById("display");
            display.innerHTML = output;
        }
            
        function createAccountPage()
        {
            var account =
            {
                createAccount: true,
            }
            var template = document.getElementById("template");
            var hash = account;
        
            var output = Mustache.render(template.innerHTML, hash);
        
            var display = document.getElementById("display");
            display.innerHTML = output;
        }
            
        function login(login) 
        {
            if(login == true)
            {
                logIn();     
            }
            else
            {
                logOut();    
            }
        }
            
        function assign(title, length, imageURL, ingredients, steps) 
        {
            var details = 
            {
                main: true,
                login: false,
                details: false,
                name: title,
                length: length,
                image: imageURL,
                ingredients:
                [   
                    
                ],
                steps: 
                [
                    
                ],
			 };
             details.steps = steps;
             details.ingredients = ingredients;  
             var template = document.getElementById("template");
             var hash = details;
        
             var output = Mustache.render(template.innerHTML, hash);
        
             var display = document.getElementById("display");
             display.innerHTML = output;
        }
        function getRecipe() 
        {
            var id = 577601;
            var url = "https://spoonacular-recipe-food-nutrition-v1.p.mashape.com/recipes/"+id+"/information?includeNutrition=false/limitLicense=true";
            $.ajax(
            {
                type: "GET",
                dataType: 'json',
                cache: false,
                url: url,
                headers: 
                {
                    "X-Mashape-Key": "KPcwDkFQicmshdW99jxJxJaXyBZ1p1VgGiGjsnKS43zN1TMUJm"
                },
                success: function(data)
                {
                    console.log(data);
                    var title = data['title'];
                            
                    var length = data['readyInMinutes'];
                            
                    var imageURL = data['image'];
                                
                    var steps=[];
                    var step = data['analyzedInstructions'][0]['steps'];
                    for(var x in step)
                    {
                        steps.push(step[x]['step']);
                    }   
                                
                    var ingredients = [];
                    var ingredient = data['extendedIngredients'];
                    for(var x in ingredient)
                    {
                        ingredients.push(ingredient[x]['originalString']);
                    }
                    console.log(imageURL);
                    assign(title, length, imageURL, ingredients, steps);
                }
            });  
        }     
	           </script>
<!--
        <div id="main">
            <h1 id="header">YumTown</h1><br><br>
            <div id="searchDish">
                <form action="search.php" method="post">
                    <input id="dishSearch" type="text" placeholder="Enter Dish"><br><br>
                </form>
            </div>
        </div>
-->
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
            }
            $stmt->close();
            $mysqli->close();
            echo '<script type="text/javascript">',
            'login(true);',
            '</script>';
        }
        ?>
        <!--Create Profile Page-->
        <?php
        if(isset($_POST['submit']) && $_POST['submit'] == 'register')
        {
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
                echo '<script type="text/javascript">',
                'login(false);',
                '</script>';
            }
            #We want to close our statement and mysqli objects that we opened up to reduce the load on the server. It's not neccessary, however it is pertinent.
            $mysqli->close();
            
            echo '<script type="text/javascript">',
            'login(true);',
            '</script>';
        }

        ?>
    </body>
</html>

