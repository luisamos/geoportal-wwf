const hoy = new Date().toLocaleDateString('es-PE', { year: 'numeric', month: 'long', day: 'numeric' });

console.log(
    `%c⚫ Geoportal WWF (${hoy}) - version [1.2.6]`,
    'color: white; background-color: #4CAF50; font-size: 14px; padding: 6px 10px; border-radius: 4px;'
);

window.addEventListener('DOMContentLoaded', function () {

    listarTematicas();

    if (document.querySelector("#formCapaGeografica")) {
        let formCapaGeografica = document.querySelector("#formCapaGeografica");
        formCapaGeografica.onsubmit = function (e) {
            e.preventDefault();

            const fileInput = $("#archivo")[0];
            const file = fileInput.files[0];
            const idCapaGeografica = $("#id_capa_geografica").val();
            const tipo = $("#tipo").val();
            const idTematica = $("#id_tematica").val();
            //const tematica = $("#id_tematica option:selected").text();
            const nombre = $("#nombre").val();
            const alias = $("#alias").val();
            const visible = $("#visible").val();

            if (!idTematica || (!nombre && !$('#nombre').is(':disabled'))) {
                new Toast("Todos los campos son obligatorios.");
                return;
            }

            if (idTematica === "0") {
                new Toast("Elegir temática.");
                $("#id_tematica").focus();
                return;
            }

            // ACTUALIZACIÓN SIN ARCHIVO
            if (!file && (idCapaGeografica != '' || idCapaGeografica != null || idCapaGeografica != undefined)) {
                new Toast("Actualizando capa existente sin cambiar archivo...");

                const updateData = new FormData();
                updateData.append("id_capa_geografica", idCapaGeografica);
                updateData.append("tipo", tipo);
                updateData.append("nombre", nombre);
                updateData.append("alias", alias);
                updateData.append("id_tematica", idTematica);
                updateData.append("visible", visible);

                sendRequest(base_url + "CapasGeograficas/set", "POST", updateData, function (response) {
                    if (!response.estado) {
                        new Toast("Error al actualizar: " + response.mensaje);
                    } else {
                        new Toast("Actualización completada.");
                        setTimeout(() => window.location.reload(), 2000);
                    }
                    formCapaGeografica.reset();
                    Fancybox.close();
                });
                return;
            }

            if (!file) {
                new Toast("No se seleccionó ningún archivo.");
                return;
            }

            // VALIDACIÓN DE EXTENSIÓN
            const extensionesPermitidas = ["zip", "tif", "tiff"];
            const nombreArchivo = file.name.toLowerCase();
            const extension = nombreArchivo.split('.').pop();

            if (!extensionesPermitidas.includes(extension)) {
                new Toast("Tipo de archivo no permitido. Solo .zip y .tif/.tiff");
                return;
            }

            // A. FLUJO ZIP (tipo 1)
            if (extension === "zip") {
                new Toast("Subiendo archivo ZIP...");

                $('#btnGuardar').css('display', 'none');
                $('#progressContainer').css('display', 'block');
                $('#progressBar').css({ 'width': '0%', 'background': 'linear-gradient(to right, #4caf50, #81c784)' });
                $('#progressText').text('0%');

                const chunkSize = 1024 * 1024 * 10;
                const totalChunks = Math.ceil(file.size / chunkSize);
                let currentChunk = 0;

                function uploadNextChunk() {
                    const start = currentChunk * chunkSize;
                    const end = Math.min(start + chunkSize, file.size);
                    const chunk = file.slice(start, end);
                    const formData = new FormData();

                    formData.append('file', chunk);
                    formData.append('filename', file.name);
                    formData.append('chunk', currentChunk);
                    formData.append('totalChunks', totalChunks);

                    $.ajax({
                        url: base_url + "Archivos/subirZip",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            if (res.estado) {
                                currentChunk++;
                                const percent = Math.floor((currentChunk / totalChunks) * 100);
                                $('#progressBar').css('width', percent + '%');
                                $('#progressText').text(`Subiendo... ${percent}%`);

                                if (currentChunk < totalChunks) {
                                    uploadNextChunk();
                                } else {
                                    $('#progressBar').css('background', 'linear-gradient(to right, #2196f3, #64b5f6)');
                                    $('#progressText').text('Completado');

                                    const shapeFileDir = res.nombre_carpeta;
                                    new Toast("Renombrando shapefile...");

                                    const renameData = new FormData();
                                    renameData.append("shapeFileDir", shapeFileDir);
                                    sendRequest(base_url + "ShapeFile/renombrar", "POST", renameData, function (response2) {
                                        if (!response2.estado) {
                                            new Toast(`Error al renombrar: ${response2.mensaje}`);
                                            return;
                                        }

                                        const newShapeFileDir = response2.ruta;
                                        new Toast("Validando shapefile...");

                                        const validateData = new FormData();
                                        validateData.append("shapeFileDir", newShapeFileDir);
                                        sendRequest(base_url + "ShapeFile/validar", "POST", validateData, function (response3) {
                                            if (!response3.estado) {
                                                new Toast(`Error en validación: ${response3.mensaje}`);
                                                return;
                                            }

                                            new Toast("Exportando a PostGIS...");
                                            const exportData = new FormData();
                                            exportData.append("shapeFileDir", newShapeFileDir);
                                            exportData.append("tableName", nombre);
                                            exportData.append("schema", "geo");
                                            exportData.append("columnas", response3.columnas);
                                            sendRequest(base_url + "ShapeFile/exportarPostGIS", "POST", exportData, function (response4) {
                                                if (!response4.estado) {
                                                    new Toast(`Error al exportar a PostGIS: ${response4.mensaje}`);
                                                    return;
                                                }

                                                const schema = response4.schema;
                                                const tableName = response4.tabla;
                                                const srid = response4.srid;
                                                //alert(response4.cmd);
                                                new Toast(`Exportado a PostGIS: ${tableName} (SRID: ${srid})`);

                                                new Toast("Verificando datastore...");
                                                const checkDataStoreData = new FormData();
                                                checkDataStoreData.append("schema", schema);

                                                sendRequest(base_url + "Geoserver/crearDataStore", "POST", checkDataStoreData, function (response5) {
                                                    const datastore = response5.datastore;

                                                    new Toast("Publicando en GeoServer...");
                                                    const publishData = new FormData();
                                                    publishData.append("schema", schema);
                                                    publishData.append("tabla", tableName);
                                                    publishData.append("workspace", schema);
                                                    publishData.append("datastore", datastore);
                                                    publishData.append("srid", srid);

                                                    sendRequest(base_url + "Geoserver/publicarVector", "POST", publishData, function (response6) {
                                                        if (!response6.estado) {
                                                            new Toast(`Error al publicar en GeoServer: ${response6.mensaje}`);
                                                            return;
                                                        }

                                                        new Toast("Registrando capa en base de datos...");
                                                        const publishVector = new FormData();
                                                        publishVector.append("nombre", tableName);
                                                        publishVector.append("alias", alias);
                                                        publishVector.append("id_tematica", idTematica);
                                                        publishVector.append("tipo", 1);
                                                        publishVector.append("visible", visible);

                                                        if (idCapaGeografica) {
                                                            publishVector.append("id_capa_geografica", idCapaGeografica);
                                                        }

                                                        sendRequest(base_url + "CapasGeograficas/set", "POST", publishVector, function (response7) {
                                                            if (!response7.estado) {
                                                                new Toast("Error: " + response7.mensaje);
                                                            } else {
                                                                new Toast(response7.mensaje.toString().trim());
                                                                setTimeout(() => window.location.reload(), 2000);
                                                            }
                                                            formCapaGeografica.reset();
                                                            Fancybox.close();
                                                        });
                                                    });
                                                });
                                            });
                                        });
                                    });
                                }
                            } else {
                                new Toast(`Error al subir parte del archivo: ${res.mensaje}`);
                                $('#btnGuardar').prop('disabled', false).text('Guardar');
                            }
                        },
                        error: function () {
                            $('#progressBar').css('background', 'linear-gradient(to right, #e53935, #ef5350)');
                            $('#progressText').text('❌ Error ');
                            $('#btnGuardar').prop('disabled', false).text('Guardar');
                        }
                    });
                }
                uploadNextChunk();
            }

            // B. FLUJO TIFF (tipo 2)
            else if (extension === "tif" || extension === "tiff") {
                $("#btnGuardar").prop("disabled", true);
                $("#spinner").show();
                new Toast("Publicando raster...");

                const formData2 = new FormData();
                formData2.append("raster", file);
                formData2.append("schema", "geo");

                sendRequest(base_url + "Geoserver/publicarRaster", "POST", formData2, function (response2) {
                    if (!response2.estado) {
                        new Toast(`Error: ${response2.mensaje}`);
                        $("#btnGuardar").prop("disabled", false);
                        $("#spinner").hide();
                        return;
                    }

                    new Toast("Registrando metadata...");

                    const publishVector = new FormData();
                    publishVector.append("nombre", response2.capa);
                    publishVector.append("alias", alias);
                    publishVector.append("id_tematica", idTematica);
                    publishVector.append("tipo", 2);
                    publishVector.append("visible", visible);

                    if (idCapaGeografica) {
                        publishVector.append("id_capa_geografica", idCapaGeografica);
                    }

                    sendRequest(base_url + "CapasGeograficas/set", "POST", publishVector, function (response3) {
                        $("#btnGuardar").prop("disabled", false);
                        $("#spinner").hide();

                        if (!response3.estado) {
                            new Toast(`Error: ${response3.mensaje}`);
                        } else {
                            new Toast(response3.mensaje.toString().trim());
                            setTimeout(() => window.location.reload(), 2000);
                        }
                        formCapaGeografica.reset();
                        Fancybox.close();
                    });
                });
            }
        };
    }

    if (document.querySelector("#formCapaGeograficaDel")) {
        let formCapaGeograficaDel = document.querySelector("#formCapaGeograficaDel");
        formCapaGeograficaDel.onsubmit = function (e) {
            e.preventDefault();

            let id_capa_geografica = $("#id_capa_geografica_del").val();
            console.log(id_capa_geografica);
            let formData = new FormData();
            formData.append("id_capa_geografica", id_capa_geografica);
            sendRequest(base_url + "CapasGeograficas/del", "POST", formData, function (rpta) {
                if (rpta.estado) {
                    formCapaGeograficaDel.reset();
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

    if (document.querySelector("#formCapaGeograficaSLD")) {
        let formCapaGeograficaSLD = document.querySelector("#formCapaGeograficaSLD");
        formCapaGeograficaSLD.onsubmit = function (e) {
            e.preventDefault();

            const archivoSLD = $("#archivoSLD")[0];
            const file = archivoSLD.files[0];

            if (!file) {
                new Toast("No se seleccionó ningún archivo.");
                return;
            }

            let nombreTabla = $("#nombre_tabla").val();
            console.log(nombreTabla);
            let formData = new FormData();
            formData.append("nombre", nombreTabla);
            formData.append("file", file);
            sendRequest(base_url + "Geoserver/ActualizarEstilo", "POST", formData, function (rpta) {
                if (rpta.estado) {
                    formCapaGeograficaSLD.reset();
                    Fancybox.close();
                    new Toast(rpta.mensaje);
                    setTimeout(function () {
                        //window.location.reload();
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

function openModal() {
    document.querySelector('#id_capa_geografica').value = "";
    document.querySelector('#titleModal').innerHTML = '<div class="btn--icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 12H17V15H20V17H17V20H15V17H12V15H15V12ZM9 14L0 7L9 0L18 7L9 14ZM9 16.54L10 15.75V16C10 16.71 10.12 17.39 10.35 18L9 19.07L0 12.07L1.62 10.81L9 16.54Z" fill="black"/></svg> Nueva Capa Geográfica</div>';
    document.querySelector("#formCapaGeografica").reset();
    $('#btnGuardar').css('display', 'block');
    $('#progressContainer').css('display', 'none');

}

function fntEditInfo() {
    const check = document.querySelector('input[name="capas"]:checked');
    if (!check) {
        new Toast('Seleccione una capa geográfica');
        return;
    }

    document.getElementById('id_capa_geografica').value = check.dataset.id;
    let id_capa_geografica = check.dataset.id;

    document.querySelector('#titleModal').innerHTML = "Actualizar Capa Geográfica";

    sendRequest(base_url + "CapasGeograficas/get/" + id_capa_geografica, "GET", null, function (rpta) {
        if (rpta.estado) {
            let datos = rpta.datos;
            $("#tipo").val(datos.tipo);
            $("#id_tematica").val(datos.id_tematica);
            $("#id_tematica").prop('disabled', true);
            $("#nombre").val(datos.nombre);
            $("#nombre").prop('disabled', true);
            $("#alias").val(datos.alias);

            $("#visible").val((datos.visible) ? 1 : 0);
            new Fancybox([{ src: "#modalFormCapaGeografica" }]);

            $("#alias").focus();
        }
        else {
            new Toast(`Error: ${rpta.mensaje}`);
        }
    });
}

function openModalDel() {
    const check = document.querySelector('input[name="capas"]:checked');
    if (!check) {
        new Toast('Seleccione una Capa geográfica');
        return;
    }

    document.getElementById('id_capa_geografica_del').value = check.dataset.id;
    new Fancybox([{ src: "#modalFormCapaGeograficaDel" }]);
}

function openModalSLD() {
    const check = document.querySelector('input[name="capas"]:checked');
    if (!check) {
        new Toast('Seleccione una capa geográfica');
        return;
    }

    document.getElementById('id_capa_geografica_sld').value = check.dataset.id;
    document.getElementById('nombre_tabla').value = check.dataset.nombre;

    document.querySelector('#titleModalSLD').innerHTML = "Actualizar estilo SLD";
    new Fancybox([{ src: "#modalFormSLD" }]);
}

$('#archivo').on('change', function () {
    var archivo = this.files[0];
    if (archivo) {
        var nombre = archivo.name.toLowerCase();

        if (nombre.endsWith('.tif') || nombre.endsWith('.tiff')) {
            $('#nombre').prop('disabled', true);
            $('#alias').focus();
        } else {
            $('#nombre').prop('disabled', false);
            $('#nombre').focus();
        }
    }
});