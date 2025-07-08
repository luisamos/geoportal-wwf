  <div class="lbox" id="modalFormRoles">
    <div class="lbox--title"  id="titleModal">Formulario Rol</div>
    <form action="post" id="formRoles" name="formRoles"> 

      <input type="hidden" name="id_rol" id="id_rol">

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


<div class="lbox" id="modalFormRolesDel">
  <div class="lbox--title" id="titleModal">Eliminar Rol</div>
  <form name="formRolesDel" id="formRolesDel">

    <input type="hidden" name="id_rol_del" id="id_rol_del">

    <div class="grid">
      <div class="col--12">¿Realmente quiere eliminar el Rol?</div>
      
     
      <div class="col--12">
        <div class="text-center"><br>
          <button class="btn btn--small">Si, eliminar!</button>
          <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> No, cancelar!</button>
        </div>
      </div>
    </div>
  </form>
</div>