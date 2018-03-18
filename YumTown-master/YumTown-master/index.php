<!-- <?php
    //Used for printing PHP errors, will want to remove upon moving to production, I believe
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    session_start();
?> -->

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
<!--                 <script>
                    //Get session variable
                    <?php 
                    //unset($_SESSION['user']);
                    ?>
                    var sessionVar = "
//                     if(isset($_SESSION['user'])) {
//                         echo $_SESSION['user'];
//                     }
//                     else {
//                         echo "NULL";
//                     }
//                     ?>";
                    console.log(sessionVar);
                    if(sessionVar != "NULL"){
                        logInToolbar();
                    }
                    else {
                        //homePageDisplay();
                        logOutButton();   
                    }                    
                </script> -->

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
                    
                   
        </script>
        
   
        
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
        
      

         
        
        
    </div>
    </body>
</html>
