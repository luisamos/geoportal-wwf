document.addEventListener('DOMContentLoaded', function () {

    if (document.querySelector("#formProgramas")) {
        var formProgramas = document.querySelector("#formProgramas");
        formProgramas.onsubmit = function (e) {
            e.preventDefault();

            var strNombre = document.querySelector('#nombre').value;
            var strDescripcion = document.querySelector('#descripcion').value;
            if (strNombre == '' || strDescripcion == '') {
                swal("Atención", "Todos los campos son obligatorios.", "error");
                return false;
            }
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Programas/setPrograma';
            var formData = new FormData(formProgramas);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formProgramas.reset();
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

    if (document.querySelector("#formProgramasDel")) {
        let formProgramasDel = document.querySelector("#formProgramasDel");
        formProgramasDel.onsubmit = function (e) {
            e.preventDefault();
            let request = (window.XMLHttpRequest) ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Programas/delPrograma';
            let formData = new FormData(formProgramasDel);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formProgramasDel.reset();
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

    const check = document.querySelector('input[name="ckProgramas"]:checked');
    if (!check) {
        new Toast('seleccione un Programa');
        return;
    }

    document.getElementById('id_programa').value = check.dataset.id;
    let id_programa = check.dataset.id;
    document.querySelector('#titleModal').innerHTML = "Actualizar Programa";

    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Programas/getPrograma/' + id_programa;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {

        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#id_programa").value = objData.data.id_programa;
                document.querySelector("#nombre").value = objData.data.progra_nombre;
                document.querySelector("#descripcion").value = objData.data.progra_descripcion;
                new Fancybox([{ src: "#modalFormProgramas" }]);
            }
            else {
                new Toast("Error: " + objData.msg);
            }
        }
    }
}





function openModalDel() {
    const check = document.querySelector('input[name="ckProgramas"]:checked');
    if (!check) {
        new Toast('Seleccione un Programa');
        return;
    }

    document.getElementById('id_programa_del').value = check.dataset.id;
    new Fancybox([{ src: "#modalFormProgramasDel" }]);
}

function openModal() {
    document.querySelector('#id_programa').value = "";
    document.querySelector('#titleModal').innerHTML = "Nuevo Programa";
    document.querySelector("#formProgramas").reset();
}