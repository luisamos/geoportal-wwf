<?php
  headerPublic($data);
?>

<!-- Aqui escribir script si es que se necesita -->

<!-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
   integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
   crossorigin=""/> -->

<!-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css" integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ==" crossorigin="" /> -->




<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />

<!-- <link rel="stylesheet" href="<?= media(); ?>/public/Mapa/src/leaflet-ruler.css" /> -->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/gokertanrisever/leaflet-ruler@master/src/leaflet-ruler.css"
    integrity="sha384-P9DABSdtEY/XDbEInD3q+PlL+BjqPCXGcF8EkhtKSfSTr/dS5PBKa9+/PMkW2xsY" crossorigin="anonymous">

<link rel="stylesheet" href="<?= media(); ?>/public/Mapa/src/leaflet.draw.css" />

<style>
.leaflet-popup-content {
    width: 260px;
    height: 240px;
    overflow-y: scroll;
}
</style>

<?php header_body_Public($data);?>

<div id="content" role="main">
    <div class="window w-layer active" id="window--layers">
        <div class="window--inner">
            <div class="window--header"><img src="<?= media() ?>/public/img/layers.png" alt="" height="30"> Cartografía
            </div>

            <div class="window-body">
                <div style="margin-left:10px; margin-bottom:10px;"></div>
                <div class="filter-search">
                    <input type="text" placeholder="Buscar capa ..." id="txtBuscaCapa" class="filter-search__input">
                    <button id="btnBuscaCapa" class="filter-search__button">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6.51562 0.0809631C8.23953 0.0809631 9.89283 0.765782 11.1118 1.98477C12.3308 3.20376 13.0156 4.85706 13.0156 6.58096C13.0156 8.19096 12.4256 9.67096 11.4556 10.811L11.7256 11.081H12.5156L17.5156 16.081L16.0156 17.581L11.0156 12.581V11.791L10.7456 11.521C9.60562 12.491 8.12563 13.081 6.51562 13.081C4.79172 13.081 3.13842 12.3961 1.91943 11.1772C0.700444 9.95817 0.015625 8.30487 0.015625 6.58096C0.015625 4.85706 0.700444 3.20376 1.91943 1.98477C3.13842 0.765782 4.79172 0.0809631 6.51562 0.0809631ZM6.51562 2.08096C4.01562 2.08096 2.01562 4.08096 2.01562 6.58096C2.01562 9.08096 4.01562 11.081 6.51562 11.081C9.01562 11.081 11.0156 9.08096 11.0156 6.58096C11.0156 4.08096 9.01562 2.08096 6.51562 2.08096Z"
                                fill="black" />
                        </svg>
                    </button>
                </div>

                <div class="layers-list" id="divLeyendaNew2_bb">


                </div>




                <div class="layers-list" id="divLeyendaNew2">
                    <!-- <div class="item-layer">
                    <label>
                      <input type="checkbox" name="">Capas 01
                    </label>
                  </div>
                  <div class="item-layer">
                    <label>
                      <input type="checkbox" name="" checked>Capas 01
                    </label>
                  </div>
                  <div class="item-layer">
                    <label>
                      <input type="checkbox" name="" checked>Capas 01
                    </label>
                  </div>
                  <div class="item-layer">
                    <label>
                      <input type="checkbox" name="" checked>Capas 01
                    </label>
                  </div>
                  <div class="item-layer">
                    <label>
                      <input type="checkbox" name="">Capas 01
                    </label>
                  </div>
                  <div class="item-layer">
                    <label>
                      <input type="checkbox" name="">Capas 01
                    </label>
                  </div> -->
                </div>
            </div>
        </div>
    </div>
    <div class="map-container">
        <div style="z-index: 500 !important;" class="toolbar">
            <button class="btn-toolbar btn-layers"
                onclick="fn(this, event, {method:'toggle', to:'body', class: 'hide-layers'})"><img
                    src="<?= media() ?>/public/img/layers.png" alt=""></button>

            <button class="btn-toolbar btn-geolocation"
                onclick="fn(this, event, {method:'toggle', to:'#window--ubicacion'})"><img
                    src="<?= media() ?>/public/img/marque.png" alt=""></button>

            <button class="btn-toolbar btn-geolocation"
                onclick="fn(this, event, {method:'toggle', to:'#window--cargadata'})"><img
                    src="<?= media() ?>/public/img/upload.png" alt=""></button>




        </div>



        <div style="z-index: 501 !important; display: none;" class="search-tools">
            <div class="row">
                <div class="flex g-20 align-center">
                    <div class="label-search">Buscar proyectos </div>
                    <input class="input-search" type="text" placeholder="Buscar">
                </div>
            </div>
            <div class="row">
                <div class="flex g-20 align-center">
                    <div class="label-search">Filtros</div>
                    <select class="input-search">
                        <option value="1">opcion 01</option>
                        <option value="2">opcion 02</option>
                        <option value="3">opcion 03</option>
                        <option value="4">opcion 04</option>
                    </select>
                </div>
            </div>
        </div>



        <div class="window draggable" id="window--login"
            style="width:400px; left: 60px; top: 60px; z-index: 504 !important;">
            <div class="window--inner">
                <div class="window--header">Más datos
                    <div class="window--close" onclick="fn(this, event, {method:'toggle', to:'#window--login'})"><svg
                            width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41L17.59 5Z"
                                fill="#182233" />
                        </svg></div>
                </div>
                <div class="window-body">

                    Para vizualizar más información ingrese sus credenciales.<br><br>
                    <div class="row justify-center mr-bottom-15 flex">
                        <div class="flex g-20 align-center">
                            <div class="label-search" style="width: 100px;">Usuario: </div>
                            <input class="input-search mr-bottom-0" id="txtUserQryxx" type="text" placeholder="">
                        </div>

                    </div>

                    <div class="row justify-center mr-bottom-15 flex">
                        <div class="flex g-20 align-center">
                            <div class="label-search" style="width: 100px;">Clave: </div>
                            <input class="input-search mr-bottom-0" id="txtClaveQryxx" type="password" placeholder="">
                        </div>

                    </div>



                    <div id="panel--1vvvv" role="tabpanel" tabindex="0" aria-labelledby="tab-lastnews-2xxx">

                        <div class="row justify-center">
                            <div class="flex align-center justify-center">
                                <button class="btn btn--regular bg-blue" id="btnQryMasDataxxx">Consultar</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="window draggable" id="window--ubicacion"
            style="width:400px; right: 30px; top: 40%; z-index: 504 !important;">
            <div class="window--inner">
                <div class="window--header">Ubicación
                    <div class="window--close" onclick="fn(this, event, {method:'toggle', to:'#window--ubicacion'})">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41L17.59 5Z"
                                fill="#182233" />
                        </svg>
                    </div>
                </div>

                <div class="window-body">
                    <div class="tabs">
                        <div class="x-tab ccenter" role="tablist" aria-label="">
                            <button id="tab-lastnews-1" role="tab" aria-selected="true" aria-controls="panel--1"
                                tabindex="0" onclick="xsl.ui.tab(this, event)"><span>Punto referencia</span></button>

                        </div>
                    </div>

                    <div id="panel--1" role="tabpanel" tabindex="0" aria-labelledby="tab-lastnews-1">
                        <div class="row justify-center mr-bottom-15 flex">
                            <div class="flex g-20 align-center">
                                <div class="label-search" style="width: 100px;"> Longitud: </div>
                                <input class="input-search mr-bottom-0" id="txtlon_ubi" type="text" placeholder="0">
                            </div>
                        </div>
                        <div class="row justify-center mr-bottom-15 flex">
                            <div class="flex g-20 align-center">
                                <div class="label-search" style="width: 100px;">Latitud: </div>
                                <input class="input-search mr-bottom-0" id="txtlat_ubi" type="text" placeholder="0">
                            </div>
                        </div>

                        <div class="row justify-center">
                            <div class="flex align-center justify-center">
                                <button class="btn btn--small bg-blue" id="btnUbicaPto">Ubicar</button>&nbsp;
                                <button class="btn btn--small bg-blue" id="btnBorraPtoMap">Borrar</button>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>

        <div class="window draggable" id="window--cargadata"
            style="width:500px; right: 30px; top: 25%; z-index: 504 !important;">
            <div class="window--inner">
                <div class="window--header">Cargar Información
                    <div class="window--close" onclick="fn(this, event, {method:'toggle', to:'#window--cargadata'})">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41L17.59 5Z"
                                fill="#182233" />
                        </svg>
                    </div>
                </div>

                <div class="window-body">

                    <div id="panel--2" style="font-size:13px;" tabindex="1" aria-labelledby="tab-lastnews-2">
                        <form enctype="multipart/form-data" style="margin-top: 1px;" method="post"
                            name="uploadFormAdjunta" id="uploadFormAdjunta">
                            <div class="mr-bottom-20">

                                <label class="custom-file-upload">1. Seleccionar el formato :
                                    <select id="cboTipoArchivo" class="input-search">
                                        <option value="shape">Shape File</option>
                                        <option value="kml">Kml</option>

                                    </select></label>
                                <!-- <label class="custom-file-upload" for="file-upload">Seleccionar archivo</label> -->
                                <label class="custom-file-upload">2. Ubicar archivo </label>
                                <div style="padding-left: 5px;">
                                    <input id="file-upload" style="width:280px !important; " name="file-upload"
                                        type="file">

                                    <!-- <button  class="btn btn--small bg-blue" id="btnFileUpload" style="display: none;">Cargar archivo</button>                            -->
                                </div>

                            </div>
                            <div class="text-center">


                            </div>
                        </form>
                        <center><button style="display: none;" class="btn btn--small bg-blue"
                                id="btnBorrarLoaded">Borrar</button></center>

                        <div style="max-height:200px; width:100%; overflow-x: scroll;" id="DivListaDatosLoad">
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <div style="z-index: 505 !important;" class="window w-bottom-flip" id="window--chart">
            <div class="window--inner">
                <div class="window--header">Estadísticos
                    <div class="window--close" onclick="fn(this, event, {method:'toggle', to:'#window--chart'})"><svg
                            width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41L17.59 5Z"
                                fill="#182233" />
                        </svg></div>
                </div>
                <div class="window-body"><img src="<?= media() ?>/public/tmp/grouped-column-chart-reference-small.png"
                        alt="" height="270"><img
                        src="<?= media() ?>/public/tmp/grouped-column-chart-reference-small.png" alt="" height="270">
                </div>
            </div>
        </div>
        <div class="info-cords">X: 133038282700 | Y: 1345454650050</div>
        <div id="map">

        </div>
        <div id="divUTM"
            style="position: absolute;    right: 50px;    bottom: 20px;    z-index: 10000;    background: rgba(0, 0, 0, .5);    color: #dbdbdb;    font-size: 13px;    padding: 5px 10px;    line-height: 20px;">
        </div>
    </div>
</div>




<div class="lbox" id="modalFormMetadata">
    <div class="lbox--title" id="titleModalMetadata">Información</div>
    <form name="formMetadata" id="formMetadata">

        <input type="hidden" name="id_usuario_del" id="id_usuario_del">

        <div class="grid">

            <div class="col--12">
                <span id="txtDescripcionInfoLayers"></span>
                <div class="text-center"><br>
                    <!-- <button class="btn btn--small">Si, eliminar!</button> -->
                    <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> Ok</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="lbox" id="modalFormMasInfoLogin">
    <div class="lbox--title" id="titleModal">Mas información</div>

    <div class="grid">

        <div class="col--12">

            Para vizualizar información cartográfica adicional debe ingrese sus credenciales.<br><br>
            <div class="row justify-center mr-bottom-15 flex">
                <div class="flex g-20 align-center">
                    <div class="label-search" style="width: 100px;">Usuario: </div>
                    <input class="input-search mr-bottom-0" id="txtUserQry" type="text" placeholder="">
                </div>

            </div>

            <div class="row justify-center mr-bottom-15 flex">
                <div class="flex g-20 align-center">
                    <div class="label-search" style="width: 100px;">Clave: </div>
                    <input class="input-search mr-bottom-0" id="txtClaveQry" type="password" placeholder="">
                </div>



            </div>



            <div id="panel--1x" role="tabpanel" tabindex="0" aria-labelledby="tab-lastnews-2xxx">

                <div class="row justify-center">
                    <div class="flex align-center justify-center">
                        <label style="color:red;" id="lblResultMasLayers"></label><br>

                    </div>
                </div>

                <div class="row justify-center">
                    <div class="flex align-center justify-center">

                        <button class="btn btn--regular bg-blue" id="btnQryMasData">Consultar</button>
                    </div>
                </div>
            </div>

            <div class="text-center"><br>
                <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> Cancelar</button>
            </div>
        </div>
    </div>

</div>



<div class="lbox" id="modalFormDescargaLayer">
    <div class="lbox--title" id="titleModal">Descarga de información </div>



    <div class="grid">

        <div class="col--12">

            Se procederá a descargar la capa <span id="lblLayerToDow"></span> <br><br>

            <div id="panel--1xxx" role="tabpanel" tabindex="0" aria-labelledby="tab-lastnews-2xxx">

                <div class="row justify-center">
                    <div class="flex align-center justify-center">
                        <button class="btn btn--regular bg-blue" id="btnExecDownLayer">DESCARGAR</button>
                    </div>
                </div>

            </div>

            <div class="text-center"><br>
                <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> Cancelar</button>
            </div>

        </div>
    </div>

</div>








<script src="<?= media(); ?>/public/js/jquery.min.js" defer></script>
<script src="<?= media(); ?>/public/js/jquery-ui.min.js" defer></script>
<script src="<?= media(); ?>/public/js/fancybox.umd.js" defer></script>
<script src="<?= media(); ?>/public/js/app.js" defer></script>


<!-- <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
   integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
   crossorigin=""></script> -->

<!-- <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet-src.js" integrity="sha512-WXoSHqw/t26DszhdMhOXOkI7qCiv5QWXhH9R7CgvgZMHz1ImlkVQ3uNsiQKu5wwbbxtPzFXd1hK4tzno2VqhpA==" crossorigin=""></script> -->

<!-- <script src="https://unpkg.com/esri-leaflet@3.0.3/dist/esri-leaflet.js"integrity="sha512-kuYkbOFCV/SsxrpmaCRMEFmqU08n6vc+TfAVlIKjR1BPVgt75pmtU9nbQll+4M9PN2tmZSAgD1kGUCKL88CscA=="crossorigin=""></script> -->


<!-- Load Esri Leaflet from CDN -->
<!-- <script src="https://unpkg.com/esri-leaflet-vector@3.1.1/dist/esri-leaflet-vector.js"integrity="sha512-7rLAors9em7cR3/583gZSvu1mxwPBUjWjdFJ000pc4Wpu+fq84lXF1l4dbG4ShiPQ4pSBUTb4e9xaO6xtMZIlA=="crossorigin=""></script> -->


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>


<!-- Load Esri Leaflet from CDN -->
<script src="https://unpkg.com/esri-leaflet@3.0.10/dist/esri-leaflet.js"></script>

<!-- Load Esri Leaflet Vector from CDN -->
<script src="https://unpkg.com/esri-leaflet-vector@4.1.0/dist/esri-leaflet-vector.js" crossorigin=""></script>


<script src="<?= media(); ?>/public/Mapa/src/Leaflet.draw.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/Leaflet.Draw.Event.js"></script>



<script src="<?= media(); ?>/public/Mapa/src/Toolbar.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/Tooltip.js"></script>

<script src="<?= media(); ?>/public/Mapa/src/ext/GeometryUtil.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/ext/LatLngUtil.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/ext/LineUtil.Intersect.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/ext/Polygon.Intersect.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/ext/Polyline.Intersect.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/ext/TouchEvents.js"></script>

<script src="<?= media(); ?>/public/Mapa/src/draw/DrawToolbar.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/draw/handler/Draw.Feature.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/draw/handler/Draw.SimpleShape.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/draw/handler/Draw.Polyline.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/draw/handler/Draw.Circle.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/draw/handler/Draw.Marker.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/draw/handler/Draw.Polygon.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/draw/handler/Draw.Rectangle.js"></script>

<script src="<?= media(); ?>/public/Mapa/src/edit/EditToolbar.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/edit/handler/EditToolbar.Edit.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/edit/handler/EditToolbar.Delete.js"></script>

<script src="<?= media(); ?>/public/Mapa/src/Control.Draw.js"></script>



<script src="<?= media(); ?>/public/Mapa/src/edit/handler/Edit.Poly.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/edit/handler/Edit.SimpleShape.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/edit/handler/Edit.Circle.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/edit/handler/Edit.Rectangle.js"></script>
<script src="<?= media(); ?>/public/Mapa/src/edit/handler/Edit.Marker.js"></script>


<script src="<?= media(); ?>/public/Mapa/leaflet-measure.js"></script>

<script src="<?= media(); ?>/public/Mapa/src/leaflet-ruler.js"></script>




<script src="<?= media(); ?>/public/Mapa/L.KML.js" defer></script>
<script src="<?= media(); ?>/public/Mapa/utm2lat.js" defer></script>
<script src="<?= media(); ?>/public/Mapa/functions_mapa.js" defer></script>


<script>
objRef = document.body;
objRef.classList.remove('home');
objRef.classList.add('map');

objRef.setAttribute("data-x", "tab, dragable");
</script>