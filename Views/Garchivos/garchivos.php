<?php 
  headerAdmin($data);
  $arrRegistros = $data['registros'];
?>
<?php
  header_body_Admin($data); //hacer visible los modales
?>

<div class="admin-layer--content">

    <div class="row flex g-10 justify-right mr-bottom-10">
        <button class="btn btn--medium bg-blue" data-fancybox data-src="#modalFormArchivos" onclick="openModal()">
            <div class="bn--icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 10H10V16H6V10H0V6H6V0H10V6H16V10Z" fill="white" />
                </svg></div>Crear nuevo Archivo
        </button>
        <button class="btn btn--medium bg-blue" onclick="fntEditInfo();">
            <div class="btn--icon"><svg width="20" height="19" viewBox="0 0 20 19" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M18 7V6L12 0H2C0.89 0 0 0.89 0 2V16C0 17.11 0.9 18 2 18H8V16.13L16.39 7.74C16.83 7.3 17.39 7.06 18 7ZM11 1.5L16.5 7H11V1.5ZM19.85 11.19L18.87 12.17L16.83 10.13L17.81 9.15C18 8.95 18.33 8.95 18.53 9.15L19.85 10.47C20.05 10.67 20.05 11 19.85 11.19ZM16.13 10.83L18.17 12.87L12.04 19H10V16.96L16.13 10.83Z"
                        fill="white" />
                </svg></div>Actualizar Archivo
        </button>
        <button class="btn btn--medium bg-blue" onclick="openModalDel();">
            <div class="btn--icon"><svg width="16" height="18" viewBox="0 0 16 18" fill="none">
                    <path
                        d="M5 0V1H0V3H1V16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H13C13.5304 18 14.0391 17.7893 14.4142 17.4142C14.7893 17.0391 15 16.5304 15 16V3H16V1H11V0H5ZM5 5H7V14H5V5ZM9 5H11V14H9V5Z"
                        fill="white" />
                </svg></div>Eliminar Archivo
        </button>
    </div>

    <div class="row flex g-10 justify-right mr-bottom-10">
        <a class="btn btn--medium bg-blue" href="<?= base_url() ?>/usuarioarchivo">
            <div class="btn--icon"><svg width="24" height="18" viewBox="0 0 24 18" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M2 0H22C23.05 0 24 0.95 24 2V16C24 17.05 23.05 18 22 18H2C0.95 18 0 17.05 0 16V2C0 0.95 0.95 0 2 0ZM14 3V4H22V3H14ZM14 5V6H21.5H22V5H14ZM14 7V8H21V7H14ZM8 10.91C6 10.91 2 12 2 14V15H14V14C14 12 10 10.91 8 10.91ZM8 3C7.20435 3 6.44129 3.31607 5.87868 3.87868C5.31607 4.44129 5 5.20435 5 6C5 6.79565 5.31607 7.55871 5.87868 8.12132C6.44129 8.68393 7.20435 9 8 9C8.79565 9 9.55871 8.68393 10.1213 8.12132C10.6839 7.55871 11 6.79565 11 6C11 5.20435 10.6839 4.44129 10.1213 3.87868C9.55871 3.31607 8.79565 3 8 3Z"
                        fill="white" />
                </svg></div>Permisos
        </a>
    </div>

    <div class="pannel-table-wwf" role="tabpanel" tabindex="0" aria-labelledby="tab-lastnews-1">
        <div class="table-pro-content">
            <table class="table-pro display" style="width:100%" id="tableArchivo">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>TIPO</th>
                        <th>CODIGO</th>
                        <th>NOMBRE</th>
                        <th>ARCHIVO</th>
                        <th>ACCESO</th>
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
                                    <input type="radio" name="ckArchivos"
                                        data-id="<?=$arrRegistros[$p]['id_archivo']?>"><span>&nbsp;</span>
                                </label>
                            </div>
                        </td>
                        <td><?=$arrRegistros[$p]['id_archivo']?></td>
                        <td><?=$arrRegistros[$p]['tiparc_nombre']?></td>
                        <td><?=$arrRegistros[$p]['archiv_codigo']?></td>
                        <td><?=$arrRegistros[$p]['archiv_nombre']?></td>
                        <td class="text-center">
                            <a href="<?=$arrRegistros[$p]['archiv_archivo']?>" target="_blank">
                                <button class="btn-sm">
                                    <svg width="16" height="20" viewBox="0 0 16 20" fill="none">
                                        <path
                                            d="M10 0L16 6V18C16 18.5304 15.7893 19.0391 15.4142 19.4142C15.0391 19.7893 14.5304 20 14 20H2C1.46957 20 0.960859 19.7893 0.585786 19.4142C0.210714 19.0391 0 18.5304 0 18V2C0 1.46957 0.210714 0.960859 0.585786 0.585786C0.960859 0.210714 1.46957 0 2 0H10ZM14 18V7H9V2H2V18H14ZM8 17L4 13H6.5V10H9.5V13H12L8 17Z"
                                            fill="#464F60" />
                                    </svg>
                                </button>
                            </a>
                        </td>
                        <td><?= $arrRegistros[$p]['archiv_acceso'] == 1 ? "PUBLICO": "PRIVADO"  ?></td>
                    </tr>
                    <?php 
                          } 
                      ?>
                </tbody>
            </table>
            <div class="footer-pagination">
                <div class="total-items"><?= sizeof($data['registros']) ?> de <?= $data['total_registros'] ?></div>
                <div class="pagination-datatable">
                    <a class="button-small-arrow arrow-left"
                        href="<?= ($data['pagina']==1) ? "#" : base_url()."/garchivos/page/".($data['pagina']-1) ?>">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.5 11L6.5 8L9.5 5" stroke="#868FA0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg></a>

                    <div class="total-pages"><?= $data['pagina'] ?> / <?= $data['total_paginas'] ?>
                    </div>
                    <a class="button-small-arrow arrow-right"
                        href="<?= base_url() ?>/garchivos/page/<?= ($data['pagina']< $data['total_paginas']) ? $data['pagina']+1 : $data['total_paginas'] ?>"><svg
                            width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.5 11L9.5 8L6.5 5" stroke="#464F60" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg></a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<?php getModal('modalArchivos',$data);?>
</div>
<?php
footerAdmin($data); 
?>
<script>
objRef = document.body;
objRef.classList.remove('home');
objRef.classList.add('admin');
objRef.setAttribute('data-x', 'tab');
$('#aArchivos').addClass('active');
</script>