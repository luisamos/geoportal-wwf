<?php
  headerAdmin($data);
  $arrRegistros = $data['registros'];
?>
<?php header_body_Admin($data);?>

      <div id="contentAjax"></div>
      <div class="admin-layer--content">

        <div class="row flex g-10 justify-right mr-bottom-10">
          <button class="btn btn--medium bg-blue" data-fancybox data-src="#modalFormPaisajes" onclick="openModal()">
            <div class="btn--icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 12H17V15H20V17H17V20H15V17H12V15H15V12ZM9 14L0 7L9 0L18 7L9 14ZM9 16.54L10 15.75V16C10 16.71 10.12 17.39 10.35 18L9 19.07L0 12.07L1.62 10.81L9 16.54Z" fill="white"/></svg></div>Crear Paisaje
          </button>
          <button class="btn btn--medium bg-blue" onclick="fntEditInfo();">
            <div class="btn--icon"><svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.63 8.27L0 7L9 0L16.94 6.17L9.5 13.61L9 14L1.63 8.27ZM7 16.94V16.11L7.59 15.53L7.63 15.5L1.62 10.81L0 12.07L7 17.5V16.94ZM18.7 10.58L17.42 9.3C17.21 9.09 16.86 9.09 16.65 9.3L15.65 10.3L17.7 12.35L18.7 11.35C18.91 11.14 18.91 10.79 18.7 10.58ZM9 19H11.06L17.11 12.93L15.06 10.88L9 16.94V19Z" fill="white"/></svg></div>Actualizar Paisaje
          </button>
          <button class="btn btn--medium bg-blue" onclick="openModalDel();">
            <div class="btn--icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.46 13.88L13.88 12.46L16 14.59L18.12 12.46L19.54 13.88L17.41 16L19.54 18.12L18.12 19.54L16 17.41L13.88 19.54L12.46 18.12L14.59 16L12.46 13.88ZM9 14L0 7L9 0L18 7L9 14ZM9 16.54L10 15.75V16C10 16.71 10.12 17.39 10.35 18L9 19.07L0 12.07L1.62 10.81L9 16.54Z" fill="white"/></svg></div>Eliminar Paisaje
          </button>

          <button class="btn btn--medium bg-blue" onclick="fntAddEstrategia()">
            <div class="bn--icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 10H10V16H6V10H0V6H6V0H10V6H16V10Z" fill="white"/></svg></div>Agregar Estrategia
          </button>

        </div>

        <div class="pannel-table-wwf" role="tabpanel" tabindex="0" aria-labelledby="tab-lastnews-1">
          <div class="table-pro-content"> 
            <table class="table-pro display" style="width:100%" id="tableServiciosGeo">
              <thead>
                <tr>
                  <th></th>
                  <th>ID</th>
                  <th>PAISAJE</th>
                  <th>ESTRATEGIA Y OBJETIVO</th>
                  <th>METAS</th>
                  <th>INDICADOR</th>
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
                                <input type="radio" name="ckPaisajes" 
                                data-id="<?=$arrRegistros[$p]['id_paisaje']?>" 
                                data-paisaje="<?=$arrRegistros[$p]['paisaj_paisaje']?>"
                                data-idref="<?=$arrRegistros[$p]['paisaj_id_ref']?>">
                                <span>&nbsp;</span>
                              </label>
                            </div>
                          </td>
                      <td><?=$arrRegistros[$p]['id_paisaje']?></td>
                      <td><b><?=$arrRegistros[$p]['paisaj_paisaje']?></b></td>
                      <td>
                        <b>ESTRATEGIA:</b><br>
                          <?=$arrRegistros[$p]['paisaj_estrategia']?><br><br>
                        <b>OBJETIVO:</b><br>
                          <?=$arrRegistros[$p]['paisaj_objetivo']?>
                      </td>
                      <td>
                        <?= empty($arrRegistros[$p]['paisaj_meta1']) ? "": "<b>META1:</b><br>".$arrRegistros[$p]['paisaj_meta1'] ?><br>
                        <?= empty($arrRegistros[$p]['paisaj_meta2']) ? "": "<b>META2:</b><br>".$arrRegistros[$p]['paisaj_meta2'] ?><br>
                        <?= empty($arrRegistros[$p]['paisaj_meta3']) ? "": "<b>META3:</b><br>".$arrRegistros[$p]['paisaj_meta3'] ?><br>
                        <?= empty($arrRegistros[$p]['paisaj_meta4']) ? "": "<b>META4:</b><br>".$arrRegistros[$p]['paisaj_meta4'] ?><br>
                        <?= empty($arrRegistros[$p]['paisaj_meta5']) ? "": "<b>META5:</b><br>".$arrRegistros[$p]['paisaj_meta5'] ?>
                      </td>
                      <td align='center'><span class='badge badge-info' style="font-size: 100% !important;"><?=$arrRegistros[$p]['paisaj_indicador']?>%</span></td>
                    </tr>
                  <?php 
                      } 
                  ?>
              </tbody>
            </table>
            <div class="footer-pagination">
                <div class="total-items"><?= sizeof($data['registros']) ?> de <?= $data['total_registros'] ?></div>
                <div class="pagination-datatable">
                  <a class="button-small-arrow arrow-left" href="<?= ($data['pagina']==1) ? "#" : base_url()."/gpaisajes/page/".($data['pagina']-1) ?>"> <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.5 11L6.5 8L9.5 5" stroke="#868FA0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>

                  <div class="total-pages"><?= $data['pagina'] ?> / <?= $data['total_paginas'] ?>
                  </div>
                  <a class="button-small-arrow arrow-right" href="<?= base_url() ?>/gpaisajes/page/<?= ($data['pagina']< $data['total_paginas']) ? $data['pagina']+1 : $data['total_paginas'] ?>"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.5 11L9.5 8L6.5 5" stroke="#464F60" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php getModal('modalPaisajes',$data);?>
</div>

<?php footerAdmin($data);?>

<script>
  objRef = document.body;
  objRef.classList.remove('home');
  objRef.classList.add('admin');
  objRef.setAttribute('data-x','tab');

  $('#aPaisajes').addClass('active');
</script>
    