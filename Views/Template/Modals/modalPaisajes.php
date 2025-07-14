  <div class="lbox" id="modalFormPaisajes">
    <div class="lbox--title"  id="titleModal">Formulario Paisajes</div>
    <form action="post" id="formPaisajes" name="formPaisajes"> 

      <input type="hidden" name="id_paisaje" id="id_paisaje">

      <input type="hidden" name="id_ref" id="id_ref">

      <div class="grid">

        <div class="col--4">Paisaje:</div>
        <div class="col--8">
          <input type="text" name="paisaje" id="paisaje" maxlength="200" required>
        </div>

        <div class="col--4">Estrategia:</div>
        <div class="col--8">
          <textarea class="form-control" id="estrategia" name="estrategia" onblur="javascript:this.value=this.value.toUpperCase();" maxlength="300" required> </textarea>
        </div>
        <div class="col--4">Objetivo:</div>
        <div class="col--8">
          <textarea class="form-control" id="objetivo" name="objetivo" onblur="javascript:this.value=this.value.toUpperCase();" maxlength="400" required> </textarea>
        </div>

        <div class="col--4">Meta 1:</div>
        <div class="col--8">
          <textarea class="form-control" id="meta1" name="meta1" onblur="javascript:this.value=this.value.toUpperCase();" maxlength="500" required> </textarea>
        </div>
        <div class="col--4">Meta 2:</div>
        <div class="col--8">
          <textarea class="form-control" id="meta2" name="meta2" onblur="javascript:this.value=this.value.toUpperCase();" maxlength="500" required> </textarea>
        </div>

        <div class="col--4">Meta 3:</div>
        <div class="col--8">
          <input type="text" name="meta3" id="meta3">
          
        </div>
        <div class="col--4">Meta 4:</div>
        <div class="col--8">
          <input type="text" name="meta4" id="meta4">
          
        </div>
        <div class="col--4">Meta 5:</div>
        <div class="col--8">
          <input type="text" name="meta5" id="meta5">
          
        </div>

        <div class="col--4">Indicador (%):</div>
        <div class="col--8">
          <input type="text" name="indicador" id="indicador"  required>
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


<div class="lbox" id="modalFormPaisajesDel">
  <div class="lbox--title" id="titleModal">Eliminar Paisaje</div>
  <form name="formPaisajesDel" id="formPaisajesDel">

    <input type="hidden" name="id_paisaje_del" id="id_paisaje_del">

    <div class="grid">
      <div class="col--12">¿Realmente quiere eliminar el Paisaje?</div>
      
     
      <div class="col--12">
        <div class="text-center"><br>
          <button class="btn btn--small">Si, eliminar!</button>
          <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> No, cancelar!</button>
        </div>
      </div>
    </div>
  </form>
</div>