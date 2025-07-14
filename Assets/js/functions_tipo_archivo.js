document.addEventListener('DOMContentLoaded', function () {

    if (document.querySelector("#formTipo_Archivo")) {
        var formTipo_Archivo = document.querySelector("#formTipo_Archivo");
        formTipo_Archivo.onsubmit = function (e) {
            e.preventDefault();

            var strNombre = document.querySelector('#nombre').value;
            if (strNombre == '') {
                swal("Atención", "Todos los campos son obligatorios.", "error");
                return false;
            }
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Tipoarchivo/setTipo_Archivo';
            var formData = new FormData(formTipo_Archivo);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formTipo_Archivo.reset();
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

    if (document.querySelector("#formTipo_ArchivoDel")) {
        let formTipo_ArchivoDel = document.querySelector("#formTipo_ArchivoDel");
        formTipo_ArchivoDel.onsubmit = function (e) {
            e.preventDefault();
            let request = (window.XMLHttpRequest) ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Tipoarchivo/delTipo_Archivo';
            let formData = new FormData(formTipo_ArchivoDel);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formTipo_ArchivoDel.reset();
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

    const check = document.querySelector('input[name="ckTipoArchivos"]:checked');
    if (!check) {
        new Toast('Seleccione un Tipo Archivo');
        return;
    }

    document.getElementById('id_tipo_archivo').value = check.dataset.id;
    let id_tipo_archivo = check.dataset.id;
    document.querySelector('#titleModal').innerHTML = "Actualizar Tipo Archivo";

    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Tipoarchivo/getTipo_Archivo/' + id_tipo_archivo;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {

        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#id_tipo_archivo").value = objData.data.id_tipo_archivo;
                document.querySelector("#nombre").value = objData.data.tiparc_nombre;
                new Fancybox([{ src: "#modalFormTipo_Archivo" }]);
            }
            else {
                new Toast("Error: " + objData.msg);
            }
        }
    }
}





function openModalDel() {
    const check = document.querySelector('input[name="ckTipoArchivos"]:checked');
    if (!check) {
        new Toast('Seleccione un Tipo Archivo');
        return;
    }

    document.getElementById('id_tipo_archivo_del').value = check.dataset.id;
    new Fancybox([{ src: "#modalFormTipo_ArchivoDel" }]);
}

function openModal() {
    document.querySelector('#id_tipo_archivo').value = "";
    document.querySelector('#titleModal').innerHTML = "Nuevo Tipo de Archivo";
    document.querySelector("#formTipo_Archivo").reset();
}