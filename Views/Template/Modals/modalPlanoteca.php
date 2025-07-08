  <div class="lbox" id="modalFormPlanoteca">
    <div class="lbox--title"  id="titleModal">Formulario Planoteca</div>
    <form action="post" id="formPlanoteca" name="formPlanoteca"> 

      <input type="hidden" name="id_planoteca" id="id_planoteca">

      <input type="hidden" id="archivo_actual" name="archivo_actual" value="">
      <input type="hidden" id="archivo_remove" name="archivo_remove" value="0">

      <input type="hidden" id="foto_actual" name="foto_actual" value="">
      <input type="hidden" id="foto_remove" name="foto_remove" value="0">

      <div class="grid">
        <div class="col--4">Programa:</div>
        <div class="col--8">
          <select name="id_programa" id="id_programa" required>
          </select>
        </div>
        <div class="col--4">Código:</div>
        <div class="col--8">
          <input type="text" name="codigo" id="codigo"  required>
        </div>
        <div class="col--4">Nombre:</div>
        <div class="col--8">
          <input type="text" name="nombre" id="nombre"  required>
        </div>
        <div class="col--4">Tag's:</div>
        <div class="col--8">
          <input type="text" name="tag" id="tag" required>
        </div>
        <div class="col--4">Archivo:</div>
        <div class="col--8">
          <input type="file" name="archivo" id="archivo" require="">
          <div id="form_alert"></div>
        </div>

         <div class="col--12 mr-bottom-20">
          <div class="grid g-20">

            <div class="col--6">
              <small>Imagen 1 (570 x 380)</small> <br>
              <div class="photo">
                <label for="foto">Formatos: .jpg .png .jpge</label>
                <div class="prevPhoto">
                  <span class="delPhoto notBlock">X</span>
                  <label for="foto"></label>
                  <div>
                    <img id="img" src="<?= media(); ?>/images/uploads/plano_img.jpg">
                  </div>
                </div>
                <div class="upimg">
                  <input type="file" name="foto" id="foto">
                </div>
                <div id="form_alert"></div>
              </div>
            </div>
          </div>
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


<div class="lbox" id="modalFormPlanotecaDel">
  <div class="lbox--title" id="titleModal">Eliminar Plano</div>
  <form name="formPlanotecaDel" id="formPlanotecaDel">

    <input type="hidden" name="id_planoteca_del" id="id_planoteca_del">

    <div class="grid">
      <div class="col--12">¿Realmente quiere eliminar la Plano?</div>
      
     
      <div class="col--12">
        <div class="text-center"><br>
          <button class="btn btn--small">Si, eliminar!</button>
          <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> No, cancelar!</button>
        </div>
      </div>
    </div>
  </form>
</div>