document.addEventListener('DOMContentLoaded', function(){

	if(document.querySelector("#formPersonas")){
        const formPersonas = document.querySelector("#formPersonas");
        formPersonas.onsubmit = function(e) {
            e.preventDefault();
            const strNum_documento = document.querySelector('#num_documento').value;
            const strNombres = document.querySelector('#nombres').value;
            const strApellidos = document.querySelector('#apellidos').value;
            const strCelular = document.querySelector('#celular').value;
            const strEmail = document.querySelector('#email').value;

            if(strNum_documento == '' || strApellidos == '' || strNombres == '' || strCelular == '' || strEmail == '')
            {
                new Toast("Todos los campos son obligatorios.");
                return false;
            }

            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Personas/setPersona'; 
            let formData = new FormData(formPersonas);
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        formPersonas.reset();
                        Fancybox.close();
                        new Toast(objData.msg);
                        setTimeout(function(){
                            window.location.reload()
                        }, 2000);
                    }
                    else{
                        new Toast("Error: "+objData.msg);
                    }     
                }
                return false;
            }
        }
    }

    if(document.querySelector("#formPersonasDel")){
        let formPersonasDel = document.querySelector("#formPersonasDel");
        formPersonasDel.onsubmit = function(e) {
            e.preventDefault();
            let request = (window.XMLHttpRequest) ? 
                            new XMLHttpRequest() : 
                            new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Personas/delPersona'; 
            let formData = new FormData(formPersonasDel);
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        formPersonasDel.reset();
                        Fancybox.close();
                        new Toast(objData.msg);
                        setTimeout(function(){
                            window.location.reload()
                        }, 2000);
                    }
                    else{
                        new Toast("Error: "+objData.msg);
                    }
                }
                return false;
            }
        }
    }

}, false);


function fntEditInfo(){
    const check = document.querySelector('input[name="ckPersonas"]:checked');
    if(!check){
      new Toast('seleccione una Persona');
      return;
    }

    document.getElementById('id_persona').value = check.dataset.id;
    let id_persona = check.dataset.id;
    document.querySelector('#titleModal').innerHTML ="Actualizar Persona";

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Personas/getPersona/'+id_persona;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){

        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            if(objData.status)
            {
                document.querySelector("#id_persona").value = objData.data.id_persona;
                document.querySelector("#num_documento").value = objData.data.person_num_documento;
                document.querySelector("#nombres").value = objData.data.person_nombres;
                document.querySelector("#apellidos").value = objData.data.person_apellidos;
                document.querySelector("#celular").value = objData.data.person_celular;
                document.querySelector("#email").value = objData.data.person_email;
                new Fancybox([{ src: "#modalFormPersonas" }]);
            }
            else{
                new Toast("Error: "+objData.msg);
            }
        }
    }
}

function openModalDel(){
    const check = document.querySelector('input[name="ckPersonas"]:checked');
    if(!check){
      new Toast('Seleccione una Persona');
      return;
    }

    document.getElementById('id_persona_del').value = check.dataset.id;
    new Fancybox([{ src: "#modalFormPersonasDel" }]);
}

function openModal(){
    document.querySelector('#id_persona').value ="";
    document.querySelector('#titleModal').innerHTML = "Nueva Persona";
    document.querySelector("#formPersonas").reset();
}