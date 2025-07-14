

var map = L.map("map").setView([-9.774, -74.562], 5);
var ARRAY_LAYERS = [];
const apikey = "AAPK82e97a336fbf4a1ab2dae19f782892d3GXyjAXajQ1B3UP30eAZZrUELY_CO8c53lj-W_ATU6uJRp5ciuQthcT_j8IxUcBlM";

var domain = "http://190.117.218.163:8081";

var FLG_GETINFO = false;

var ARRAY_IDENTIFY_RESULT = [];

document.addEventListener('DOMContentLoaded', function () {




    L.esri.Vector.vectorBasemapLayer("arcgis/imagery", {
        apikey: apikey
    }).addTo(map);


    fntGetDatos();



    map.on('click', function (e) {
        //console.log(e.latlng);

        //console.log( map.getSize());
        //console.log( map.getBounds());

        var xy = map.latLngToContainerPoint(e.latlng);
        //console.log(xy);

        w = map.getSize().x;
        h = map.getSize().y;

        //lys = map.getBounds()._northEast.lat + "," + map.getBounds()._northEast.lng + "," + map.getBounds()._southWest.lat + "," + map.getBounds()._southWest.lng;

        bbox = map.getBounds().toBBoxString();
        lys = "geoportalwwf:anp_definitivas";
        x = xy.x;
        y = xy.y;



        if (FLG_GETINFO) {

            sendGetfeatueInfo(w, h, x, y, bbox, lys, e.latlng);
        }


    });



    //"http://190.117.218.163:8081/geoserver/geoportalwwf/wms?"
    //"geoportalwwf:anp_definitivas"


    var lyProyectos = L.tileLayer.wms("http://190.117.218.163:8081/geoserver/geoportalwwf/wms?", {
        layers: "geoportalwwf:anp_definitivas",
        transparent: true,
        format: 'image/png',
        attribution: ""
    });
    lyProyectos.bringToFront();

    map.addLayer(lyProyectos);


    iniControleIndentify();


    if (document.querySelector("#formBitacora")) {
        var formBitacora = document.querySelector("#formBitacora");
        formBitacora.onsubmit = function (e) {
            e.preventDefault();

            var strCampo1 = document.querySelector('#campo1').value;
            var strCampo2 = document.querySelector('#campo2').value;
            if (strCampo1 == '' || strCampo2 == '') {
                swal("Atención", "Todos los campos (*) son obligatorios.", "error");
                return false;
            }
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Bitacora/setBitacora';
            var formData = new FormData(formBitacora);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formBitacora.reset();
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

    if (document.querySelector("#formBitacoraDel")) {
        let formBitacoraDel = document.querySelector("#formBitacoraDel");
        formBitacoraDel.onsubmit = function (e) {
            e.preventDefault();
            let request = (window.XMLHttpRequest) ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Bitacora/delBitacora';
            let formData = new FormData(formBitacoraDel);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        formBitacoraDel.reset();
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



function sendGetfeatueInfo(w, h, x, y, _bbox, lys_, latlng) {

    let PROMISSESS = [];
    let PUNTERO = [];

    let lys = lys_.toString();

    let literal = "";

    params = {
        request: 'GetFeatureInfo',
        service: 'WMS',
        srs: 'EPSG:4326',
        version: '1.1.1',
        bbox: _bbox,
        height: h,
        width: w,
        x: x,
        y: y,
        layers: lys,
        query_layers: lys,
        info_format: 'application/json',
        feature_count: 5
    };
    url = domain + "/geoserver/wms" + L.Util.getParamString(params);
    $.ajax({
        url: url,
        success: function (data, status, xhr) {
            console.log("dataxhr:", data);
            ARRAY_IDENTIFY_RESULT = data.features;

            // $.each(data.features, function(x, feature) {

            //     let vlink=x+1;

            //     literal +=  "<a onclick='showDataInPopup("+x+")' style=' text-decoration:underline; font-size:10px; font-weight:bold !important; color:blue !important;'>"+ vlink + "</a>  - ";

            // });

            literal += '<button class="btn btn--medium bg-blue" data-fancybox data-src="#modalFormBitacora" onclick="openModal()"><div class="btn--icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 12H17V15H20V17H17V20H15V17H12V15H15V12ZM9 14L0 7L9 0L18 7L9 14ZM9 16.54L10 15.75V16C10 16.71 10.12 17.39 10.35 18L9 19.07L0 12.07L1.62 10.81L9 16.54Z" fill="white"/></svg></div>Crear Bitacora </button>';

            literal += "<br><div style='width:200px !important; max-height:200px !important;overflow-y: auto;' id='divResultPopup'> </div>"


            var popup = L.popup()
                .setLatLng(latlng)
                .setContent(literal)
                .openOn(map);


            showDataInPopup(0);
            FLG_GETINFO = false;
            $('.leaflet-container').css('cursor', '');

        },
        error: function (xhr, status, error) {
            //showResults(error);
        }
    });
}


function showDataInPopup(idx) {
    let TheFeature = ARRAY_IDENTIFY_RESULT[idx];
    console.log("TheFeature .>", TheFeature);
    let ind = idx + 1;

    var jsonResponse = TheFeature.properties;
    var literal = "<br><span  style=' padding: 4px 4px 2px 4px; background-color:#7c7c7c;  font-size:10px; color:white !important;'> <span style='padding: 2px 2px 0px 2px; background-color:#fff;color:blue; font-weight:bold;'>" + ind + " </span> &nbsp;&nbsp; " + TheFeature.id.toUpperCase() + "</span><br />";

    literal += "<table >";

    for (var key in jsonResponse) {
        literal += "<tr><td><span style='font-weight:bold !important; color:black !important;'>" + key.toUpperCase() + "</span> :</td> <td>" + jsonResponse[key] + "</td></tr>";
    }

    literal += "</table>";


    document.getElementById("divResultPopup").innerHTML = literal;
    GEOJSON_PolyIdenti.clearLayers();
    let StrPop = "INFORMACION-DATA";
    let the_geom = TheFeature.geometry;
    let formObject = {
        type: "Feature",
        geometry: the_geom,
        properties: {
            name: "Mis coberturas",
            popupContent: StrPop

        }
    };

    GEOJSON_PolyIdenti.addData(formObject);
}

function iniControleIndentify() {

    var ourCustomControl = L.Control.extend({

        options: {
            position: 'topleft'
            //control position - allowed: 'topleft', 'topright', 'bottomleft', 'bottomright'
        },

        onAdd: function (map) {

            var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');

            container.style.backgroundColor = 'white';
            container.style.backgroundImage = "url(Assets/public/img/iconidentidad.png)";
            container.style.backgroundSize = "30px 30px";
            container.style.width = '34px';
            container.style.height = '34px';




            container.onmouseover = function () {
                container.style.borderColor = 'cyan';

            }

            container.onmouseout = function () {
                container.style.border = '2px solid rgba(0,0,0,0.2)';
            }

            container.onclick = function (e) {


                //setExtentPeru();
                FLG_GETINFO = true;
                $('.leaflet-container').css('cursor', 'crosshair');
                e.preventDefault();
                e.stopPropagation();

            }

            return container;
        }

    });

    map.addControl(new ourCustomControl());
}




function graabarBitacoraEspatial() {


    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Bitacora/setBitacora';

    var formData = new FormData();
    formData.append('id_bitacora', 1);
    formData.append('id_proy', 1);
    formData.append('id_elemento', 1);
    formData.append('campo1', 'campo1');
    formData.append('campo2', 'campo2');
    formData.append('campo3', 'campo3');
    formData.append('id_ref', 1);

    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            console.log(objData.msg);
            // if(objData.status)
            // {
            //     formBitacora.reset();
            //     Fancybox.close();
            //     new Toast(objData.msg);
            //     setTimeout(function(){
            //         window.location.reload();
            //     }, 2000);
            // }
            // else{
            //     new Toast("Error: "+objData.msg);
            // }
        }
        return false;
    }

}


function fntEditInfo() {

    const check = document.querySelector('input[name="ckBitacoras"]:checked');
    if (!check) {
        new Toast('seleccione un Bitacora');
        return;
    }

    document.getElementById('id_bitacora').value = check.dataset.id;
    let id_bitacora = check.dataset.id;
    document.querySelector('#titleModal').innerHTML = "Actualizar Bitacora";

    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Bitacora/getBitacora/' + id_bitacora;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {

        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#id_bitacora").value = objData.data.id_bitacora;
                document.querySelector("#campo1").value = objData.data.bitaco_campo1;
                document.querySelector("#campo2").value = objData.data.bitaco_campo2;
                document.querySelector("#campo3").value = objData.data.bitaco_campo3;
                new Fancybox([{ src: "#modalFormBitacora" }]);
            }
            else {
                new Toast("Error: " + objData.msg);
            }
        }
    }
}


function fntAddEstrategia() {

    const check = document.querySelector('input[name="ckBitacoras"]:checked');
    if (!check) {
        new Toast('Seleccione una Bitacora');
        return;
    }

    document.getElementById('id_bitacora').value = check.dataset.id;
    const id_bitacora = check.dataset.id;
    document.getElementById('campo1').value = check.dataset.campo1;
    document.getElementById('campo1').readOnly = true;

    document.querySelector('#titleModal').innerHTML = "Agregar Estrategia";

    new Fancybox([{ src: "#modalFormBitacora" }]);
    document.querySelector("#id_ref").value = id_bitacora;

}


function openModalDel() {
    const check = document.querySelector('input[name="ckBitacoras"]:checked');
    if (!check) {
        new Toast('Seleccione una Bitacora');
        return;
    }

    document.getElementById('id_bitacora_del').value = check.dataset.id;
    new Fancybox([{ src: "#modalFormBitacoraDel" }]);
}

function openModal() {
    document.querySelector('#id_bitacora').value = "";
    document.querySelector('#titleModal').innerHTML = "Nueva Bitacora";
    document.querySelector("#formBitacora").reset();
}



function fntGetDatos() {

    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Bitacora/getDatos/';
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {

        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                console.log(objData.data);
            }
            else {
                new Toast("Error: " + objData.msg);
            }
        }
    }
}