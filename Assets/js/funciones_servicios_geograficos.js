const hoy = new Date().toLocaleDateString('es-PE', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
});

console.log(
    `%c⚫ Geoportal WWF (${hoy} - version [1.2.6])`,
    'color: white; background-color: #4CAF50; font-size: 14px; padding: 6px 10px; border-radius: 4px;'
);

listarTematicas();
let direccionServicioWeb = document.getElementById("direccion_web");
let tipoServicioWeb = document.getElementById("tipo");
let capasTematicas = document.getElementById("capas_tematicas");

let conectarServicioWeb = document.getElementById("conectarServicioWeb");
let btnGuardar = document.getElementById("btnGuardar");
let formServicioGeografico = document.querySelector("#formServicioGeografico");

function limpiarURL(url) {
    if (typeof url !== 'string') return '';
    url = url.trim();
    url = url.split('?')[0]; // Eliminar todo lo que sigue a ?
    url = url.replace(/\/+$/, ''); // Eliminar slash final redundante
    return url;
}

conectarServicioWeb.addEventListener("click", function (e) {
    e.preventDefault();
    if (direccionServicioWeb.value.length > 0) {
        switch (tipoServicioWeb.value) {
            case '1':
                conectarServicioOGCWMS();
                break;
            case '2':
                conectarServicioRestArcGIS();
                break;
        }
    } else {
        direccionServicioWeb.classList.add('is-invalid');
        direccionServicioWeb.focus();
        conectarServicioWeb.innerHTML = 'Conectar';
    }
});

btnGuardar.addEventListener("click", function (e) {
    e.preventDefault();
    const id_servicio_geografico = $("#id_servicio_geografico").val();
    const tipo = $("#tipo").val();
    const idTematica = $("#id_tematica").val();
    const direccionWeb = $("#direccion_web").val();
    const capa = $("#capas_tematicas").val();
    const nombre = $('#capas_tematicas option:selected').text();
    const alias = $("#alias").val();
    const visible = $("#visible").val();

    if (!tipo || !alias || !direccionWeb) {
        new Toast("Todos los campos son obligatorios.");
        return;
    }
    if (idTematica == "0") {
        new Toast("Elegir temática.");
        $("#id_tematica").focus();
        return;
    }

    if (capa === "-1") {
        new Toast("Elegir capa temática.");
        $("#nombre").focus();
        return;
    }

    const direccionWebFinal = limpiarURL(direccionWeb);
    let formData = new FormData();
    if (id_servicio_geografico != '' || id_servicio_geografico != null || id_servicio_geografico != undefined) {
        formData.append("id_servicio_geografico", id_servicio_geografico);
    }
    formData.append("tipo", tipo);
    formData.append("id_tematica", idTematica);
    formData.append("direccion_web", direccionWebFinal);
    formData.append("capa", capa);
    formData.append("nombre", nombre);
    formData.append("alias", alias);
    formData.append("visible", visible);

    console.log(formData);

    sendRequest(base_url + "ServiciosGeograficos/set", "POST", formData, function (rpta) {
        console.log(rpta);
        if (!rpta.estado) {
            new Toast(`Error: ${rpta.mensaje}`);
            return;
        }

        formServicioGeografico.reset();
        Fancybox.close();
        new Toast(rpta.mensaje);
        setTimeout(function () {
            window.location.reload();
        }, 2000);
        return false;
    });
});

document.addEventListener('DOMContentLoaded', function () {

    if (document.querySelector("#formServicioGeografico")) {
        formServicioGeografico.onsubmit = function (e) {
        }
    }

    if (document.querySelector("#formServicioGeograficoDel")) {
        let formServicioGeograficoDel = document.querySelector("#formServicioGeograficoDel");
        formServicioGeograficoDel.onsubmit = function (e) {
            e.preventDefault();

            let id_servicio_geografico = $("#id_servicio_geografico_del").val();

            let formData = new FormData();
            formData.append("id_servicio_geografico", id_servicio_geografico);
            sendRequest(base_url + "ServiciosGeograficos/del", "POST", formData, function (rpta) {
                if (rpta.estado) {
                    formServicioGeograficoDel.reset();
                    Fancybox.close();
                    new Toast(rpta.mensaje);
                    setTimeout(function () {
                        window.location.reload();
                    }, 2000);
                }
                else {
                    new Toast(`Error: ${rpta.mensaje}`);
                }
                return false;
            });
        }
    }

}, false);

function fntEditInfo() {
    const check = document.querySelector('input[name="ckServicioGeografico"]:checked');
    if (!check) {
        new Toast('Seleccione un Servicio Geográfico');
        return;
    }

    document.getElementById('id_servicio_geografico').value = check.dataset.id;
    let id_servicio_geografico = check.dataset.id;
    document.querySelector('#titleModal').innerHTML = "Actualizar Servicio Geográfico";

    sendRequest(base_url + "ServiciosGeograficos/get/" + id_servicio_geografico, "GET", null, function (rpta) {
        if (rpta.estado) {
            let datos = rpta.datos;
            console.log(datos);
            $("#tipo").val(datos.tipo);
            $("#id_tematica").val(datos.id_tematica);
            $("#alias").val(datos.alias);
            $("#direccion_web").val(datos.direccion_web);
            $('#capas_tematicas option:first').remove();
            $('#capas_tematicas').append($('<option>', {
                value: datos.capa,
                text: datos.nombre
            }));
            $("#visible").val((datos.visible) ? 1 : 0);
            new Fancybox([{ src: "#modalFormServicioGeografico" }]);
        }
        else {
            new Toast(`Error: ${rpta.mensaje}`);
        }
    });
}

function openModalDel() {
    const check = document.querySelector('input[name="ckServicioGeografico"]:checked');
    if (!check) {
        new Toast('Seleccione un Servicio Geográfico');
        return;
    }

    document.getElementById('id_servicio_geografico_del').value = check.dataset.id;
    new Fancybox([{ src: "#modalFormServicioGeograficoDel" }]);
}

function openModal() {
    document.querySelector('#id_servicio_geografico').value = "";
    document.querySelector('#titleModal').innerHTML = "Nuevo Servicio Geográfico";
    document.querySelector("#formServicioGeografico").reset();
}

function conectarServicioOGCWMS() {
    const urlServicioWMS = modificarURLServicioWMS(direccionServicioWeb.value.trim());

    if (validarUrl(urlServicioWMS)) {
        fetch(urlServicioWMS, { mode: 'cors' })
            .then(response => {
                console.log(response);
                if (!response.ok) throw new Error(`La solicitud falló, revisar la dirección web, ${response.status}`);
                return response.text();
            })
            .then(xmlText => {
                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(xmlText, 'text/xml');

                const grupoCapas = xmlDoc.querySelectorAll('Layer[queryable="1"]');
                $('#capas_tematicas option:first').remove();
                grupoCapas.forEach(capa => {
                    const nombre = capa.querySelector('Name').textContent;
                    const titulo = capa.querySelector('Title').textContent;

                    const option = document.createElement('option');
                    option.textContent = titulo;
                    option.value = nombre;
                    capasTematicas.appendChild(option);
                });
            })
            .catch(error => {
                new Toast(`${error}`);
                limpiar();
            });

        conectarServicioWeb.classList.add('disabled');
        direccionServicioWeb.classList.remove('is-invalid');
        conectarServicioWeb.innerHTML = 'Conectar';
    } else limpiar();
}

function conectarServicioRestArcGIS() {
    const urlArcGISRest = modificarURLServicioArcGISRest(direccionServicioWeb.value.trim());

    $.ajax({
        url: `${base_url}/Visor2/conectarServicioRest`,
        method: 'POST',
        data: { url: urlArcGISRest },
        dataType: 'json',
        success: function (l) {
            $('#capas_tematicas option:first').remove();
            if (l.estado) {
                $.each(l.datos, function (_, i) {
                    const option = $('<option>').val(i.id).text(i.name);
                    $('#capas_tematicas').append(option);
                });
            }
            else {
                console.log(layers);
                new Toast(`Respuesta inesperada del servidor, ${layers}`);
            }
        },
        error: function (xhr, status, error) {
            console.log(error);
            new Toast(`Error: ${xhr}|${status}|${error}`);
        }
    });
}

function validarURLServicioWMS(url) {
    const regex = /[?&](?=.*\brequest\b)(?=.*\bservice\b)/i;
    return regex.test(url);
}

function modificarURLServicioWMS(url) {
    if (!/\?/.test(url) || !/(?:[?&])(REQUEST|SERVICE)=/i.test(url)) {
        const parametrosFaltantes = [];

        if (!/(?:[?&])REQUEST=/i.test(url)) {
            parametrosFaltantes.push('REQUEST=GetCapabilities');
        }
        if (!/(?:[?&])SERVICE=/i.test(url)) {
            parametrosFaltantes.push('SERVICE=WMS');
        }

        url += (url.includes('?') ? '&' : '?') + parametrosFaltantes.join('&');
    }
    return url;
}

function modificarURLServicioArcGISRest(url) {
    // Si ya tiene el parámetro f=json, no hacer nada
    if (!/[?&]f=json/i.test(url)) {
        // Agrega ?f=json o &f=json dependiendo de si ya hay ?
        url += (url.includes('?') ? '&' : '?') + 'f=json';
    }
    return url;
}

function validarUrl(url) {
    try {
        new URL(url);
        return true;
    } catch (error) {
        return false;
    }
}

function limpiar() {

}