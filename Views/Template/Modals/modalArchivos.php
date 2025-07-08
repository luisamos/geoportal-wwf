<div class="lbox" id="modalFormArchivos">
  <div class="lbox--title"  id="titleModal">Formulario Archivo</div>
  <form action="post" id="formArchivos" name="formArchivos"> 

    <input type="hidden" name="id_archivo" id="id_archivo">
    <input type="hidden" id="archivo_actual" name="archivo_actual" value="">
    <input type="hidden" id="archivo_remove" name="archivo_remove" value="0">

    <div class="grid">
      <div class="col--4">Tipo:</div>
      <div class="col--8">
        <select name="id_tipo" id="id_tipo" required>
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
      <div class="col--4">Archivo:</div>
      <div class="col--8">
        <input type="file" name="archivo" id="archivo" require="">
        <div id="form_alert"></div>
      </div>
      <div class="col--4">Acceso:</div>
      <div class="col--8">
        <select name="acceso" id="acceso">
          <option value="1">Publico</option>
          <option value="2">Privado</option>
        </select>
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


<div class="lbox" id="modalFormArchivosDel">
  <div class="lbox--title" id="titleModal">Eliminar Archivo</div>
  <form name="formArchivosDel" id="formArchivosDel">

    <input type="hidden" name="id_archivo_del" id="id_archivo_del">

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
