document.addEventListener('DOMContentLoaded', function () {

    //NUEVO ROL
    if (document.querySelector("#formRoles")) {
        var formRoles = document.querySelector("#formRoles");
        formRoles.onsubmit = function (e) {
            e.preventDefault();

            var strNombre = document.querySelector('#nombre').value;
            var strDescripcion = document.querySelector('#descripcion').value;
            if (strNombre == '' || strDescripcion == '') {
                swal("Atención", "Todos los campos son obligatorios.", "error");
                return false;
            }
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Roles/setRol';
            var formData = new FormData(formRoles);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formRoles.reset();
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

    if (document.querySelector("#formRolesDel")) {
        let formRolesDel = document.querySelector("#formRolesDel");
        formRolesDel.onsubmit = function (e) {
            e.preventDefault();
            let request = (window.XMLHttpRequest) ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Roles/delRol';
            let formData = new FormData(formRolesDel);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formRolesDel.reset();
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

});


function fntEditInfo() {

    const check = document.querySelector('input[name="ckRoles"]:checked');
    if (!check) {
        new Toast('seleccione un Rol');
        return;
    }

    document.getElementById('id_rol').value = check.dataset.id;
    let id_rol = check.dataset.id;
    document.querySelector('#titleModal').innerHTML = "Actualizar Rol";

    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Roles/getRol/' + id_rol;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {

        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#id_rol").value = objData.data.id_rol;
                document.querySelector("#nombre").value = objData.data.rol_nombre;
                document.querySelector("#descripcion").value = objData.data.rol_descripcion;
                new Fancybox([{ src: "#modalFormRoles" }]);
            }
            else {
                new Toast("Error: " + objData.msg);
            }
        }
    }
}

function fntPermisos() {

    const check = document.querySelector('input[name="ckRoles"]:checked');
    if (!check) {
        new Toast('seleccione un Rol');
        return;
    }

    document.getElementById('id_rol').value = check.dataset.id;
    const id_rol = check.dataset.id;

    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Permisos/getPermisosRol/' + id_rol;
    request.open("GET", ajaxUrl, true);
    request.send();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            document.querySelector('#contentAjax').innerHTML = request.responseText;
            new Fancybox([{ src: "#modalPermisos" }]);
            document.querySelector('#formPermisos').addEventListener('submit', fntSavePermisos, false);
        }
    }
}

function fntSavePermisos(event) {
    event.preventDefault();
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Permisos/setPermisos';
    var formElement = document.querySelector("#formPermisos");
    var formData = new FormData(formElement);
    request.open("POST", ajaxUrl, true);
    request.send(formData);

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                Fancybox.close();
                new Toast(objData.msg);
                setTimeout(function () {
                    window.location.reload();
                }, 2000);
            } else {
                new Toast("Error: " + objData.msg);
            }
        }
    }
}

function openModalDel() {
    const check = document.querySelector('input[name="ckRoles"]:checked');
    if (!check) {
        new Toast('Seleccione un Rol');
        return;
    }

    document.getElementById('id_rol_del').value = check.dataset.id;
    new Fancybox([{ src: "#modalFormRolesDel" }]);
}

function openModal() {
    document.querySelector('#id_rol').value = "";
    document.querySelector('#titleModal').innerHTML = "Nuevo Rol";
    document.querySelector("#formRoles").reset();
}