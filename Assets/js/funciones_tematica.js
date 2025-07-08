const hoy = new Date().toLocaleDateString('es-PE', { year: 'numeric', month: 'long', day: 'numeric' });
console.log(
    `%c⚫ Geoportal WWF (${hoy} - version [1.2.6])`,
    'color: white; background-color: #4CAF50; font-size: 14px; padding: 6px 10px; border-radius: 4px;'
);

document.addEventListener('DOMContentLoaded', function () {
    let listaCategorias = document.getElementById("listaCategorias");
    let listaSubCategorias = document.getElementById("listaSubCategorias");
    let listaCapasServicios = document.getElementById("listaCapasServicios");
    new Sortable(listaCategorias, { animation: 150 });
    new Sortable(listaSubCategorias, { animation: 150 });
    new Sortable(listaCapasServicios, { animation: 150 });

    listaCategorias.addEventListener("click", function (e) {
        const li = e.target.closest('li');
        if (!li) return;

        if (e.target.classList.contains('handle')) return;
        listaCategorias.querySelectorAll('.item.selected').forEach(el => {
            el.classList.remove('selected');
        });

        li.classList.add('selected');
        $("#idCategoria").val(li.id);
        $("#idCategoriaDel").val(li.id);

        const categoria = $(li).contents().filter(function () { return this.nodeType === 3; }).text().trim();

        $("#idPadre").val(li.id);
        $("#nombrePadre").val(categoria);

        listarSubCategorias(li.id);
    });

    listaSubCategorias.addEventListener("click", function (e) {
        const li = e.target.closest('li');
        if (!li) return;

        if (e.target.classList.contains('handle')) return;
        listaSubCategorias.querySelectorAll('.item.selected').forEach(el => {
            el.classList.remove('selected');
        });

        li.classList.add('selected');
        $("#idSubCategoria").val(li.id);
        $("#idSubCategoriaDel").val(li.id);
        $("#idPadre").val(li.dataset.id_padre);
        listarCapasServicios(li.id);
    });

    if (document.querySelector("#formCategoria")) {
        let formCategoria = document.querySelector("#formCategoria");
        formCategoria.onsubmit = function (e) {
            e.preventDefault();
            const idCategoria = $("#idCategoria").val();
            const nombre = $("#nombreCategoria").val();
            const visible = $("#visibleCategoria").val();

            if (!nombre || nombre.length <= 0) {
                new Toast("El nombre de la categoría no puede estar vacío.");
                return;
            }

            let formData1 = new FormData();
            if (idCategoria != '' || idCategoria != null || idCategoria != undefined || idCategoria != 0) {
                formData1.append("idCategoria", idCategoria);
            }
            formData1.append("nombre", nombre);
            formData1.append("visible", visible);

            sendRequest(base_url + "Tematica/setCategoria", "POST", formData1, function (rpta) {
                if (!rpta.estado) {
                    new Toast(`Error: ${rpta.mensaje}`);
                    return;
                }

                formCategoria.reset();
                Fancybox.close();
                new Toast(rpta.mensaje);
                setTimeout(function () {
                    window.location.reload();
                }, 1000);

                return false;
            });
        }
    }

    if (document.querySelector("#formCategoriaDel")) {
        let formCategoriaDel = document.querySelector("#formCategoriaDel");
        formCategoriaDel.onsubmit = function (e) {
            e.preventDefault();

            let idCategoriaDel = $("#idCategoriaDel").val();

            let formData2 = new FormData();
            formData2.append("idCategoria", idCategoriaDel);
            sendRequest(base_url + "Tematica/del", "POST", formData2, function (rpta) {
                if (rpta.estado) {
                    formCategoriaDel.reset();
                    Fancybox.close();
                    new Toast(rpta.mensaje);
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                }
                else {
                    new Toast(`Error: ${rpta.mensaje}`);
                }
                return false;
            });
        }
    }

    if (document.querySelector("#formSubCategoria")) {
        let formSubCategoria = document.querySelector("#formSubCategoria");
        formSubCategoria.onsubmit = function (e) {
            e.preventDefault();
            const idSubCategoria = $("#idSubCategoria").val();
            const idPadre = $("#idPadre").val();
            const nombreSubCategoria = $("#nombreSubCategoria").val();
            const visibleSubCategoria = $("#visibleSubCategoria").val();

            if (!nombreSubCategoria || nombreSubCategoria.length <= 0) {
                new Toast("El nombre de la categoría no puede estar vacío.");
                return;
            }

            let formData3 = new FormData();
            if (idSubCategoria != '' || idSubCategoria != null || idSubCategoria != undefined || idSubCategoria != 0) {
                formData3.append("idCategoria", idSubCategoria);
            }
            formData3.append("nombre", nombreSubCategoria);
            formData3.append("visible", visibleSubCategoria);
            formData3.append("idPadre", idPadre);
            sendRequest(base_url + "Tematica/setSubCategoria", "POST", formData3, function (rpta) {
                if (!rpta.estado) {
                    new Toast(`Error: ${rpta.mensaje}`);
                    return;
                }

                formSubCategoria.reset();
                Fancybox.close();
                new Toast(rpta.mensaje);
                setTimeout(function () {
                    window.location.reload();
                }, 1000);

                return false;
            });
        }
    }

    if (document.querySelector("#formSubCategoriaDel")) {
        let formSubCategoriaDel = document.querySelector("#formSubCategoriaDel");
        formSubCategoriaDel.onsubmit = function (e) {
            e.preventDefault();

            let idSubCategoriaDel = $("#idSubCategoriaDel").val();

            let formData4 = new FormData();
            formData4.append("idCategoria", idSubCategoriaDel);
            sendRequest(base_url + "Tematica/del", "POST", formData4, function (rpta) {
                if (rpta.estado) {
                    formSubCategoriaDel.reset();
                    Fancybox.close();
                    new Toast(rpta.mensaje);
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                }
                else {
                    new Toast(`Error: ${rpta.mensaje}`);
                }
                return false;
            });
        }
    }
}, false);

let guardarCategoria = document.getElementById("guardarCategoria");
guardarCategoria.addEventListener("click", function (e) {
    let orden = [];
    $("#listaCategorias li").each(function (i) {
        orden.push({ id: this.id, orden: i + 1 });
    });

    $.ajax({
        url: "Tematica/setOrden",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(orden),
        success: function (rpta) {
            if (rpta.estado) {
                new Toast(rpta.mensaje);
            } else {
                new Toast("Error: " + rpta.mensaje);
            }
            setTimeout(function () {
                window.location.reload();
            }, 1000);
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud:", error);
        }
    });
});

let guardarSubCategoria = document.getElementById("guardarSubCategoria");
guardarSubCategoria.addEventListener("click", function (e) {
    let orden = [];
    $("#listaSubCategorias li").each(function (i) {
        if (this.id != -1) {
            orden.push({ id: this.id, orden: i + 1 });
        }
    });

    if (orden.length > 0) {
        $.ajax({
            url: "Tematica/setOrden",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(orden),
            success: function (rpta) {
                if (rpta.estado) {
                    new Toast(rpta.mensaje);
                } else {
                    new Toast("Error: " + rpta.mensaje);
                }
                setTimeout(function () {
                    window.location.reload();
                }, 1000);
            },
            error: function (xhr, status, error) {
                console.error("Error en la solicitud:", error);
            }
        });
    }
});

let guardarCapasServicios = document.getElementById("guardarCapasServicios");
guardarCapasServicios.addEventListener("click", function (e) {
    let ordenCapasGeograficas = [];
    let ordenServiciosGeograficas = [];
    $("#listaCapasServicios li").each(function (i) {
        if (this.id != -1) {
            let servicio = $(this).data("servicio");
            if (servicio === 0) {
                ordenCapasGeograficas.push({ id: this.id, orden: i + 1 });
            }
            else if (servicio === 1) {
                ordenServiciosGeograficas.push({ id: this.id, orden: i + 1 });
            }
        }
    });

    if (ordenCapasGeograficas.length > 0) {
        $.ajax({
            url: "CapasGeograficas/setOrden",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(ordenCapasGeograficas),
            success: function (rpta) {
                if (rpta.estado) {
                    new Toast(rpta.mensaje);
                } else {
                    new Toast("Error: " + rpta.mensaje);
                }
                setTimeout(function () {
                    //window.location.reload();
                }, 1000);
            },
            error: function (xhr, status, error) {
                console.error("Error en la solicitud:", error);
            }
        });
    }

    if (ordenServiciosGeograficas.length > 0) {
        $.ajax({
            url: "ServiciosGeograficos/setOrden",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(ordenServiciosGeograficas),
            success: function (rpta) {
                if (rpta.estado) {
                    new Toast(rpta.mensaje);
                } else {
                    new Toast("Error: " + rpta.mensaje);
                }
                setTimeout(function () {
                    window.location.reload();
                }, 1000);
            },
            error: function (xhr, status, error) {
                console.error("Error en la solicitud:", error);
            }
        });
    }
});

function listarSubCategorias(idPadre) {
    $("#listaSubCategorias").empty();
    $("#listaCapasServicios").empty();
    sendRequest(base_url + "Tematica/listarSubCategorias/" + idPadre, "GET", null, function (rpta) {
        if (rpta.estado) {
            let subCategorias = rpta.datos;

            if (subCategorias.length > 0) {
                subCategorias.forEach(function (i) {
                    $("#listaSubCategorias").append(`
                        <li id="${i.id_tematica}" class="item" data-id_padre="${i.id_padre}" >
                            <span class="handle">☰</span>
                            ${i.nombre}
                        </li>
                    `);
                });
            }
            else {
                $("#listaSubCategorias").append(`<li id="-1" class="item"><span class="handle">☰</span>Ninguno</li>`);
            }
        }
        else {
            new Toast(`Error: ${rpta.mensaje}`);
        }
    });
}

function listarCapasServicios(idSubCategoria) {
    $("#listaCapasServicios").empty();
    sendRequest(base_url + "Tematica/listarCapasServicios/" + idSubCategoria, "GET", null, function (rpta) {
        if (rpta.estado) {
            let capasServicios = rpta.datos;

            if (capasServicios.length > 0) {
                capasServicios.forEach(function (i) {
                    $("#listaCapasServicios").append(`
                        <li id="${i.id}" class="item" data-servicio="${i.servicio}" >
                            <span class="handle">☰</span>
                            ${i.alias}
                        </li>
                    `);
                });
            }
            else {
                $("#listaCapasServicios").append(`<li id="-1" class="item"><span class="handle">☰</span>Ninguno</li>`);
            }
        }
        else {
            new Toast(`Error: ${rpta.mensaje}`);
        }
    });
}

function openModalCategoriaAdd() {
    document.querySelector('#titleModalCategoria').innerHTML = "Nueva Categoría";
    document.querySelector("#formCategoria").reset();
}

function openModalCategoriaEdit() {
    let idCategoria = $("#idCategoria").val();

    if (idCategoria === null || idCategoria === "" || idCategoria === undefined) {
        new Toast('Seleccione una categoría');
        return;
    }
    document.querySelector('#titleModalCategoria').innerHTML = "Actualizar categoría";

    sendRequest(base_url + "Tematica/getCategoria/" + idCategoria, "GET", null, function (rpta) {
        if (rpta.estado) {
            let datos = rpta.datos;
            $("#idCategoria").val(datos.id_tematica);
            $("#nombreCategoria").val(datos.nombre);
            $("#nombreCategoria").focus();
            $("#visibleCategoria").val((datos.visible) ? 1 : 0);

            new Fancybox([{ src: "#modalFormCategoria" }]);
        }
        else {
            new Toast(`Error: ${rpta.mensaje}`);
        }
    });
}

function openModalCategoriaDel() {
    let idCategoriaDel = $("#idCategoriaDel").val();
    if (idCategoriaDel === 0 || idCategoriaDel === null || idCategoriaDel === undefined || idCategoriaDel === "") {
        new Toast('Seleccione una Categoría');
        return;
    }
    new Fancybox([{ src: "#modalFormCategoriaDel" }]);
}

function openModalSubCategoriaAdd() {
    let idCategoria = $("#idCategoria").val();

    if (idCategoria === "" || idCategoria === null || idCategoria === undefined) {
        new Toast('Primero elegir una Categoría');
        Fancybox.close();
        return;
    }
    new Fancybox([{ src: "#modalFormSubCategoria" }]);
    document.querySelector('#titleModalSubCategoria').innerHTML = "Nueva Subcategoría";
    $("#nombrePadre").prop("disabled", true);
}

function openModalSubCategoriaEdit() {
    let idSubCategoria = $("#idSubCategoria").val();

    if (idSubCategoria === null || idSubCategoria === "" || idSubCategoria === undefined) {
        new Toast('Seleccione una subcategoría');
        return;
    }

    document.querySelector('#titleModalSubCategoria').innerHTML = "Actualizar subcategoría";
    sendRequest(base_url + "Tematica/getCategoria/" + idSubCategoria, "GET", null, function (rpta) {
        if (rpta.estado) {
            let datos = rpta.datos;
            $("#idPadre").val(datos.id_padre);
            $("#nombrePadre").val(datos.nombre_padre);
            $("#nombrePadre").prop("disabled", true);
            $("#nombreSubCategoria").val(datos.nombre);
            $("#nombreSubCategoria").focus();
            $("#visibleSubCategoria").val((datos.visible) ? 1 : 0);

            new Fancybox([{ src: "#modalFormSubCategoria" }]);
        }
        else {
            new Toast(`Error: ${rpta.mensaje}`);
        }
    });
}

function openModalSubCategoriaDel() {
    let idSubCategoriaDel = $("#idSubCategoriaDel").val();
    if (idSubCategoriaDel === 0 || idSubCategoriaDel === null || idSubCategoriaDel === undefined || idSubCategoriaDel === "") {
        new Toast('Seleccione una subcategoría');
        return;
    }
    new Fancybox([{ src: "#modalFormSubCategoriaDel" }]);
}