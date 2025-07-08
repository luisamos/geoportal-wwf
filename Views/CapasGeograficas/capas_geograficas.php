<?php 
  headerAdmin($data);
  $arrRegistros = $data['registros'];
?>
<?php header_body_Admin($data);?>

<div class="admin-layer--content">

    <div class="row flex g-10 justify-right mr-bottom-10">
        <button class="btn btn--medium bg-blue" data-fancybox data-src="#modalFormCapaGeografica"
            onclick="openModal();">
            <div class="btn--icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M15 12H17V15H20V17H17V20H15V17H12V15H15V12ZM9 14L0 7L9 0L18 7L9 14ZM9 16.54L10 15.75V16C10 16.71 10.12 17.39 10.35 18L9 19.07L0 12.07L1.62 10.81L9 16.54Z"
                        fill="white" />
                </svg></div>Crear capa
        </button>
        <button class="btn btn--medium bg-blue" onclick="fntEditInfo();">
            <div class="btn--icon"><svg width="19" height="19" viewBox="0 0 19 19" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M1.63 8.27L0 7L9 0L16.94 6.17L9.5 13.61L9 14L1.63 8.27ZM7 16.94V16.11L7.59 15.53L7.63 15.5L1.62 10.81L0 12.07L7 17.5V16.94ZM18.7 10.58L17.42 9.3C17.21 9.09 16.86 9.09 16.65 9.3L15.65 10.3L17.7 12.35L18.7 11.35C18.91 11.14 18.91 10.79 18.7 10.58ZM9 19H11.06L17.11 12.93L15.06 10.88L9 16.94V19Z"
                        fill="white" />
                </svg></div>Actualizar capa
        </button>
        <button class="btn btn--medium bg-blue" onclick="openModalDel();">
            <div class="btn--icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12.46 13.88L13.88 12.46L16 14.59L18.12 12.46L19.54 13.88L17.41 16L19.54 18.12L18.12 19.54L16 17.41L13.88 19.54L12.46 18.12L14.59 16L12.46 13.88ZM9 14L0 7L9 0L18 7L9 14ZM9 16.54L10 15.75V16C10 16.71 10.12 17.39 10.35 18L9 19.07L0 12.07L1.62 10.81L9 16.54Z"
                        fill="white" />
                </svg></div>Eliminar capa
        </button>
    </div>
    <div class="row flex g-10 justify-right mr-bottom-10">
        <button class="btn btn--medium" onclick="openModalSLD();">
            <div class="btn--icon"><svg width="20" height="18" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path path
                        d="M9.288,13.067c-2.317.446-3.465,3.026-3.963,4.634A1,1,0,0,0,6.281,19H10a3,3,0,0,0,2.988-3.274A3.107,3.107,0,0,0,9.288,13.067Z"
                        fill="white" />
                    <path
                        d="M23,8.979a1,1,0,0,0-1,1V15H18a3,3,0,0,0-3,3v4H5a3,3,0,0,1-3-3V5A3,3,0,0,1,5,2H16.042a1,1,0,0,0,0-2H5A5.006,5.006,0,0,0,0,5V19a5.006,5.006,0,0,0,5,5H16.343a4.966,4.966,0,0,0,3.535-1.464l2.658-2.658A4.966,4.966,0,0,0,24,16.343V9.979A1,1,0,0,0,23,8.979ZM18.464,21.122a3.022,3.022,0,0,1-1.464.8V18a1,1,0,0,1,1-1h3.925a3.022,3.022,0,0,1-.8,1.464Z"
                        fill="white" />
                    <path
                        d="M14.566,14.17a1,1,0,0,1-.707-1.707L21.712,4.61a.943.943,0,0,0,0-1.335A.9.9,0,0,0,21.018,3a.933.933,0,0,0-.678.314l-7.6,8.407a1,1,0,0,1-1.484-1.341l7.6-8.4A2.949,2.949,0,0,1,20.963,1a2.985,2.985,0,0,1,2.163.862,2.947,2.947,0,0,1,0,4.163l-7.853,7.853A.993.993,0,0,1,14.566,14.17Z"
                        fill="white" />
                </svg></div>Estilo SLD
        </button>
    </div>

    <div class="pannel-table-wwf" role="tabpanel" tabindex="0" aria-labelledby="tab-lastnews-1">
        <div class="table-pro-content">
            <table class="table-pro display" style="width:100%" id="tableCapas">
                <thead>
                    <tr>
                        <th></th>
                        <th>Código</th>
                        <th>Temática</th>
                        <th>Nombre</th>
                        <th>Alias</th>
                        <th>Tipo</th>
                        <th>Visible</th>
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
                                    <input type="radio" name="capas"
                                        data-id="<?=$arrRegistros[$p]['id_capa_geografica']?>"
                                        data-descripcion_tematica="<?=$arrRegistros[$p]['descripcion_tematica']?>"
                                        data-nombre="<?=$arrRegistros[$p]['nombre']?>"
                                        data-alias="<?=$arrRegistros[$p]['alias']?>"
                                        data-tipo="<?=$arrRegistros[$p]['tipo']?>"
                                        data-visible="<?=$arrRegistros[$p]['visible']?>">
                                    <span>&nbsp;</span>
                                </label>
                            </div>
                        </td>
                        <td><?=$arrRegistros[$p]['id_capa_geografica']?></td>
                        <td><?=$arrRegistros[$p]['descripcion_tematica']?></td>
                        <td><?=$arrRegistros[$p]['nombre']?></td>
                        <td><?=$arrRegistros[$p]['alias']?></td>
                        <td><?=$arrRegistros[$p]['tipo']?></td>
                        <td><?=$arrRegistros[$p]['visible']?></td>
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
                        href="<?= ($data['pagina']==1) ? "#" : base_url()."CapasGeograficas/page/".($data['pagina']-1) ?>">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.5 11L6.5 8L9.5 5" stroke="#868FA0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg></a>

                    <div class="total-pages"><?= $data['pagina'] ?> / <?= $data['total_paginas'] ?>
                    </div>
                    <a class="button-small-arrow arrow-right"
                        href="<?= base_url() ?>CapasGeograficas/page/<?= ($data['pagina']< $data['total_paginas']) ? $data['pagina']+1 : $data['total_paginas'] ?>"><svg
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
<?php getModal('modalCapasGeograficas',$data);?>
</div>

<?php footerAdmin($data);?>

<script>
objRef = document.body;
objRef.classList.remove('home');
objRef.classList.add('admin');
objRef.setAttribute('data-x', 'tab');

$('#aCapasGeograficas').addClass('active');
</script>