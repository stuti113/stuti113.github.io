<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        
        <title>Snazzy Recipes</title>
        
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
        
        <script>
            function logIn()
            {
                var Container = document.getElementById("container");
                Container.innerHTML =
                    '<ul class="nav nav-pills nav-stacked"><button id="loggedInNav1" type="button" class="btn btn-default"><a data-toggle="tab" href="#SearchDishes">Search Dishes</a></button><button id="loggedInNav2" type="button" class="btn btn-default"><a data-toggle="tab" href="#ViewSavedDishes">View Saved Dishes</a></button><button id="loggedInNav3" type="button" class="btn btn-default"><a data-toggle="tab" href="#CreateNewRecipe">Create New Recipe</a></button><button id="loggedInNav4" type="button" class="btn btn-default"><a data-toggle="tab" href="#EditProfile">Edit Profile</a></button><button id="loggedInNav5" type="button" class="btn btn-default" onclick="logOut()"><a data-toggle="tab" href="#LogOut">Log Out</a></button></ul>'
            }
            
            function logOut()
            {
                var Container = document.getElementById("container");
                Container.innerHTML =
                    '<ul class="nav nav-pills nav-stacked"><button id="generalNav1" type="button" class="btn btn-default"><a data-toggle="tab" href="#home">Home</a></button><button id="generalNav2" type="button" class="btn btn-default" onclick="logIn()"><a data-toggle="tab" href="#LogIn">Log In</a></button><button id="generalNav3" type="button" class="btn btn-default"><a data-toggle="tab" href="#CreateAccount">Create Account</a></button></ul>'
            }
        </script>
        <script src="js/mustache.js"></script>
    </head>
    <body onload="getRecipe()">
        <div id="mySidenav" class="sidenav"> <!--https://www.w3schools.com/howto/howto_js_sidenav.asp-->
            <img id="broccoliLeft" src="broccoli.png">
            <div id ="container" class="container">      
                <ul class="nav nav-pills nav-stacked">
                    <button id="generalNav1" type="button" class="btn btn-default"><a data-toggle="tab" href="#home">Home</a></button>
                    <button id="generalNav2" type="button" class="btn btn-default" onclick="logIn()"><a data-toggle="tab" href="#LogIn">Log In</a></button>
                    <button id="generalNav3" type="button" class="btn btn-default"><a data-toggle="tab" href="#CreateAccount">Create Account</a></button>
                </ul>
            </div>
        </div>
        
        <div id="mySidenavRight" class="sidenavRight"> <!--https://www.w3schools.com/css/css_align.asp-->
            <img id="broccoliRight" src="broccoli.png">
        </div>
        
        <div id="main">
            <h1 id="header">Snazzy Recipes</h1><br><br>
            <div id ="display" style="padding-left: 100px;"></div>  
        </div>
        <script id="template" type="x-tmpl-mustache">
                    
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
        </script>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        
        <script>
        function assign(title, length, imageURL, ingredients, steps) 
        {
            var details = 
            {
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
    </body>
</html>
