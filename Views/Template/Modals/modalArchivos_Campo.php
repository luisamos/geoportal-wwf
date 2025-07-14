<div class="lbox" id="modalFormArchivosCampo">
  <div class="lbox--title"  id="titleModal">Formulario Archivo Campo</div>
  <form action="post" id="formArchivosCampo" name="formArchivosCampo" enctype="multipart/form-data"> 

    <input type="hidden" name="id_archivo_campo" id="id_archivo_campo">
    <input type="hidden" id="archivo_actual" name="archivo_actual" value="">
    <input type="hidden" id="archivo_remove" name="archivo_remove" value="0">

    <div class="grid">
      <div class="col--4">Tipo:</div>
      <div class="col--8">
        <select name="id_tipo" id="id_tipo" required>
        </select>
      </div>
      
      <div class="col--4">Nombre:</div>
      <div class="col--8">
      <input type="text" name="nombre" id="nombre"  required>
      </div>

      <div class="col--4">Archivo (.jpg ó .mp4) max 20MB:</div>
      <div class="col--8">
        
        <!-- <input type="file" name="archivo" id="archivo" require="">
        <div id="form_alert"></div> -->

          <div class="prevPhoto">
            <span class="delPhoto notBlock">X</span>
            <label for="archivo"></label>
            <div>
              <img id="img" src="<?= media(); ?>/images/uploads/portada_categoria.png">
            </div>
          </div>
          <div class="upimg">
            <input type="file" name="archivo" id="archivo">
          </div>
          <div id="form_alert"></div>
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


<div class="lbox" id="modalFormArchivosCampoDel">
  <div class="lbox--title" id="titleModal">Eliminar Archivo</div>
  <form name="formArchivosCampoDel" id="formArchivosCampoDel">

    <input type="hidden" name="id_archivo_campo_del" id="id_archivo_campo_del">

    <div class="grid">
      <div class="col--12">¿Realmente quiere eliminar el Archivo?</div>     
      <div class="col--12">
        <div class="text-center"><br>
          <button class="btn btn--small">Si, eliminar!</button>
          <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> No, cancelar!</button>
        </div>
      </div>
    </div>
  </form>
</div>
