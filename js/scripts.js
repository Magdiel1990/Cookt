if (window.history.replaceState) { // verificamos disponibilidad
    window.history.replaceState(null, null, window.location.href);
}


function validation(type, regExpText){

    let input = document.getElementById(type).value;

    if (!regExpText.test(input)) {
        alert("¡Texto incorrecto!");
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
    else if (numberValue < 5 || numberValue > 180 || numberValue == "") {
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


/*function deleteMessage (){
    alert("¿Está seguro de eliminar?");
    return true;
}*/