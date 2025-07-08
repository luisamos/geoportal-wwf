document.addEventListener('DOMContentLoaded', function () {

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
                    contactAlert.innerHTML = '<p>El archivo no es valido.</p>';
                    archivo.value = "";
                    return false;
                }
                else {
                    contactAlert.innerHTML = '';
                }
            }
            else {
                alert("No seleccion� el archivo");
            }
        }
    }

    if (document.querySelector("#foto")) {
        let foto = document.querySelector("#foto");
        foto.onchange = function (e) {
            let uploadFoto = document.querySelector("#foto").value;
            let fileimg = document.querySelector("#foto").files;
            let nav = window.URL || window.webkitURL;

            let contactAlert = document.querySelector('#form_alert');

            if (uploadFoto != '') {
                let type = fileimg[0].type;
                let name = fileimg[0].name;
                if (type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png') {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';
                    if (document.querySelector('#img')) {
                        document.querySelector('#img').remove();
                    }
                    document.querySelector('.delPhoto').classList.add("notBlock");
                    foto.value = "";
                    return false;
                } else {
                    contactAlert.innerHTML = '';
                    if (document.querySelector('#img')) {
                        document.querySelector('#img').remove();
                    }
                    document.querySelector('.delPhoto').classList.remove("notBlock");
                    let objeto_url = nav.createObjectURL(this.files[0]);
                    document.querySelector('.prevPhoto div').innerHTML = "<img id='img' src=" + objeto_url + ">";
                }
            } else {
                alert("No seleccionó foto");
                if (document.querySelector('#img')) {
                    document.querySelector('#img').remove();
                }
            }
        }
    }

    if (document.querySelector(".delPhoto")) {
        let delPhoto = document.querySelector(".delPhoto");
        delPhoto.onclick = function (e) {
            document.querySelector("#foto_remove").value = 1;
            removePhoto1();
        }
    }

    if (document.querySelector("#formPlanoteca")) {

        let formPlanoteca = document.querySelector("#formPlanoteca");
        formPlanoteca.onsubmit = function (e) {
            e.preventDefault();
            let intIdTipo = document.querySelector('#id_programa').value;
            let strCodigo = document.querySelector('#codigo').value;
            let strNombre = document.querySelector('#nombre').value;

            if (intIdTipo == 0 || strCodigo == '' || strNombre == '') {
                new Toast("Todos los campos son obligatorios.");
                return false;
            }
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Gplanoteca/setPlanoteca';
            let formData = new FormData(formPlanoteca);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formPlanoteca.reset();
                        Fancybox.close();
                        removeArchivo();
                        removePhoto1();
                        new Toast(objData.msg);
                        //setTimeout(function(){
                        //    window.location.reload();
                        //}, 2000);
                    } else {
                        new Toast("Error: " + objData.msg);
                    }
                }
                return false;
            }
        }
    }

    if (document.querySelector("#formPlanotecaDel")) {
        let formPlanotecaDel = document.querySelector("#formPlanotecaDel");
        formPlanotecaDel.onsubmit = function (e) {
            e.preventDefault();
            let request = (window.XMLHttpRequest) ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Gplanoteca/delPlanoteca';
            let formData = new FormData(formPlanotecaDel);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formPlanotecaDel.reset();
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
    fntLoadProgramas();
}, false);

function fntEditInfo() {

    const check = document.querySelector('input[name="ckPlanoteca"]:checked');
    if (!check) {
        new Toast('seleccione un Plano');
        return;
    }

    document.getElementById('id_planoteca').value = check.dataset.id;
    let id_planoteca = check.dataset.id;
    document.querySelector('#titleModal').innerHTML = "Actualizar Plano";

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Gplanoteca/getPlanoteca/' + id_planoteca;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);

            if (objData.status) {
                document.querySelector("#id_programa").value = objData.data.planot_id_tipo;
                document.querySelector("#codigo").value = objData.data.planot_codigo;
                document.querySelector("#nombre").value = objData.data.planot_nombre;
                document.querySelector("#tag").value = objData.data.planot_tag;

                document.querySelector('#archivo_actual').value = objData.data.planot_archivo;
                document.querySelector("#archivo_remove").value = 0;

                document.querySelector('#foto_actual').value = objData.data.planot_img;
                document.querySelector("#foto_remove").value = 0;

                if (document.querySelector('#img')) {
                    document.querySelector('#img').src = base_url + '/Assets/images/uploads/' + objData.data.planot_img;
                }
                else {
                    document.querySelector('.prevPhoto div').innerHTML = "<img id='img' src=" + base_url + '/Assets/images/uploads/' + objData.data.planot_img + ">";
                }

                new Fancybox([{ src: "#modalFormPlanoteca" }]);
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

function removePhoto1() {
    document.querySelector('#foto').value = "";
    document.querySelector('.delPhoto').classList.add("notBlock");
    if (document.querySelector('#img')) {
        document.querySelector('#img').remove();
    }
}

function fntLoadProgramas() {
    if (document.querySelector('select[name="id_programa"]')) {
        let ajaxUrl = base_url + '/Gplanoteca/getProgramas';
        let request = (window.XMLHttpRequest) ?
            new XMLHttpRequest() :
            new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                document.querySelector('select[name="id_programa"]').innerHTML = request.responseText;
                //$('#id_programa').selectpicker('render');
                //$('#id_programa').val("").trigger('change');
            }
        }
    }
}

function openModalDel() {
    const check = document.querySelector('input[name="ckPlanoteca"]:checked');
    if (!check) {
        new Toast('Seleccione un Plano');
        return;
    }

    document.getElementById('id_planoteca_del').value = check.dataset.id;
    new Fancybox([{ src: "#modalFormPlanotecaDel" }]);
}

function openModal() {
    document.querySelector('#id_planoteca').value = "";
    document.querySelector('#titleModal').innerHTML = "Nuevo Plano";
    document.querySelector("#formPlanoteca").reset();
    removeArchivo();
    removePhoto1();
}