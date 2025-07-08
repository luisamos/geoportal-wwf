
function acceptNum(a) {
    a = a.which ? a.which : event.keyCode;
    return 13 < a && (48 > a || 57 < a) ? !1 : !0
}

function sendRequest(url, method, data, onSuccess, onError) {
    $.ajax({
        url: url,
        type: method,
        data: data,
        processData: false,
        contentType: false,
        success: function (response) {
            if (onSuccess) onSuccess(response);
        },
        error: function (xhr, status, error) {
            if (onError) onError(xhr, status, error);
        }
    });
}

function listarTematicas() {
    let ajaxUrl = base_url + "/Tematica/listar";

    $.ajax({
        url: ajaxUrl,
        type: "GET",
        dataType: "json",
        success: function (rpta) {
            const selectTematica = $('#id_tematica');
            let optionsHTML = '<option value="0">[Seleccione una opción]</option>';
            if (rpta.estado) {
                let datos = rpta.datos;
                datos.forEach(i => {
                    optionsHTML += `<option value="${i.id_tematica}">${i.nombre}</option>`;
                });
            }
            selectTematica.html(optionsHTML);
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX:", xhr, status, error);
        }
    });
}