document.addEventListener('DOMContentLoaded', function () {

    fntLoadTipos();

    if (document.querySelector("#archivo")) {
        let archivo = document.querySelector("#archivo");
        archivo.onchange = function (e) {

            let uploadarchivo = document.querySelector("#archivo").value;
            let fileimg = document.querySelector("#archivo").files;
            let nav = window.URL || window.webkitURL;
            let contactAlert = document.querySelector('#form_alert');
            if (uploadarchivo != '') {
                let type = fileimg[0].type;
                let name = fileimg[0].name;
                let size = fileimg[0].size;
                let ext = (/[.]/.exec(name)) ? /[^.]+$/.exec(name)[0] : undefined;

                if (type != 'application/pdf' && type != 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' && type != 'application/msword' && ext != 'rar') {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';
                    archivo.value = "";
                    return false;
                }
                else {
                    contactAlert.innerHTML = '';
                }
            }
            else {
                alert("No seleccionó el archivo");
            }
        }
    }


    if (document.querySelector("#formArchivos")) {

        let formArchivos = document.querySelector("#formArchivos");
        formArchivos.onsubmit = function (e) {
            e.preventDefault();
            let intIdTipo = document.querySelector('#id_tipo').value;
            let strCodigo = document.querySelector('#codigo').value;
            let strNombre = document.querySelector('#nombre').value;
            let intAcceso = document.querySelector('#acceso').value;

            if (intIdTipo == 0 || strCodigo == '' || strNombre == '' || intAcceso == '') {
                new Toast("Todos los campos son obligatorios.");
                return false;
            }
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Garchivos/setArchivo';
            let formData = new FormData(formArchivos);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formArchivos.reset();
                        Fancybox.close();
                        removeArchivo();
                        new Toast(objData.msg);
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    } else {
                        new Toast("Error: " + objData.msg);
                    }
                }
                return false;
            }
        }
    }

    if (document.querySelector("#formArchivosDel")) {
        let formArchivosDel = document.querySelector("#formArchivosDel");
        formArchivosDel.onsubmit = function (e) {
            e.preventDefault();
            let request = (window.XMLHttpRequest) ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Garchivos/delArchivo';
            let formData = new FormData(formArchivosDel);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formArchivosDel.reset();
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


function fntEditInfo() {

    const check = document.querySelector('input[name="ckArchivos"]:checked');
    if (!check) {
        new Toast('seleccione un Archivo');
        return;
    }

    document.getElementById('id_archivo').value = check.dataset.id;
    let id_archivo = check.dataset.id;
    document.querySelector('#titleModal').innerHTML = "Actualizar Archivo";

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Garchivos/getArchivo/' + id_archivo;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);

            if (objData.status) {
                document.querySelector("#id_tipo").value = objData.data.archiv_id_tipo;
                document.querySelector("#codigo").value = objData.data.archiv_codigo;
                document.querySelector("#nombre").value = objData.data.archiv_nombre;
                document.querySelector('#archivo_actual').value = objData.data.archiv_archivo;
                document.querySelector("#archivo_remove").value = 0;
                document.querySelector("#acceso").value = objData.data.archiv_acceso;
                new Fancybox([{ src: "#modalFormArchivos" }]);
            }
            else {
                new Toast("Error: " + objData.msg);
            }
        }
    }
}

function removeArchivo() {
    document.querySelector('#archivo').value = "";
}

function fntLoadTipos() {
    if (document.querySelector('select[name="id_tipo"]')) {
        let ajaxUrl = base_url + '/Garchivos/getTipos';
        let request = (window.XMLHttpRequest) ?
            new XMLHttpRequest() :
            new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                document.querySelector('select[name="id_tipo"]').innerHTML = request.responseText;
                //$('#id_tipo').selectpicker('render');
                //$('#id_tipo').val("").trigger('change');
            }
        }
    }
}

function openModalDel() {
    const check = document.querySelector('input[name="ckArchivos"]:checked');
    if (!check) {
        new Toast('Seleccione un Archivo');
        return;
    }

    document.getElementById('id_archivo_del').value = check.dataset.id;
    new Fancybox([{ src: "#modalFormArchivosDel" }]);
}

function openModal() {
    document.querySelector('#id_archivo').value = "";
    document.querySelector('#titleModal').innerHTML = "Nuevo Archivo";
    document.querySelector("#formArchivos").reset();
    removeArchivo();
}