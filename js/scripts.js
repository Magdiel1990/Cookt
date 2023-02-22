if (window.history.replaceState) { // verificamos disponibilidad
    window.history.replaceState(null, null, window.location.href);
}


function validation(){
    let ingredient = document.getElementById("add_ingredient").value;
    let regExpText = /[a-zA-Z\t\h]+|(^$)/;  
    if (!regExpText.test(ingredient)) {
        alert("¡Texto incorrecto!");
        return false; 
    }
    return true;
}


function validation(){
    let ingredient = document.getElementById("add_units").value;
    let regExpText = /[a-zA-Z\t\h]+|(^$)/;  
    if (!regExpText.test(ingredient)) {
        alert("¡Texto incorrecto!");
        return false; 
    }
    return true;
}


/*
function validation(type, regExpText){
    let input = document.getElementById("'" + type + "'").value;
    if (!regExpText.test(input)) {
        alert("¡Texto incorrecto!");
        return false; 
    }
    return true;
}
*/