<?php 
  headerAdmin($data);
  $arrRegistros = $data['registros'];
?>
<?php 
  header_body_Admin($data); //hacer visible los modales
?>

      <div class="admin-layer--content">

        <div class="row flex g-10 justify-right mr-bottom-10">
          <button class="btn btn--medium bg-blue" data-fancybox data-src="#modalFormUsuarioCapa" onclick="openModal()">
            <div class="bn--icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 10H10V16H6V10H0V6H6V0H10V6H16V10Z" fill="white"/></svg></div>Agregar Permiso
          </button>

          <button class="btn btn--medium bg-blue" onclick="openModalDel();">
            <div class="btn--icon"><svg width="16" height="18" viewBox="0 0 16 18" fill="none"><path d="M5 0V1H0V3H1V16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H13C13.5304 18 14.0391 17.7893 14.4142 17.4142C14.7893 17.0391 15 16.5304 15 16V3H16V1H11V0H5ZM5 5H7V14H5V5ZM9 5H11V14H9V5Z" fill="white"/></svg></div>Eliminar Permiso
          </button>
        </div>

        <div class="row flex g-10 justify-right mr-bottom-10">
          <a class="btn btn--medium bg-blue" href="<?= base_url() ?>/capas">
            <div class="btn--icon"><svg width="24" height="18" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 7V6L12 0H2C0.89 0 0 0.89 0 2V16C0 17.11 0.9 18 2 18H8V16.13L16.39 7.74C16.83 7.3 17.39 7.06 18 7ZM11 1.5L16.5 7H11V1.5ZM19.85 11.19L18.87 12.17L16.83 10.13L17.81 9.15C18 8.95 18.33 8.95 18.53 9.15L19.85 10.47C20.05 10.67 20.05 11 19.85 11.19ZM16.13 10.83L18.17 12.87L12.04 19H10V16.96L16.13 10.83Z" fill="white"/></svg></div> <- Regresar Capa 
          </a>

        </div>

        <div class="pannel-table-wwf" role="tabpanel" tabindex="0" aria-labelledby="tab-lastnews-1">
          <div class="table-pro-content"> 
            <table class="table-pro display" style="width:100%" id="tableUsuarioCapa">
              <thead>
                <tr>
                  <th></th>
                  <th>ID</th>
                  <th>USUARIO</th>
                  <th>CAPA</th>
                </tr>
              </thead>
              <tbody>
                  <?php 
                        for ($p=0; $p < count($arrRegistros); $p++) { 
                      ?>
                        <tr>
                          <td>
                            <div class="selected-article"> 
                              <label>
                                <input type="radio" name="ckUsuarioCapa" data-id="<?=$arrRegistros[$p]['id_usuario_capa']?>"><span>&nbsp;</span>
                              </label>
                            </div>
                          </td>
                          <td><?=$arrRegistros[$p]['id_usuario_capa']?></td>
                          <td> <b><?=$arrRegistros[$p]['usuari_nombre']?></b> <br>
                              <?=$arrRegistros[$p]['person_nombres']." ".$arrRegistros[$p]['person_apellidos'] ?> </td>
                          <td><?=$arrRegistros[$p]['capa_nombre']?></td>
                        </tr>
                      <?php 
                          } 
                      ?>
              </tbody>
            </table>
            <div class="footer-pagination">
                <div class="total-items"><?= sizeof($data['registros']) ?> de <?= $data['total_registros'] ?></div>
                <div class="pagination-datatable">
                  <a class="button-small-arrow arrow-left" href="<?= ($data['pagina']==1) ? "#" : base_url()."/capas/page/".($data['pagina']-1) ?>"> <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.5 11L6.5 8L9.5 5" stroke="#868FA0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>

                  <div class="total-pages"><?= $data['pagina'] ?> / <?= $data['total_paginas'] ?>
                  </div>
                  <a class="button-small-arrow arrow-right" href="<?= base_url() ?>/capas/page/<?= ($data['pagina']< $data['total_paginas']) ? $data['pagina']+1 : $data['total_paginas'] ?>"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.5 11L9.5 8L6.5 5" stroke="#464F60" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php getModal('modalUsuarioCapa',$data);?>
</div>

<?php footerAdmin($data);?>

<script>
  objRef = document.body;
  objRef.classList.remove('home');
  objRef.classList.add('admin');
  objRef.setAttribute('data-x','tab');
  $('#aCapasGeograficas').addClass('active');
</script>
    