window.addEventListener('DOMContentLoaded', function () {

    if (document.querySelector("#formUsuarios")) {
        const formUsuarios = document.querySelector("#formUsuarios");
        formUsuarios.onsubmit = function (e) {
            e.preventDefault();
            const strPersona = document.querySelector('#persona').value;
            const strNombre = document.querySelector('#usuario').value;
            if (strPersona == '' || strNombre == '') {
                swal("Atención", "Todos los campos son obligatorios.", "error");
                return false;
            }

            const request = (window.XMLHttpRequest) ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
            const ajaxUrl = base_url + '/Usuarios/setUsuario';
            const formData = new FormData(formUsuarios);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formUsuarios.reset();
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

    if (document.querySelector("#formUsuariosDel")) {
        let formUsuariosDel = document.querySelector("#formUsuariosDel");
        formUsuariosDel.onsubmit = function (e) {
            e.preventDefault();
            let request = (window.XMLHttpRequest) ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Usuarios/delUsuario';
            let formData = new FormData(formUsuariosDel);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formUsuariosDel.reset();
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

window.addEventListener('load', function () {
    fntRolesUsuario();
}, false);

function fntRolesUsuario() {
    if (document.querySelector('#id_rol')) {
        let ajaxUrl = base_url + '/Roles/getSelectRoles';
        let request = (window.XMLHttpRequest) ?
            new XMLHttpRequest() :
            new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                document.querySelector('#id_rol').innerHTML = request.responseText;
            }
        }
    }
}

function fntEditInfo() {
    const check = document.querySelector('input[name="ckUsuarios"]:checked');
    if (!check) {
        new Toast('seleccione un Usuario');
        return;
    }

    document.getElementById('id_usuario').value = check.dataset.id;
    let id_usuario = check.dataset.id;
    document.querySelector('#titleModal').innerHTML = "Actualizar Usuario";

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Usuarios/getUsuario/' + id_usuario;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {

        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);

            if (objData.status) {
                document.querySelector("#id_usuario").value = objData.data.id_usuario;
                document.querySelector("#id_persona").value = objData.data.usuari_id_persona;
                document.querySelector("#num_documento").value = objData.data.person_num_documento;
                document.querySelector("#persona").value = objData.data.person_nombres + ' ' + objData.data.person_apellidos;
                document.querySelector("#usuario").value = objData.data.usuari_nombre;
                document.querySelector("#id_rol").value = objData.data.usuari_id_rol;

                new Fancybox([{ src: "#modalFormUsuarios" }]);
            }
            else {
                new Toast("Error: " + objData.msg);
            }
        }
    }
}

function fntSearchPersona() {
    if (document.querySelector('#num_documento')) {

        const num_documento = document.querySelector('#num_documento').value;

        if (num_documento === '') {
            new Toast("Ingrese el DNI de la persona a Asignar Usuario");
        }
        else {
            let ajaxUrl = base_url + '/Personas/getPersona_NumDocumento/' + num_documento;
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            request.open("GET", ajaxUrl, true);
            request.send();
            request.onreadystatechange = function () {

                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);

                    if (objData.status) {
                        document.querySelector("#id_persona").value = objData.data.id_persona;
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

function openModalDel() {
    const check = document.querySelector('input[name="ckUsuarios"]:checked');
    if (!check) {
        new Toast('Seleccione un Usuario');
        return;
    }

    document.getElementById('id_usuario_del').value = check.dataset.id;
    new Fancybox([{ src: "#modalFormUsuariosDel" }]);
}

function openModal() {
    document.querySelector('#id_usuario').value = "";
    document.querySelector('#titleModal').innerHTML = "Nuevo Usuario";
    document.querySelector("#formUsuarios").reset();
}

function habilitarUsuario() {
    const check = document.querySelector('input[name="ckUsuarios"]:checked');
    if (!check) {
        new Toast('seleccione un Usuario');
        return;
    }

    const id_usuario_hab = check.dataset.id;
    if (id_usuario_hab != 0) {
        let ajaxUrl = base_url + '/Usuarios/habilitar/' + id_usuario_hab;
        let request = (window.XMLHttpRequest) ?
            new XMLHttpRequest() :
            new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                let objData = JSON.parse(request.responseText);
                if (objData.status) {
                    new Toast("Correcto: " + objData.msg);
                }
                else {
                    new Toast("Error: " + objData.msg);
                }

                setTimeout(function () {
                    window.location.reload();
                }, 2000);
            }
        }
    }
}