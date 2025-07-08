<div class="lbox" id="modalFormCapaGeografica">
    <div class="lbox--title" id="titleModal">Formulario Capa Geográfica</div>
    <form action="post" name="formCapaGeografica" id="formCapaGeografica">
        <input type="hidden" name="id_capa_geografica" id="id_capa_geografica">
        <input type="hidden" name="tipo" id="tipo">
        <div class="grid">
            <div class="col--12">
                <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>
            </div>
            <div class="col--4">Temática*:</div>
            <div class="col--8">
                <select name="id_tematica" id="id_tematica" required>
                </select>
            </div>
            <div class="col--4">Archivo:</div>
            <div class="col--8">
                <input type="file" name="archivo" id="archivo">
            </div>
            <div class="col--4">Nombre*:</div>
            <div class="col--8">
                <input type="text" name="nombre" id="nombre" required>
            </div>
            <div class="col--4">Alias*:</div>
            <div class="col--8">
                <input type="text" name="alias" id="alias" required>
            </div>
            <div class="col--4">Visible:</div>
            <div class="col--8">
                <select name="visible" id="visible">
                    <option value="1" selected>Si</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="col--12">
                <div id="progressContainer" style="display:none;">
                    <div id="progressBar"></div>
                    <div id="progressText">0%</div>
                </div>
            </div>
        </div>
        <div class="col--12">
            <div class="text-center"><br>
                <button class="btn btn--small" id="btnGuardar">Guardar</button>
                <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()">Cancelar</button>
                <div id="spinner" style="display:none; margin-top: 10px;">
                    <span>Cargando...</span>
                </div>
            </div>
        </div>
    </form>
</div>


<div class="lbox" id="modalFormCapaGeograficaDel">
    <div class="lbox--title" id="titleModalDel">Eliminar Capa Geográfica</div>
    <form name="formCapaGeograficaDel" id="formCapaGeograficaDel">

        <input type="hidden" name="id_capa_geografica_del" id="id_capa_geografica_del">

        <div class="grid">
            <div class="col--12">¿Realmente quiere eliminar la Capa Geográfica?</div>

            <div class="col--12">
                <div class="text-center"><br>
                    <button class="btn btn--small">Si, eliminar!</button>
                    <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> No,
                        cancelar!</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="lbox" id="modalFormSLD">
    <div class="lbox--title" id="titleModalSLD">Actualizar estilo SLD</div>
    <form name="formCapaGeograficaSLD" id="formCapaGeograficaSLD">

        <input type="hidden" name="id_capa_geografica_sld" id="id_capa_geografica_sld">
        <input type="hidden" name="nombre_tabla" id="nombre_tabla">
        <div class="grid">
            <div class="col--12">
                <input type="file" id="archivoSLD" name="archivoSLD" accept=".sld" />
            </div>

            <div class="col--12">
                <div class="text-center"><br>
                    <button class="btn btn--small">Guardar</button>
                    <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> Cancelar</button>
                </div>
            </div>
        </div>
    </form>
</div>