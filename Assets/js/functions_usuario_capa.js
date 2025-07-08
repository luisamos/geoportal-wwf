window.addEventListener('DOMContentLoaded', function () {

    if (document.querySelector("#formUsuarioCapa")) {
        const formUsuarioCapa = document.querySelector("#formUsuarioCapa");
        formUsuarioCapa.onsubmit = function (e) {
            e.preventDefault();
            const id_usuario = document.querySelector('#id_usuario').value;
            const id_capa = document.querySelector('#id_capa').value;
            if (id_usuario == '' || id_capa == '') {
                swal("Atención", "Todos los campos son obligatorios.", "error");
                return false;
            }

            const request = (window.XMLHttpRequest) ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
            const ajaxUrl = base_url + '/Usuariocapa/setUsuarioCapa';
            const formData = new FormData(formUsuarioCapa);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formUsuarioCapa.reset();
                        Fancybox.close();
                        new Toast(objData.msg);
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }
                    else {
                        new Toast("Error: " + objData.msg);
                    }
                }
                return false;
            }
        }
    }

    if (document.querySelector("#formUsuarioCapaDel")) {
        let formUsuarioCapaDel = document.querySelector("#formUsuarioCapaDel");
        formUsuarioCapaDel.onsubmit = function (e) {
            e.preventDefault();
            let request = (window.XMLHttpRequest) ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Usuariocapa/delUsuarioCapa';
            let formData = new FormData(formUsuarioCapaDel);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formUsuarioCapaDel.reset();
                        Fancybox.close();
                        new Toast(objData.msg);
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }
                    else {
                        new Toast("Error: " + objData.msg);
                    }
                }
                return false;
            }
        }
    }

}, false);


function fntSearchUsuario() {
    if (document.querySelector('#nom_usuario')) {

        const nom_usuario = document.querySelector('#nom_usuario').value;

        if (nom_usuario === '') {
            new Toast("Ingrese el nombre del usuario");
        }
        else {
            let ajaxUrl = base_url + '/Usuariocapa/getUsuario_nombre/' + nom_usuario;
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            request.open("GET", ajaxUrl, true);
            request.send();
            request.onreadystatechange = function () {

                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);

                    if (objData.status) {
                        document.querySelector("#id_usuario").value = objData.data.id_usuario;
                        document.querySelector("#usuario").value = objData.data.usuari_nombre;
                        document.querySelector("#persona").value = objData.data.person_nombres + ' ' + objData.data.person_apellidos;
                    }
                    else {
                        new Toast(objData.msg);
                    }
                }
            }
        }
    }
}


function fntSearchCapa() {
    if (document.querySelector('#nombre')) {

        const nombre = document.querySelector('#nombre').value;

        if (nombre === '') {
            new Toast("Ingrese el nombre de la capa");
        }
        else {
            let ajaxUrl = base_url + '/Usuariocapa/getCapa_nombre/' + nombre;
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            request.open("GET", ajaxUrl, true);
            request.send();
            request.onreadystatechange = function () {

                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);

                    if (objData.status) {
                        document.querySelector("#id_capa").value = objData.data.id_capa;
                        document.querySelector("#capa").value = objData.data.capa_nombre;
                    }
                    else {
                        new Toast(objData.msg);
                    }
                }
            }
        }
    }
}

function openModalDel() {
    const check = document.querySelector('input[name="ckUsuarioCapa"]:checked');
    if (!check) {
        new Toast('Seleccione un Usuario');
        return;
    }

    document.getElementById('id_usuario_capa_del').value = check.dataset.id;
    new Fancybox([{ src: "#modalFormUsuarioCapaDel" }]);
}

function openModal() {

    document.querySelector('#titleModal').innerHTML = "Nuevo Permiso Capa";
    document.querySelector("#formUsuarioCapa").reset();
}