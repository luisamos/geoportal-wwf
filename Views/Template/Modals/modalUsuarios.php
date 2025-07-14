<div class="lbox" id="modalFormUsuarios">
  <div class="lbox--title" id="titleModal">Formulario Usuarios</div>
  <form action="post" id="formUsuarios" name="formUsuarios">

    <input type="hidden" name="id_usuario" id="id_usuario">

    <div class="grid">
      <div class="col--12" style="text-align:center;">------------------ Seleccionar Persona ------------------</div>

      <div class="col--4">Nº Documento:</div>
      <div class="col--4">
        <input type="text" name="num_documento" id="num_documento" 
        onkeypress="return acceptNum(event);"
        maxlength="8" required>
      </div>
      <div class="col--4">
        <button class="btn btn--small" type="button" onclick="fntSearchPersona()">Buscar</button>
      </div>

      <div class="col--4">Persona:</div>
      <div class="col--8">
        <input type="hidden" name="id_persona" id="id_persona" required>
        <input type="text" name="persona" id="persona" required readonly>
      </div>

      <div class="col--12" style="text-align:center;">------------------ Datos del Usuario ------------------</div>

      <div class="col--6">Rol:</div>
      <div class="col--6">
        <select name="id_rol" id="id_rol">
         
        </select>
      </div>

      <div class="col--6">Usuario:</div>
      <div class="col--6">
        <input type="text" id="usuario" name="usuario" required>
      </div>
      <div class="col--6">Clave:</div>
      <div class="col--6">
        <input type="password" name="clave" id="clave">
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


<div class="lbox" id="modalFormUsuariosDel">
  <div class="lbox--title" id="titleModal">Eliminar Usuario</div>
  <form name="formUsuariosDel" id="formUsuariosDel">

    <input type="hidden" name="id_usuario_del" id="id_usuario_del">

    <div class="grid">
      <div class="col--12">¿Realmente quiere eliminar el Usuario?</div>
      
     
      <div class="col--12">
        <div class="text-center"><br>
          <button class="btn btn--small">Si, eliminar!</button>
          <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> No, cancelar!</button>
        </div>
      </div>
    </div>
  </form>
</div>