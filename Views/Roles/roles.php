<?php 
  headerAdmin($data);
  $arrRegistros = $data['registros'];
?>
<?php header_body_Admin($data);?>

      <div id="contentAjax"></div>
      <div class="admin-layer--content">

        <div class="row flex g-10 justify-right mr-bottom-10">
          <button class="btn btn--medium bg-blue" data-fancybox data-src="#modalFormRoles" onclick="openModal()">
            <div class="btn--icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 12H17V15H20V17H17V20H15V17H12V15H15V12ZM9 14L0 7L9 0L18 7L9 14ZM9 16.54L10 15.75V16C10 16.71 10.12 17.39 10.35 18L9 19.07L0 12.07L1.62 10.81L9 16.54Z" fill="white"/></svg></div>Crear Rol
          </button>
          <button class="btn btn--medium bg-blue" onclick="fntEditInfo();">
            <div class="btn--icon"><svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.63 8.27L0 7L9 0L16.94 6.17L9.5 13.61L9 14L1.63 8.27ZM7 16.94V16.11L7.59 15.53L7.63 15.5L1.62 10.81L0 12.07L7 17.5V16.94ZM18.7 10.58L17.42 9.3C17.21 9.09 16.86 9.09 16.65 9.3L15.65 10.3L17.7 12.35L18.7 11.35C18.91 11.14 18.91 10.79 18.7 10.58ZM9 19H11.06L17.11 12.93L15.06 10.88L9 16.94V19Z" fill="white"/></svg></div>Actualizar Rol
          </button>
          <button class="btn btn--medium bg-blue" onclick="openModalDel();">
            <div class="btn--icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.46 13.88L13.88 12.46L16 14.59L18.12 12.46L19.54 13.88L17.41 16L19.54 18.12L18.12 19.54L16 17.41L13.88 19.54L12.46 18.12L14.59 16L12.46 13.88ZM9 14L0 7L9 0L18 7L9 14ZM9 16.54L10 15.75V16C10 16.71 10.12 17.39 10.35 18L9 19.07L0 12.07L1.62 10.81L9 16.54Z" fill="white"/></svg></div>Eliminar Rol
          </button>
          <button class="btn btn--medium bg-blue" onclick="fntPermisos();">
            <div class="btn--icon"><svg width="24" height="18" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 0H22C23.05 0 24 0.95 24 2V16C24 17.05 23.05 18 22 18H2C0.95 18 0 17.05 0 16V2C0 0.95 0.95 0 2 0ZM14 3V4H22V3H14ZM14 5V6H21.5H22V5H14ZM14 7V8H21V7H14ZM8 10.91C6 10.91 2 12 2 14V15H14V14C14 12 10 10.91 8 10.91ZM8 3C7.20435 3 6.44129 3.31607 5.87868 3.87868C5.31607 4.44129 5 5.20435 5 6C5 6.79565 5.31607 7.55871 5.87868 8.12132C6.44129 8.68393 7.20435 9 8 9C8.79565 9 9.55871 8.68393 10.1213 8.12132C10.6839 7.55871 11 6.79565 11 6C11 5.20435 10.6839 4.44129 10.1213 3.87868C9.55871 3.31607 8.79565 3 8 3Z" fill="white"/></svg></div>Permisos 
          </button>
        </div>

        <div class="pannel-table-wwf" role="tabpanel" tabindex="0" aria-labelledby="tab-lastnews-1">
          <div class="table-pro-content">
            <table class="table-pro display" style="width:100%" id="tableServiciosGeo">
              <thead>
                <tr>
                  <th></th>
                  <th>ID</th>
                  <th>NOMBRE</th>
                  <th>DESCRIPCION</th>
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
                                <input type="radio" name="ckRoles" data-id="<?=$arrRegistros[$p]['id_rol']?>"><span>&nbsp;</span>
                              </label>
                            </div>
                          </td>
                      <td><?=$arrRegistros[$p]['id_rol']?></td>
                      <td><?=$arrRegistros[$p]['rol_nombre']?></td>
                      <td><?=$arrRegistros[$p]['rol_descripcion']?></td>
                    </tr>
                  <?php
                      }
                  ?>
              </tbody>
            </table>
            <div class="footer-pagination">
                <div class="total-items"><?= sizeof($data['registros']) ?> de <?= $data['total_registros'] ?></div>
                <div class="pagination-datatable">
                  <a class="button-small-arrow arrow-left" href="<?= ($data['pagina']==1) ? "#" : base_url()."/roles/page/".($data['pagina']-1) ?>"> <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.5 11L6.5 8L9.5 5" stroke="#868FA0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>

                  <div class="total-pages"><?= $data['pagina'] ?> / <?= $data['total_paginas'] ?>
                  </div>
                  <a class="button-small-arrow arrow-right" href="<?= base_url() ?>/roles/page/<?= ($data['pagina']< $data['total_paginas']) ? $data['pagina']+1 : $data['total_paginas'] ?>"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.5 11L9.5 8L6.5 5" stroke="#464F60" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php getModal('modalRoles',$data);?>
</div>

<?php footerAdmin($data); ?>

<script>
  objRef = document.body;
  objRef.classList.remove('home');
  objRef.classList.add('admin');
  objRef.setAttribute('data-x','tab');

  $('#aRoles').addClass('active');
</script>