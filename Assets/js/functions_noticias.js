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
                alert("No seleccionó foto");
                if (document.querySelector('#img')) {
                    document.querySelector('#img').remove();
                }
            }
        }
    }

    if (document.querySelector("#foto2")) {
        let foto2 = document.querySelector("#foto2");
        foto2.onchange = function (e) {
            let uploadFoto2 = document.querySelector("#foto2").value;
            let fileimg = document.querySelector("#foto2").files;
            let nav = window.URL || window.webkitURL;
            let contactAlert = document.querySelector('#form_alert');
            if (uploadFoto2 != '') {
                let type = fileimg[0].type;
                let name = fileimg[0].name;
                if (type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png') {
                    contactAlert.innerHTML = '<p class="errorArchivo2">El archivo no es válido.</p>';
                    if (document.querySelector('#img2')) {
                        document.querySelector('#img2').remove();
                    }
                    document.querySelector('.delPhoto2').classList.add("notBlock");
                    foto2.value = "";
                    return false;
                } else {
                    contactAlert.innerHTML = '';
                    if (document.querySelector('#img2')) {
                        document.querySelector('#img2').remove();
                    }
                    document.querySelector('.delPhoto2').classList.remove("notBlock");
                    let objeto_url2 = nav.createObjectURL(this.files[0]);
                    document.querySelector('.prevPhoto2 div').innerHTML = "<img id='img2' src=" + objeto_url2 + ">";
                }
            } else {
                alert("No seleccionó foto2");
                if (document.querySelector('#img2')) {
                    document.querySelector('#img2').remove();
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

    if (document.querySelector(".delPhoto2")) {
        let delPhoto2 = document.querySelector(".delPhoto2");
        delPhoto2.onclick = function (e) {
            document.querySelector("#foto_remove2").value = 1;
            removePhoto2();
        }
    }

    if (document.querySelector("#formNoticia")) {
        let formNoticia = document.querySelector("#formNoticia");
        formNoticia.onsubmit = function (e) {
            e.preventDefault();

            let id_noticia = document.querySelector('#id_noticia').value;

            let tipo = document.querySelector('#tipo').value;
            let url = document.querySelector('#url').value;
            let titulo = document.querySelector('#titulo').value;
            let foto = document.querySelector('#foto').value;
            let foto2 = document.querySelector('#foto2').value;


            if (id_noticia < 1 && tipo == 2 && (url == '' || foto == '')) {
                new Toast("Debe escribir la URL y subir almenos 1 Imagen");
                return false;
            }
            else {
                if (id_noticia < 1 && tipo == 1 && (titulo == '' || foto == '' || foto2 == '')) {
                    new Toast("Todos los campos son obligatorios.");
                    return false;
                }
            }

            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Gnoticias/setNoticia';
            let formData = new FormData(formNoticia);

            // Obtener el valor del contenido del editor CKEditor
            let editorData = editor.getData();
            formData.append('descripcion', editorData); // Agrega el contenido del editor al FormData

            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formNoticia.reset();
                        Fancybox.close();
                        removePhoto1();
                        removePhoto2();
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

    if (document.querySelector("#formNoticiaDel")) {
        let formNoticiaDel = document.querySelector("#formNoticiaDel");
        formNoticiaDel.onsubmit = function (e) {
            e.preventDefault();
            let request = (window.XMLHttpRequest) ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Gnoticias/delNoticia';
            let formData = new FormData(formNoticiaDel);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formNoticiaDel.reset();
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

    const check = document.querySelector('input[name="noticias"]:checked');
    if (!check) {
        new Toast('seleccione una noticia');
        return;
    }

    document.getElementById('id_noticia').value = check.dataset.id;
    let id_noticia = check.dataset.id;

    document.querySelector('#titleModal').innerHTML = "Actualizar Noticia";

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Gnoticias/getNoticia/' + id_noticia;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);

            if (objData.status) {
                document.querySelector("#id_noticia").value = objData.data.id_noticia;
                document.querySelector("#tipo").value = objData.data.notici_tipo;

                document.querySelector("#url").value = objData.data.notici_url;
                document.querySelector("#titulo").value = objData.data.notici_titulo;
                //document.querySelector("#descripcion").value = objData.data.notici_descripcion;
                editor.setData(objData.data.notici_descripcion);

                document.querySelector('#foto_actual').value = objData.data.notici_imagen1;
                document.querySelector("#foto_remove").value = 0;

                document.querySelector('#foto_actual2').value = objData.data.notici_imagen2;
                document.querySelector("#foto_remove2").value = 0;


                if (document.querySelector('#img')) {
                    document.querySelector('#img').src = objData.data.url_noticia;
                } else {
                    document.querySelector('.prevPhoto div').innerHTML = "<img id='img' src=" + objData.data.url_noticia + ">";
                }

                if (objData.data.notici_imagen1 == 'notici_imagen1.png') {
                    document.querySelector('.delPhoto').classList.add("notBlock");
                } else {
                    document.querySelector('.delPhoto').classList.remove("notBlock");
                }


                if (document.querySelector('#img2')) {
                    document.querySelector('#img2').src = objData.data.url_noticia2;
                } else {
                    document.querySelector('.prevPhoto2 div').innerHTML = "<img id='img2' src=" + objData.data.url_noticia2 + ">";
                }

                if (objData.data.notici_imagen2 == 'notici_imagen2.png') {
                    document.querySelector('.delPhoto2').classList.add("notBlock");
                } else {
                    document.querySelector('.delPhoto2').classList.remove("notBlock");
                }

                new Fancybox([{ src: "#modalFormNoticia" }]);
            }
            else {
                new Toast(objData.msg);
            }
        }
    }
}

function openModalDel() {
    const check = document.querySelector('input[name="noticias"]:checked');
    if (!check) {
        new Toast('Seleccione una Noticia');
        return;
    }

    document.getElementById('id_noticia_del').value = check.dataset.id;
    new Fancybox([{ src: "#modalFormNoticiaDel" }]);
}

function removePhoto1() {
    document.querySelector('#foto').value = "";
    document.querySelector('.delPhoto').classList.add("notBlock");
    if (document.querySelector('#img')) {
        document.querySelector('#img').remove();
    }
}

function removePhoto2() {
    document.querySelector('#foto2').value = "";
    document.querySelector('.delPhoto2').classList.add("notBlock");
    if (document.querySelector('#img2')) {
        document.querySelector('#img2').remove();
    }
}

function openModal() {
    rowTable = "";
    document.querySelector('#id_noticia').value = "";
    document.querySelector('#titleModal').innerHTML = "Nueva Noticia";
    document.querySelector("#formNoticia").reset();
    removePhoto1();
    removePhoto2();
}



// Obtener el valor del contenido del editor CKEditor
function obtenerContenidoEditor() {
    var editorData = editor.getData();
    console.log(editorData); // Puedes mostrarlo en la consola o hacer lo que desees con el valor.
}