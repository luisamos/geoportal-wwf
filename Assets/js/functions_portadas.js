document.addEventListener('DOMContentLoaded', function () {

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
                alert("No selecciono foto");
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
            removePhoto();
        }
    }

    if (document.querySelector("#formPortada")) {
        let formPortada = document.querySelector("#formPortada");
        formPortada.onsubmit = function (e) {
            e.preventDefault();
            let strTitulo = document.querySelector('#titulo').value;
            if (strTitulo == '') {
                new Toast("Todos los campos son obligatorios.");
                return false;
            }

            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Portadas/setPortada';
            let formData = new FormData(formPortada);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formPortada.reset();
                        Fancybox.close();
                        removePhoto();
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

    if (document.querySelector("#formPotadaDel")) {
        let formPotadaDel = document.querySelector("#formPotadaDel");
        formPotadaDel.onsubmit = function (e) {
            e.preventDefault();
            let request = (window.XMLHttpRequest) ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Portadas/delPortada';
            let formData = new FormData(formPotadaDel);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formPotadaDel.reset();
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
    const check = document.querySelector('input[name="ckPortada"]:checked');
    if (!check) {
        new Toast('seleccione una Portada');
        return;
    }

    document.getElementById('id_portada').value = check.dataset.id;
    let id_portada = check.dataset.id;

    document.querySelector('#titleModal').innerHTML = "Actualizar Portada";

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Portadas/getPortada/' + id_portada;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#id_portada").value = objData.data.id_portada;
                document.querySelector("#titulo").value = objData.data.portad_titulo;
                document.querySelector("#descripcion").value = objData.data.portad_descrip;
                document.querySelector('#foto_actual').value = objData.data.portad_imagen;
                document.querySelector("#foto_remove").value = 0;

                if (document.querySelector('#img')) {
                    document.querySelector('#img').src = objData.data.url_portada;
                } else {
                    document.querySelector('.prevPhoto div').innerHTML = "<img id='img' src=" + objData.data.url_portada + ">";
                }

                if (objData.data.portad_imagen == 'portad_imagen.png') {
                    document.querySelector('.delPhoto').classList.add("notBlock");
                } else {
                    document.querySelector('.delPhoto').classList.remove("notBlock");
                }

                new Fancybox([{ src: "#modalFormPortada" }]);
            }
            else {
                swal("Error", objData.msg, "error");
            }
        }
    }
}

function openModalDel() {
    const check = document.querySelector('input[name="ckPortada"]:checked');
    if (!check) {
        new Toast('Seleccione una Portada');
        return;
    }

    document.getElementById('id_portada_del').value = check.dataset.id;
    new Fancybox([{ src: "#modalFormPortadaDel" }]);
}

function removePhoto() {
    document.querySelector('#foto').value = "";
    document.querySelector('.delPhoto').classList.add("notBlock");
    if (document.querySelector('#img')) {
        document.querySelector('#img').remove();
    }
}

function openModal() {
    document.querySelector('#id_portada').value = "";
    document.querySelector('#titleModal').innerHTML = "Nueva Portada";
    document.querySelector("#formPortada").reset();
    removePhoto();
}

let cropper;

function recortarPortada() {
    document.querySelector('#id_portada').value = "";
    document.querySelector('#titleModalRecortar').innerHTML = "Recortar Portada";
    document.getElementById('imgCrop').src = '../../../Assets/images/portada.jpg?' + new Date().getTime();
    const check = document.querySelector('input[name="ckPortada"]:checked');
    if (!check) {
        new Toast('Seleccione una Portada');
        return;
    }

    document.getElementById('id_portada_recortar').value = check.dataset.id;

    Fancybox.show([{
        src: "#modalFormRecortarPortada",
        type: "inline"
    }], {
        on: {
            done: () => {
                const image = document.getElementById('imgCrop');
                if (cropper) cropper.destroy();
                cropper = new Cropper(image, {
                    aspectRatio: 1520 / 600,
                    viewMode: 2,
                    autoCropArea: 1.0
                });
            },
            closing: () => {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            }
        }
    });
}