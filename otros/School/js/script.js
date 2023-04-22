//Código para la subida de archivos.
    document.querySelectorAll(".drop-zone__input").forEach((inputElement) => {
        const dropZoneElement = inputElement.closest(".drop-zone");
    dropZoneElement.addEventListener("click", (e) => {
        inputElement.click();
        });
    inputElement.addEventListener("change", (e) => {
        if (inputElement.files.length) {
            updateThumbnail(dropZoneElement, inputElement.files[0]);
        }
        });
    dropZoneElement.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropZoneElement.classList.add("drop-zone--over");
        });
    ["dragleave", "dragend"].forEach((type) => {
        dropZoneElement.addEventListener(type, (e) => {
            dropZoneElement.classList.remove("drop-zone--over");
        });
        });
    dropZoneElement.addEventListener("drop", (e) => {
        e.preventDefault();
    if (e.dataTransfer.files.length) {
            inputElement.files = e.dataTransfer.files;
            updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
        }
    dropZoneElement.classList.remove("drop-zone--over");
        });
    });
    function updateThumbnail(dropZoneElement, file) {
        let thumbnailElement = dropZoneElement.querySelector(".drop-zone__thumb");
    // First time - remove the prompt
        if (dropZoneElement.querySelector(".drop-zone__prompt")) {
        dropZoneElement.querySelector(".drop-zone__prompt").remove();
        }
    // First time - there is no thumbnail element, so lets create it
        if (!thumbnailElement) {
        thumbnailElement = document.createElement("div");
        thumbnailElement.classList.add("drop-zone__thumb");
        dropZoneElement.appendChild(thumbnailElement);
        }
    thumbnailElement.dataset.label = file.name;
    // Show thumbnail for image files
        if (file.type.startsWith("./img/")) {
        const reader = new FileReader();
    reader.readAsDataURL(file);
        reader.onload = () => {
            thumbnailElement.style.backgroundImage = `url('${reader.result}')`;
        };
        } else {
        thumbnailElement.style.backgroundImage = null;
        }
    }

    //Código para que se seleccionen todos los checkbox de los archivos a eliminar.
    function toggle(source) {
        checkboxes = document.getElementsByName('archivos[]');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    //Código para abrir y cerrar la ventana emergente.
    document.getElementById("button").addEventListener("click",function(){
        document.querySelector(".popup_subcontainer").style.display = "flex";
    })
    document.querySelector(".close").addEventListener("click", function(){
        document.querySelector(".popup_subcontainer").style.display = "none";
    })

    //Script para eliminar los datos post almacenados en el navegador después de enviar un formulario.
    if(window.history.replaceState){
        window.history.replaceState(null,null,window.location.href);
    }