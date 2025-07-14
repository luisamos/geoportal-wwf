  <div class="lbox" id="modalFormServicioGeografico">
      <div class="lbox--title" id="titleModal">Formulario Servicio Geográfico</div>
      <form id="formServicioGeografico" name="formServicioGeografico">

          <input type="hidden" name="id_servicio_geografico" id="id_servicio_geografico">

          <div class="grid">
              <div class="col--12">
                  <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.
                  </p>
              </div>
              <div class="col--4">Tipo:</div>
              <div class="col--8">
                  <select name="tipo" id="tipo">
                      <option value="1" selected>OGC: WMS</option>
                      <option value="2">ArcGIS Server</option>
                  </select>
              </div>
              <div class="col--4">Temática*:</div>
              <div class="col--8">
                  <select name="id_tematica" id="id_tematica" required>
                  </select>
              </div>
              <div class="col--4">Alias*:</div>
              <div class="col--8">
                  <input type="text" name="alias" id="alias" required>
              </div>
              <div class="col--4">Dirección web*:</div>
              <div class="col--8">
                  <input type="text" name="direccion_web" id="direccion_web" required>&nbsp;
                  <button class="btn btn--small bg-aqua" id="conectarServicioWeb">Conectar</button>
              </div>
              <div class="col--4">Capa*:</div>
              <div class="col--8">
                  <select name="capas_tematicas" id="capas_tematicas">
                      <option value="-1" selected>[Elegir]</option>
                  </select>
              </div>

              <div class="col--4">Visible:</div>
              <div class="col--8">
                  <select name="visible" id="visible">
                      <option value="1" selected>Si</option>
                      <option value="0">No</option>
                  </select>
              </div>

              <div class="col--12">
                  <div class="text-center"><br>
                      <button class="btn btn--small" id="btnGuardar">Guardar</button>
                      <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> Cancelar</button>
                  </div>
              </div>
          </div>
      </form>
  </div>


  <div class="lbox" id="modalFormServicioGeograficoDel">
      <div class="lbox--title" id="titleModal">Eliminar</div>
      <form name="formServicioGeograficoDel" id="formServicioGeograficoDel">

          <input type="hidden" name="id_servicio_geografico_del" id="id_servicio_geografico_del">

          <div class="grid">
              <div class="col--12">¿Realmente quiere eliminar el Servicio Geografico?</div>


              <div class="col--12">
                  <div class="text-center"><br>
                      <button class="btn btn--small">Si, eliminar!</button>
                      <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> No,
                          cancelar!</button>
                  </div>
              </div>
          </div>
      </form>
  </div>