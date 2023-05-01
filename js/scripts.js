//Function to avoid resubmitting forms
if (window.history.replaceState) { 
    window.history.replaceState(null, null, window.location.href);
}

//Validation of inputs
function validation(type, regExpText){

    var input = document.getElementById(type).value;

    if (!regExpText.test(input)) {
        alert("¡Texto incorrecto!");
        return false; 
    }

    if(input.length == 0){
        alert("¡No has escrito nada!");
        return false; 
    }

    return true;
}

function validationNumberText(number, text, regExpText){

    let numberValue = document.getElementById(number).value;
    let textValue = document.getElementById(text).value;

    if (!regExpText.test(textValue)) {
        alert("¡Texto incorrecto!");
        return false; 
    }
    if (numberValue < 5 || numberValue > 180 || numberValue == "") {
        alert("¡El tiempo de cocción debe estar entre 5-180 minutos!");
        return false; 
    }

    return true;
}

function validationNumber(number){

    let numberValue = document.getElementById(number).value;

    if (numberValue <= 0 || numberValue == "") {
        alert("¡La cantidad incorrecta!");
        return false; 
    }

    return true;
}

//Recipe addition validation method              
function add_recipe_validation() {
//Form   
    form = document.getElementById("add_recipe_form");
    form.addEventListener("submit", function(event){

    var regExp = /[a-zA-Z\t\h]+|(^$)/;
    var recipename = document.getElementById("recipename").value;
    var cookingtime = document.getElementById("cookingtime").value;
    var ingredients = document.getElementById("ingredients").value;
    var preparation = document.getElementById("preparation").value;
    var recipeImage = document.getElementById("recipeImage");
    var message = document.getElementById("message");
    var file = recipeImage.files[0];
    var weight = file.size;
    var fileType = file.type;
    var allowedImageTypes = ["image/jpeg", "image/gif", "image/png", "image/jpg"];


//Conditions
    if(recipename == "" || preparation == "" || ingredients == ""){
        event.preventDefault();
        message.innerHTML = "Completar los campos requeridos";             
        return false;
    }
//Regular Expression    
    if(!recipename.match(regExp)){
        event.preventDefault();
        message.innerHTML = "¡Nombre de receta incorrecto!";                 
        return false;
    }
//Cooking time parameters    
    if(cookingtime > 180 || cookingtime < 5){
        event.preventDefault();
        message.innerHTML = "¡Tiempo de cocción debe estar entre 5 - 180 minutos!";  
        return false;
    }      
    if (recipeImage.value != "") {
//Size in Bytes     
        if(weight > 300000) {
            event.preventDefault();
            message.innerHTML = "¡El tamaño de la imagen debe ser menor que 300 KB!";  
            return false;
        }       
//Image format validation
        if(!allowedImageTypes.includes(fileType)){
            event.preventDefault();
            message.innerHTML = "¡Formatos de imagen admitidos: jpg, png y gif!";
            return false;
        }
    }
        return true;                           
    })
}           