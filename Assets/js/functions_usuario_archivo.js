window.addEventListener('DOMContentLoaded', function () {

    if (document.querySelector("#formUsuarioArchivo")) {
        const formUsuarioArchivo = document.querySelector("#formUsuarioArchivo");
        formUsuarioArchivo.onsubmit = function (e) {
            e.preventDefault();
            const id_usuario = document.querySelector('#id_usuario').value;
            const id_archivo = document.querySelector('#id_archivo').value;
            if (id_usuario == '' || id_archivo == '') {
                swal("Atención", "Todos los campos son obligatorios.", "error");
                return false;
            }

            const request = (window.XMLHttpRequest) ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
            const ajaxUrl = base_url + '/Usuarioarchivo/setUsuarioArchivo';
            const formData = new FormData(formUsuarioArchivo);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formUsuarioArchivo.reset();
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

    if (document.querySelector("#formUsuarioArchivoDel")) {
        let formUsuarioArchivoDel = document.querySelector("#formUsuarioArchivoDel");
        formUsuarioArchivoDel.onsubmit = function (e) {
            e.preventDefault();
            let request = (window.XMLHttpRequest) ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Usuarioarchivo/delUsuarioArchivo';
            let formData = new FormData(formUsuarioArchivoDel);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formUsuarioArchivoDel.reset();
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
            let ajaxUrl = base_url + '/Usuarioarchivo/getUsuario_nombre/' + nom_usuario;
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


function fntSearchArchivo() {
    if (document.querySelector('#codigo')) {

        const codigo = document.querySelector('#codigo').value;

        if (codigo === '') {
            new Toast("Ingrese el codigo del archivo");
        }
        else {
            let ajaxUrl = base_url + '/Usuarioarchivo/getArchivo_codigo/' + codigo;
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            request.open("GET", ajaxUrl, true);
            request.send();
            request.onreadystatechange = function () {

                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);

                    if (objData.status) {
                        document.querySelector("#id_archivo").value = objData.data.id_archivo;
                        document.querySelector("#archivo").value = objData.data.archiv_nombre;
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
    const check = document.querySelector('input[name="ckUsuarioArchivo"]:checked');
    if (!check) {
        new Toast('Seleccione un Usuario');
        return;
    }

    document.getElementById('id_usuario_archivo_del').value = check.dataset.id;
    new Fancybox([{ src: "#modalFormUsuarioArchivoDel" }]);
}

function openModal() {

    document.querySelector('#titleModal').innerHTML = "Nuevo Permiso Archivo";
    document.querySelector("#formUsuarioArchivo").reset();
}