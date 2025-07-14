  <div class="lbox" id="modalFormBitacora">
    <div class="lbox--title"  id="titleModal">Formulario Paisajes</div>
    <form action="post" id="formBitacora" name="formBitacora"> 

      <input type="hidden" name="id_bitacora" id="id_bitacora">

      <input type="hidden" name="id_proy" id="id_proy">

      <div class="grid">
        <div class="col--4">Proyecto:</div>
        <div class="col--8">
          <input type="text" name="nombre_proyecto" id="nombre_proyecto"  required readonly>
        </div>
        <div class="col--4">Campo1:</div>
        <div class="col--8">
          <input type="text" name="nombre_proyecto" id="nombre_proyecto" maxlength="200"  required>
        </div>
        <div class="col--4">Campo2:</div>
        <div class="col--8">
          <input type="text" name="campo2" id="campo2" maxlength="500" required>
        </div>
        <div class="col--4">Campo3:</div>
        <div class="col--8">
          <textarea class="form-control" id="campo3" name="campo3" onblur="javascript:this.value=this.value.toUpperCase();" required=""> </textarea>
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


<div class="lbox" id="modalFormBitacoraDel">
  <div class="lbox--title" id="titleModal">Eliminar Bitacora</div>
  <form name="formBitacoraDel" id="formBitacoraDel">

    <input type="hidden" name="id_bitacora_del" id="id_bitacora_del">

    <div class="grid">
      <div class="col--12">¿Realmente quiere eliminar el Bitacora?</div>
      
     
      <div class="col--12">
        <div class="text-center"><br>
          <button class="btn btn--small">Si, eliminar!</button>
          <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> No, cancelar!</button>
        </div>
      </div>
    </div>
  </form>
</div>