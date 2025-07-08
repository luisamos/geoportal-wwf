  <div class="lbox" id="modalFormProgramas">
    <div class="lbox--title"  id="titleModal">Formulario Programa</div>
    <form action="post" id="formProgramas" name="formProgramas"> 

      <input type="hidden" name="id_programa" id="id_programa">

      <div class="grid">
        <div class="col--4">Nombre:</div>
        <div class="col--8">
          <input type="text" name="nombre" id="nombre"  required>
        </div>
        <div class="col--4">Descripcion:</div>
        <div class="col--8">
        <input type="text" name="descripcion" id="descripcion"  required>
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


<div class="lbox" id="modalFormProgramasDel">
  <div class="lbox--title" id="titleModal">Eliminar Programa</div>
  <form name="formProgramasDel" id="formProgramasDel">

    <input type="hidden" name="id_programa_del" id="id_programa_del">

    <div class="grid">
      <div class="col--12">¿Realmente quiere eliminar el Programa?</div>
      
     
      <div class="col--12">
        <div class="text-center"><br>
          <button class="btn btn--small">Si, eliminar!</button>
          <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> No, cancelar!</button>
        </div>
      </div>
    </div>
  </form>
</div>