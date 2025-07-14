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

                // Tamaño máximo permitido en bytes (40 MB)
                const maxSizeBytes = 40 * 1024 * 1024;

                if (type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png' && type !== 'video/mp4') {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';
                    if (document.querySelector('#img')) {
                        document.querySelector('#img').remove();
                    }
                    document.querySelector('.delPhoto').classList.add("notBlock");
                    archivo.value = "";
                    return false;
                }
                else if (size > maxSizeBytes) {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo es demasiado grande (máximo 40 MB).</p>';
                    if (document.querySelector('#img')) {
                        document.querySelector('#img').remove();
                    }
                    document.querySelector('.delPhoto').classList.add("notBlock");
                    archivo.value = "";
                    return false;
                }
                else {
                    contactAlert.innerHTML = '';
                    if (document.querySelector('#img')) {
                        document.querySelector('#img').remove();
                    }
                    document.querySelector('.delPhoto').classList.remove("notBlock");
                    let objeto_url = nav.createObjectURL(this.files[0]);
                    document.querySelector('.prevPhoto div').innerHTML = "<img id='img' src=" + objeto_url + ">";
                }
            }
            else {
                alert("No seleccionó el archivo");
                if (document.querySelector('#img')) {
                    document.querySelector('#img').remove();
                }
            }
        }
    }

    if (document.querySelector(".delPhoto")) {
        let delPhoto = document.querySelector(".delPhoto");
        delPhoto.onclick = function (e) {
            document.querySelector("#archivo_remove").value = 1;
            removeArchivo();
        }
    }

    if (document.querySelector("#formArchivosCampo")) {

        let formArchivosCampo = document.querySelector("#formArchivosCampo");
        formArchivosCampo.onsubmit = function (e) {
            e.preventDefault();
            let intIdTipo = document.querySelector('#id_tipo').value;
            let strNombre = document.querySelector('#nombre').value;

            if (intIdTipo == 0 || strNombre == '') {
                new Toast("Todos los campos son obligatorios.");
                return false;
            }
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Garchivoscampo/setArchivo';
            let formData = new FormData(formArchivosCampo);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formArchivosCampo.reset();
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

    if (document.querySelector("#formArchivosCampoDel")) {
        let formArchivosCampoDel = document.querySelector("#formArchivosCampoDel");
        formArchivosCampoDel.onsubmit = function (e) {
            e.preventDefault();
            let request = (window.XMLHttpRequest) ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Garchivoscampo/delArchivo';
            let formData = new FormData(formArchivosCampoDel);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formArchivosCampoDel.reset();
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

    document.getElementById('id_archivo_campo').value = check.dataset.id;
    let id_archivo_campo = check.dataset.id;
    document.querySelector('#titleModal').innerHTML = "Actualizar Archivo";

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Garchivoscampo/getArchivo/' + id_archivo_campo;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);

            if (objData.status) {
                document.querySelector("#id_tipo").value = objData.data.arccam_id_tipo;
                document.querySelector("#nombre").value = objData.data.arccam_nombre;
                document.querySelector('#archivo_actual').value = objData.data.arccam_archivo;
                document.querySelector("#archivo_remove").value = 0;

                let media = document.querySelector('#media').value;

                if (document.querySelector('#img')) {
                    document.querySelector('#img').src = objData.data.arccam_archivo;
                }
                else {
                    document.querySelector('.prevPhoto div').innerHTML = "<img id='img' src=" + media + "/" + objData.data.arccam_archivo + ">";
                }

                new Fancybox([{ src: "#modalFormArchivosCampo" }]);
            }
            else {
                new Toast("Error: " + objData.msg);
            }
        }
    }
}

function removeArchivo() {
    document.querySelector('#archivo').value = "";
    document.querySelector('.delPhoto').classList.add("notBlock");
    if (document.querySelector('#img')) {
        document.querySelector('#img').remove();
    }
}

function fntLoadTipos() {
    if (document.querySelector('select[name="id_tipo"]')) {
        let ajaxUrl = base_url + '/Garchivoscampo/getTipos';
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

    document.getElementById('id_archivo_campo_del').value = check.dataset.id;
    new Fancybox([{ src: "#modalFormArchivosCampoDel" }]);
}

function openModal() {
    document.querySelector('#id_archivo_campo').value = "";
    document.querySelector('#titleModal').innerHTML = "Nuevo Archivo";
    document.querySelector("#formArchivosCampo").reset();
    removeArchivo();
}