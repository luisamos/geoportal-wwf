document.addEventListener('DOMContentLoaded', function () {

    if (document.querySelector("#formPaisajes")) {
        var formPaisajes = document.querySelector("#formPaisajes");
        formPaisajes.onsubmit = function (e) {
            e.preventDefault();

            var strPaisaje = document.querySelector('#paisaje').value;
            var strEstrategia = document.querySelector('#estrategia').value;
            if (strPaisaje == '' || strEstrategia == '') {
                swal("Atención", "Todos los campos son obligatorios.", "error");
                return false;
            }
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Gpaisajes/setPaisaje';
            var formData = new FormData(formPaisajes);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formPaisajes.reset();
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

    if (document.querySelector("#formPaisajesDel")) {
        let formPaisajesDel = document.querySelector("#formPaisajesDel");
        formPaisajesDel.onsubmit = function (e) {
            e.preventDefault();
            let request = (window.XMLHttpRequest) ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Gpaisajes/delPaisaje';
            let formData = new FormData(formPaisajesDel);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formPaisajesDel.reset();
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

    const check = document.querySelector('input[name="ckPaisajes"]:checked');
    if (!check) {
        new Toast('seleccione un Paisaje');
        return;
    }

    document.getElementById('id_paisaje').value = check.dataset.id;
    let id_paisaje = check.dataset.id;
    document.querySelector('#titleModal').innerHTML = "Actualizar Paisaje";

    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Gpaisajes/getPaisaje/' + id_paisaje;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {

        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#id_paisaje").value = objData.data.id_paisaje;
                document.querySelector("#paisaje").value = objData.data.paisaj_paisaje;
                document.querySelector("#estrategia").value = objData.data.paisaj_estrategia;
                document.querySelector("#objetivo").value = objData.data.paisaj_objetivo;

                document.querySelector("#meta1").value = objData.data.paisaj_meta1;
                document.querySelector("#meta2").value = objData.data.paisaj_meta2;
                document.querySelector("#meta3").value = objData.data.paisaj_meta3;
                document.querySelector("#meta4").value = objData.data.paisaj_meta4;
                document.querySelector("#meta5").value = objData.data.paisaj_meta5;
                document.querySelector("#indicador").value = objData.data.paisaj_indicador;

                new Fancybox([{ src: "#modalFormPaisajes" }]);
            }
            else {
                new Toast("Error: " + objData.msg);
            }
        }
    }
}


function fntAddEstrategia() {

    const check = document.querySelector('input[name="ckPaisajes"]:checked');
    if (!check) {
        new Toast('seleccione un Paisaje');
        return;
    }

    document.getElementById('id_paisaje').value = check.dataset.id;
    const id_paisaje = check.dataset.id;
    document.getElementById('paisaje').value = check.dataset.paisaje;
    document.getElementById('paisaje').readOnly = true;

    document.querySelector('#titleModal').innerHTML = "Agregar Estrategia";

    new Fancybox([{ src: "#modalFormPaisajes" }]);
    document.querySelector("#id_ref").value = id_paisaje;

}


function openModalDel() {
    const check = document.querySelector('input[name="ckPaisajes"]:checked');
    if (!check) {
        new Toast('Seleccione un Paisaje');
        return;
    }

    document.getElementById('id_paisaje_del').value = check.dataset.id;
    new Fancybox([{ src: "#modalFormPaisajesDel" }]);
}

function openModal() {
    document.querySelector('#id_paisaje').value = "";
    document.querySelector('#titleModal').innerHTML = "Nuevo Paisaje";
    document.querySelector("#formPaisajes").reset();
}