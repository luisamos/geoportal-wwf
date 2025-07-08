  <div class="lbox" id="modalFormTipo_Archivo">
    <div class="lbox--title"  id="titleModal">Formulario Tipo Archivo</div>
    <form action="post" id="formTipo_Archivo" name="formTipo_Archivo"> 

      <input type="hidden" name="id_tipo_archivo" id="id_tipo_archivo">

      <div class="grid">
        <div class="col--4">Nombre:</div>
        <div class="col--8">
          <input type="text" name="nombre" id="nombre"  required>
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


<div class="lbox" id="modalFormTipo_ArchivoDel">
  <div class="lbox--title" id="titleModal">Eliminar Tipo Archivo</div>
  <form name="formTipo_ArchivoDel" id="formTipo_ArchivoDel">

    <input type="hidden" name="id_tipo_archivo_del" id="id_tipo_archivo_del">

    <div class="grid">
      <div class="col--12">¿Realmente quiere eliminar el Tipo Archivo?</div>
      
     
      <div class="col--12">
        <div class="text-center"><br>
          <button class="btn btn--small">Si, eliminar!</button>
          <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> No, cancelar!</button>
        </div>
      </div>
    </div>
  </form>
</div>