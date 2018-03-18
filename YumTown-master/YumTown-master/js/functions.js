    function getRecipeList() {
    $("#displayUserProfileDiv").empty();    
    var searchText = document.getElementById('dishSearch').value;
    var searchText1 = searchText.split(' ').join('+');
    var url = "https://spoonacular-recipe-food-nutrition-v1.p.mashape.com/recipes/search?&limitLicense=false&number=12&offset=0&query=" + searchText1;
    $.ajax({
        type: "GET",
        dataType: 'json',
        cache: false,
        url: url,
        headers: {
            "X-Mashape-Key": "KPcwDkFQicmshdW99jxJxJaXyBZ1p1VgGiGjsnKS43zN1TMUJm"
        },
        success: function(data) {
            console.log(data);
            var arr = [];
            var obj = data['results'];
            var imageURL = [];
            var prepTime = [];
            var idarr = [];
            for (var x in obj) {
                arr.push(obj[x]['title']);
                imageURL.push(data['baseUri'] + obj[x]['image']);
                prepTime.push(obj[x]['readyInMinutes']);
                idarr.push(obj[x]['id']);
            }
            // alert(prepTime);
            $('#recipeCardContainer').empty();
            for (i = 0; i < obj.length; i++) {
                $('#recipeCardContainer').append("<div class='card'>" +
                    "<a href='" + "#?" + idarr[i] + "' onclick='getRecipe()'>" +
                    "<img class='card-img-top' src='" + imageURL[i] + "'/></a>" +
                    "<div class='card-body'>" +
                    "<h4 class='title'>" + arr[i] + "</h4>" +
                    "<p class='card-text'>" + 'Ready in ' + prepTime[i] + ' minutes' + "</p>" +
                    "</div>" +
                    "</div>");
            }
        }
    });
}

function loadPre(servings) {
    spoonacularServings = servings;
    if (typeof spoonacularMeasure != "undefined") {
        spoonacularMeasureActive = spoonacularMeasure
    } else {
        spoonacularMeasure = spoonacularMeasureActive
    }
    if (typeof spoonacularServings != "undefined") {
        spoonacularServingsInit = spoonacularServings
    }
    if (typeof spoonacularView != "undefined") {
        spoonacularViewInit = spoonacularView
    }
    if (spoonacularMeasure == "metric") {
        spoonacularMeasureActive = "metric";
        spoonacularMeasureInactive = "us"
    } else {
        spoonacularMeasureActive = "us";
        spoonacularMeasureInactive = "metric"
    }
    if (typeof spoonacularPriceView == "undefined") {
        spoonacularPriceView = 2
    }
    var b = document.getElementById("spoonacular-ingredients");
    var c = b.innerHTML;
    var a = "https://spoonacular.com:8443";
    if (document.getElementById("spoonacular-ingredient-visualizer") != null) {
        $.ajax({
            type: "POST",
            url: a + "/recipes/visualizeIngredients",
            data: "servings=" + spoonacularServingsInit + "&view=" + spoonacularViewInit + "&measure=" + spoonacularMeasureActive + "&ingredientList=" + encodeURIComponent(c),
            success: spoonacularServerResponseSIV,
            dataType: "html"
        })
    }
    if (document.getElementById("spoonacular-price-estimator") != null) {
        $.ajax({
            type: "POST",
            url: a + "/recipes/visualizePriceEstimator",
            data: "servings=" + spoonacularServingsInit + "&mode=" + spoonacularPriceView + "&ingredientList=" + encodeURIComponent(c),
            success: spoonacularServerResponseSPE,
            dataType: "html"
        })
    }
    if (document.getElementById("spoonacular-nutrition-visualizer") != null) {
        $.ajax({
            type: "POST",
            url: a + "/recipes/visualizeNutrition",
            data: "servings=" + spoonacularServingsInit + "&mode=2&ingredientList=" + encodeURIComponent(c),
            success: spoonacularServerResponseSNV,
            dataType: "html"
        })
    }
}
        

function assign(title, imageURL, ingredients, steps, servings, time) {
    //Getting rid of the URL parameters so that next time we search, they're replaced.
    var old_url = window.location.href;
    var new_url = old_url.substring(0, old_url.indexOf('?'));
    window.location.href = new_url;
    var details = {
        name: title,
        image: imageURL,
        ingredients: [],
        steps: [],
        servings: servings,
        time: time,
        divTempFill: "here",

    };
    details.steps = steps;
    details.ingredients = ingredients;
    var template = document.getElementById("Detailstemplate");
    var hash = details;

    var output = Mustache.render(template.innerHTML, hash);
    var display = document.getElementById("display");
    display.innerHTML = output;
    loadPre(details.servings);
    
}

function getRecipe() {
    var id = document.location.href.split('?').pop();
    var url = "https://spoonacular-recipe-food-nutrition-v1.p.mashape.com/recipes/" + id + "/information?includeNutrition=false/limitLicense=true";
    $.ajax({
        type: "GET",
        dataType: 'json',
        cache: false,
        url: url,
        headers: {
            "X-Mashape-Key": "KPcwDkFQicmshdW99jxJxJaXyBZ1p1VgGiGjsnKS43zN1TMUJm"
        },
        success: function(data) {
            console.log(data);
            var title = data['title'];

            var imageURL = data['image'];
            var servings = data['servings'];
            var time = data['readyInMinutes'];
            var steps = [];
            if (data['analyzedInstructions'][0] != undefined){
                var step = data['analyzedInstructions'][0]['steps'];
            }
            for (var x in step) {
                steps.push(step[x]['step']);
            }

            var ingredients = [];
            var ingredient = data['extendedIngredients'];
            for (var x in ingredient) {
                ingredients.push(ingredient[x]['originalString']);
            }
            // console.log(imageURL);
            priceWidgetviewer(ingredients, servings);
            assign(title, imageURL, ingredients, steps, servings, time);
            $("#spoonacular-price-estimator").css('display', 'block');
        }
    });

}

function priceWidgetviewer(ingredients, servings){
    var ingredientList = ingredients;
    var spoonacularServings = servings;
    var spoonacularPriceView  = 2;
    $('#spoonacular-ingredients').empty();
    for(i = 0; i < ingredientList.length; i++){        
        $('#spoonacular-ingredients').append(ingredientList[i]+"\n");
    }
}

function logInToolbar() {
    $("#displayUserProfileDiv").empty();
    $("#spoonacular-price-estimator").css('display', 'none');

    var Container = document.getElementById("buttonContainer");
    Container.innerHTML =
        '<ul class="nav nav-pills nav-stacked"><button id="loggedInNav1" type="button" class="btn btn-default btn-block" onclick="searchPage()">Search Dishes</button><button id="loggedInNav2" type="button" class="btn btn-default btn-block">View Saved Dishes</button><button id="loggedInNav3" type="button" class="btn btn-default btn-block" onclick="viewProfilePage()">View Profile</button><button id="loggedInNav4" type="button" class="btn btn-default btn-block" onclick="editProfilePage()">Edit Profile</button><button id="loggedInNav5" type="button" class="btn btn-default btn-block" onclick="logoutUser()">Log Out</button></ul>';
    searchPage();
}

function logoutUser() {
    $("#spoonacular-price-estimator").css('display', 'none');

    //Unset PHP variables
    var profile3 = {
        logoutUser: true,
    }
    var template = document.getElementById("template");
    var hash = profile3;

    var output = Mustache.render(template.innerHTML, hash);

    var display = document.getElementById("display");
    display.innerHTML = output;

    //Call PHP function to unset the variables
    var theForm, newInput1, newInput2;
    // Start by creating a <form>
    theForm = document.createElement('form');
    theForm.action = '';
    theForm.method = 'post';
    // Next create the <input>s in the form and give them names and values
    newInput1 = document.createElement('input');
    newInput1.type = 'hidden';
    newInput1.name = 'logoutSubmit';
    newInput1.value = 'logoutUser';
    //              newInput2 = document.createElement('input');
    //              newInput2.type = 'hidden';
    //              newInput2.name = 'input_2';
    //              newInput2.value = 'value 2';
    // Now put everything together...
    theForm.appendChild(newInput1);
    //theForm.appendChild(newInput2); 
    // ...and it to the DOM...
    document.getElementById('hidden_form_container_logout').appendChild(theForm);
    // ...and submit it
    theForm.submit();
    logOutButton();

}

//Allows search to be clicked when the user is logged in.
function searchPage() {
    $("#displayUserProfileDiv").empty();
    $("#spoonacular-price-estimator").css('display', 'none');
    
    var state = {
        main: true,
    }
    var template = document.getElementById("template");
    var hash = state;

    var output = Mustache.render(template.innerHTML, hash);

    var display = document.getElementById("display");
    display.innerHTML = output; 
}

function logOutButton() {
    //alert("Got to the logout button!");
    $("#displayUserProfileDiv").empty();
    $("#spoonacular-price-estimator").css('display', 'none');
    var Container = document.getElementById("buttonContainer");
    Container.innerHTML =
        '<ul class="nav nav-pills nav-stacked"><button id="generalNav1" type="button" class="btn btn-default" onclick="homePageDisplay()">Home</button><button id="generalNav2" type="button" class="btn btn-default" onclick="logInPage()">Log In</button><button id="generalNav3" type="button" class="btn btn-default" onclick="createAccountPage()">Create Account</button></ul>';
    window.onload = function() {
        searchPage();
    };
}

function homePageDisplay() {
    $("#displayUserProfileDiv").empty();
    $("#spoonacular-price-estimator").css('display', 'none');

    
    //            logOutButton();
    var state = {
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

function logInPage() {
    $("#displayUserProfileDiv").empty();
    $("#spoonacular-price-estimator").css('display', 'none');

    
    var logIn = {
        loginPage: true,
    }
    var template = document.getElementById("template");
    var hash = logIn;

    var output = Mustache.render(template.innerHTML, hash);

    var display = document.getElementById("display");
    display.innerHTML = output;
}

function createAccountPage() {
    $("#displayUserProfileDiv").empty();
    $("#spoonacular-price-estimator").css('display', 'none');

    
    var account = {
        createAccount: true,
    }
    var template = document.getElementById("template");
    var hash = account;

    var output = Mustache.render(template.innerHTML, hash);

    var display = document.getElementById("display");
    display.innerHTML = output;
}

function toolbarToggle(login) {
    $("#displayUserProfileDiv").empty();
    $("#spoonacular-price-estimator").css('display', 'none');

    
    if (login == true) {
        //alert("Got inside toolbar toggle!");
        $("#display").empty();
        logInToolbar();
    } else {
        logOutButton();
    }
}

function displayRecipeDetails(title, length, imageURL, ingredients, steps) {
    $("#displayUserProfileDiv").empty();
    $("#spoonacular-price-estimator").css('display', 'none');

    
    var details = {
        main: true,
        login: false,
        details: false,
        name: title,
        length: length,
        image: imageURL,
        ingredients: [

        ],
        steps: [

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

function editProfilePage() {
    $("#displayUserProfileDiv").empty();
    $("#spoonacular-price-estimator").css('display', 'none');

    
    var profile = {
        editProfile: true,
    }
    var template = document.getElementById("template");
    var hash = profile;

    var output = Mustache.render(template.innerHTML, hash);

    var display = document.getElementById("display");
    display.innerHTML = output;

    //                $("#editPage").submit(function(e) {
    //                    e.preventDefault();
    //                });
}

function viewProfilePage() {
    $("#displayUserProfileDiv").empty();
    $("#spoonacular-price-estimator").css('display', 'none');
    //Getting rid of the URL parameters so that next time we search, they're replaced.
    var old_url = window.location.href;
    var new_url = old_url.substring(0, old_url.indexOf('?'));
    window.location.href = new_url;

    var profile2 = {
        viewProfile: true,
    }
    var template = document.getElementById("template");
    var hash = profile2;

    var output = Mustache.render(template.innerHTML, hash);

    var display = document.getElementById("display");
    display.innerHTML = output;

    //Pass info to the PHP function over POST. May not work yet.
    //                var form = document.createElement('form');
    //                form.setAttribute('method', 'post');
    //                form.setAttribute('action', '');
    //                form.setAttribute('value', 'viewProfile');
    //                form.style.display = 'hidden';
    //                document.body.appendChild(form)
    //                form.submit();
    //                var xhr = new XMLHttpRequest();
    //                xhr.open("POST", yourUrl, true);
    //                xhr.setRequestHeader('Content-Type', 'application/json');
    //                xhr.send(JSON.stringify({
    //                    value: value
    //                }));
    var theForm, newInput1, newInput2;
    // Start by creating a <form>
    theForm = document.createElement('form');
    theForm.action = '';
    theForm.method = 'post';
    // Next create the <input>s in the form and give them names and values
    newInput1 = document.createElement('input');
    newInput1.type = 'hidden';
    newInput1.name = 'btnSubmit';
    newInput1.value = 'viewProfile';
    //              newInput2 = document.createElement('input');
    //              newInput2.type = 'hidden';
    //              newInput2.name = 'input_2';
    //              newInput2.value = 'value 2';
    // Now put everything together...
    theForm.appendChild(newInput1);
    //theForm.appendChild(newInput2); 
    // ...and it to the DOM...
    document.getElementById('hidden_form_container').appendChild(theForm);
    // ...and submit it
    theForm.submit();
    //alert("Got here!");
}

function homePageDisplay() {
    // $("#displayUserProfileDiv").empty();
    $("#spoonacular-price-estimator").css('display', 'none');

    //            logOutButton();
    
    var state = {

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

