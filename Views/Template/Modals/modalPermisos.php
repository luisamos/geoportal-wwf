<div class="modal fade modalPermisos" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
  <div class="lbox" id="modalPermisos">
    <div class="lbox--title"  id="titleModal">Formulario Persmisos</div>

    <form action="" id="formPermisos" name="formPermisos">

      <input type="hidden" id="id_rol" name="id_rol" value="<?= $data['id_rol']; ?>" required="">

      <div class="grid">
        <div class="col--12">
          <div class="table-responsive">

            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Módulo</th>
                  <th>Acceso</th>
                </tr>
              </thead>
              <tbody>
                  <?php 
                      $no=1;
                      $modulos = $data['modulos'];
                      for ($i=0; $i < count($modulos); $i++) { 

                          $permisos = $modulos[$i]['permisos'];
                          $rCheck = $permisos['permis_estado'] == 1 ? " checked " : "";
                         

                          $idmod = $modulos[$i]['id_modulo'];
                  ?>
                <tr>
                  <td>
                      <?= $no; ?>
                      <input type="hidden" name="modulos[<?= $i; ?>][id_modulo]" value="<?= $idmod ?>" required >
                  </td>
                  <td>
                      <?= $modulos[$i]['modulo_titulo']; ?>
                  </td>
                  <td><div class="toggle-flip">
                        <label>
                          <input type="checkbox" name="modulos[<?= $i; ?>][permis_estado]" <?= $rCheck ?> ><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                        </label>
                      </div>
                  </td>
                 
                </tr>
                <?php 
                      $no++;
                  }
                  ?>
              </tbody>
            </table>

          </div>
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