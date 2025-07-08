<div class="lbox" id="modalFormNoticia">
  <div class="lbox--title" id="titleModal">Nueva Noticia</div>
    <form action="post" id="formNoticia" name="formNoticia">

      <input type="hidden" id="id_noticia" name="id_noticia" value="">

      <input type="hidden" id="foto_actual" name="foto_actual" value="">
      <input type="hidden" id="foto_remove" name="foto_remove" value="0">

      <input type="hidden" id="foto_actual2" name="foto_actual2" value="">
      <input type="hidden" id="foto_remove2" name="foto_remove2" value="0">

      <div class="grid">

        <div class="col--12">
          <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>
        </div>

        

        <div class="col--12 mr-bottom-10">
        <small>Tipo *</small><br>
          <select name="tipo" id="tipo">
            <option value="1">Local</option>
            <option value="2">Externa</option>
          </select>
        </div>

        <div class="col--12 mr-bottom-10">
        <small>Url *</small><br>
          <input class="form-control full-width" id="url" name="url" type="text" placeholder="Url" maxlength="400">
        </div>

        <div class="col--12 mr-bottom-10">
        <small>Titulo *</small><br>
          <input class="form-control full-width" id="titulo" name="titulo" type="text" placeholder="Titulo Noticia" onblur="javascript:this.value=this.value.toUpperCase();" maxlength="100">
        </div>


        <div class="col--12 mr-bottom-10">
        <small>Descripción</small><br>
          <div id="dv_editor"></div>
          <script>
              ClassicEditor
                  .create( document.querySelector( '#dv_editor' ), {
                     removePlugins: [ 'ImageUpload', 'EasyImage' ]
                  })
                  .then( newEditor => {
                      editor = newEditor;
                  })
                  .catch( error => {
                      console.error( error );
                  } );

                  // obtener html:
                  // var editorData = editor.getData();
                  // https://ckeditor.com/docs/ckeditor5/latest/installation/getting-started/getting-and-setting-data.html
          </script>
          <!--textarea class="form-control" id="descripcion" name="descripcion" onblur="javascript:this.value=this.value.toUpperCase();" > </textarea-->
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
                    <img id="img" src="<?= media(); ?>/images/uploads/noticia_noticia.png">
                  </div>
                </div>
                <div class="upimg">
                  <input type="file" name="foto" id="foto">
                </div>
                <div id="form_alert"></div>
              </div>
            </div>

            <div class="col--6">
              <small>Imagen 2 (570 x 380)</small> <br>
              <div class="photo">
                <label for="foto2">Formatos: .jpg .png .jpge</label>
                <div class="prevPhoto2">
                  <span class="delPhoto2 notBlock">X</span>
                  <label for="foto2"></label>
                  <div>
                    <img id="img2" src="<?= media(); ?>/images/uploads/noticia_noticia.png">
                  </div>
                </div>
                <div class="upimg">
                  <input type="file" name="foto2" id="foto2">
                </div>
                <div id="form_alert2"></div>
              </div>
            </div>
          </div>
        </div>
      
      
        <div class="col--6">
       
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


<div class="lbox" id="modalFormNoticiaDel">
  <div class="lbox--title" id="titleModal">Eliminar Noticia</div>
  <form name="formNoticiaDel" id="formNoticiaDel">

    <input type="hidden" name="id_noticia_del" id="id_noticia_del">

    <div class="grid">
      <div class="col--12">¿Realmente quiere eliminar la Noticia?</div>
      
     
      <div class="col--12">
        <div class="text-center"><br>
          <button class="btn btn--small">Si, eliminar!</button>
          <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> No, cancelar!</button>
        </div>
      </div>
    </div>
  </form>
</div>