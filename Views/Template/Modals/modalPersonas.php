  <div class="lbox" id="modalFormPersonas">
    <div class="lbox--title"  id="titleModal">Formulario Persona</div>
    <form action="post" id="formPersonas" name="formPersonas"> 

      <input type="hidden" name="id_persona" id="id_persona">

      <div class="grid">
        <div class="col--4">DNI:</div>
        <div class="col--8">
          <input type="text" name="num_documento" id="num_documento" 
          onkeypress="return acceptNum(event);" 
          maxlength="8" 
          required>
        </div>
        <div class="col--4">Nombres:</div>
        <div class="col--8">
          <input type="text" name="nombres" id="nombres"  
          required>
        </div>
        <div class="col--4">Apellidos:</div>
        <div class="col--8">
          <input type="text" name="apellidos" id="apellidos"  
          required>
        </div>
        <div class="col--4">Celular:</div>
        <div class="col--8">
          <input type="text" name="celular" id="celular" 
          onkeypress="return acceptNum(event);"
          maxlength="9"  
          required>
        </div>
        <div class="col--4">Email:</div>
        <div class="col--8">
          <input type="email" name="email" id="email"  
          required>
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


<div class="lbox" id="modalFormPersonasDel">
  <div class="lbox--title" id="titleModal">Eliminar Persona</div>
  <form name="formPersonasDel" id="formPersonasDel">

    <input type="hidden" name="id_persona_del" id="id_persona_del">

    <div class="grid">
      <div class="col--12">¿Realmente quiere eliminar la Persona?</div>
      
     
      <div class="col--12">
        <div class="text-center"><br>
          <button class="btn btn--small">Si, eliminar!</button>
          <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> No, cancelar!</button>
        </div>
      </div>
    </div>
  </form>
</div>