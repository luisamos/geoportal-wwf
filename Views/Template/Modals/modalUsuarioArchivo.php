<div class="lbox" id="modalFormUsuarioArchivo">
  <div class="lbox--title"  id="titleModal">Formulario Permiso Archivo</div>
  <form action="post" id="formUsuarioArchivo" name="formUsuarioArchivo"> 

    <div class="grid">

      <div class="col--12" style="text-align:center;"><b>------------------ Seleccionar Usuario ------------------</b></div>

      <div class="col--2">Nombre de usuario:</div>
      <div class="col--8">
        <input type="text" name="nom_usuario" id="nom_usuario" maxlength="15" required style="width:100%">
      </div>
      <div class="col--2">
        <button class="btn btn--small" type="button" onclick="fntSearchUsuario()">Buscar</button>
      </div>

      <div class="col--2">Usuario:</div>
      <div class="col--3">
        <input type="hidden" name="id_usuario" id="id_usuario" required>
        <input type="text" name="usuario" id="usuario" required readonly style="width:100%">
      </div>
      <div class="col--2">Persona:</div>
      <div class="col--5">
        <input type="text" name="persona" id="persona" required readonly style="width:100%">
      </div>
      <br>
      <div class="col--12" style="text-align:center;"><b>------------------ Seleccionar Archivo ------------------</b></div>
      <div class="col--12" style="text-align:center;">                  (solo archivos Privados)                  </div>

      <div class="col--2">Código del Archivo:</div>
      <div class="col--8">
        <input type="text" id="codigo" name="codigo" required style="width:100%">
      </div>
      <div class="col--2">
        <button class="btn btn--small" type="button" onclick="fntSearchArchivo()">Buscar</button>
      </div>

      <div class="col--2">Archivo:</div>
      <div class="col--10">
        <input type="hidden" name="id_archivo" id="id_archivo" required>
        <input type="text" name="archivo" id="archivo" required readonly style="width:100%">
      </div>
      
      <div class="col--12">
        <div class="text-center"><br>
          <button class="btn btn--small">Guardar</button>
          <button class="btn btn--small bg-gray" onclick="Fancybox.close()"> Cancelar</button>
        </div>
      </div>
    </div>
  </form>
</div>


<div class="lbox" id="modalFormUsuarioArchivoDel">
  <div class="lbox--title" id="titleModal">Eliminar Permiso Archivo</div>
  <form name="formUsuarioArchivoDel" id="formUsuarioArchivoDel">

    <input type="hidden" name="id_usuario_archivo_del" id="id_usuario_archivo_del">

    <div class="grid">
      <div class="col--12">¿Realmente quiere eliminar el permiso al Archivo?</div>     
      <div class="col--12">
        <div class="text-center"><br>
          <button class="btn btn--small">Si, eliminar!</button>
          <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> No, cancelar!</button>
        </div>
      </div>
    </div>
  </form>
</div>
