<?php
  headerAdmin($data);
  $arrRegistros = $data['registros'];
?>
<script src="<?= media() ?>/public/js/ckeditor.js"></script>
<?php
  header_body_Admin($data);
?>

<div class="admin-layer--content">

    <div class="row flex g-10 justify-right mr-bottom-10">
        <button class="btn btn--medium bg-blue" data-fancybox="nodrag" data-src="#modalFormNoticia"
            onclick="openModal()">
            <div class="btn--icon"><svg width="21" height="20" viewBox="0 0 21 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M15.7871 11.323H17.7871V14.323H20.7871V16.323H17.7871V19.323H15.7871V16.323H12.7871V14.323H15.7871V11.323ZM18.7871 8.323V5.323H2.78711V8.323H18.7871ZM11.7871 10.323V12.003C11.1571 12.953 10.7871 14.093 10.7871 15.323C10.7871 16.413 11.0771 17.443 11.5871 18.323H2.78711C2.25668 18.323 1.74797 18.1123 1.3729 17.7372C0.997823 17.3621 0.787109 16.8534 0.787109 16.323V0.322998L2.45711 1.993L4.11711 0.322998L5.78711 1.993L7.45711 0.322998L9.11711 1.993L10.7871 0.322998L12.4571 1.993L14.1171 0.322998L15.7871 1.993L17.4571 0.322998L19.1171 1.993L20.7871 0.322998V10.823C19.7171 9.903 18.3171 9.323 16.7871 9.323C15.5571 9.323 14.4171 9.693 13.4671 10.323H11.7871ZM9.78711 16.323V10.323H2.78711V16.323H9.78711Z"
                        fill="white" />
                </svg></div>Nueva noticia
        </button>
        <button class="btn btn--medium bg-blue" onclick="fntEditInfo();">
            <div class="btn--icon"><svg width="19" height="19" viewBox="0 0 19 19" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M1.63 8.27L0 7L9 0L16.94 6.17L9.5 13.61L9 14L1.63 8.27ZM7 16.94V16.11L7.59 15.53L7.63 15.5L1.62 10.81L0 12.07L7 17.5V16.94ZM18.7 10.58L17.42 9.3C17.21 9.09 16.86 9.09 16.65 9.3L15.65 10.3L17.7 12.35L18.7 11.35C18.91 11.14 18.91 10.79 18.7 10.58ZM9 19H11.06L17.11 12.93L15.06 10.88L9 16.94V19Z"
                        fill="white" />
                </svg></div>Editar noticia
        </button>
        <button class="btn btn--medium bg-blue" onclick="openModalDel();">
            <div class="btn--icon"><svg width="16" height="18" viewBox="0 0 16 18" fill="none">
                    <path
                        d="M5 0V1H0V3H1V16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H13C13.5304 18 14.0391 17.7893 14.4142 17.4142C14.7893 17.0391 15 16.5304 15 16V3H16V1H11V0H5ZM5 5H7V14H5V5ZM9 5H11V14H9V5Z"
                        fill="white" />
                </svg></div>Eliminar noticia
        </button>
    </div>

    <div class="pannel-table-wwf" role="tabpanel" tabindex="0" aria-labelledby="tab-lastnews-1">
        <div class="table-pro-content">
            <table class="table-pro display" style="width:100%" id="tableNoticias">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>TIPO</th>
                        <th>URL</th>
                        <th>TITULO</th>
                        <th>DESCRIPCION</th>
                        <th>IMAGEN 1</th>
                        <th>IMAGEN 2</th>
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
                                    <input type="radio" name="noticias"
                                        data-id="<?=$arrRegistros[$p]['id_noticia']?>"><span>&nbsp;</span>
                                </label>
                            </div>
                        </td>
                        <td><?=$arrRegistros[$p]['id_noticia']?></td>
                        <td><?= $arrRegistros[$p]['notici_tipo'] == 1 ? 'LOCAL':'EXTERNA' ?></td>
                        <td><?=$arrRegistros[$p]['notici_url']?></td>
                        <td><?=$arrRegistros[$p]['notici_titulo']?></td>
                        <td><?= substr($arrRegistros[$p]['notici_descripcion'], 0, 200) ."..." ?></td>
                        <td class="text-center">
                            <a href="<?=$arrRegistros[$p]['notici_imagen1']?>" target="_blank">
                                <button class="btn-sm">
                                    <svg width="16" height="20" viewBox="0 0 16 20" fill="none">
                                        <path
                                            d="M10 0L16 6V18C16 18.5304 15.7893 19.0391 15.4142 19.4142C15.0391 19.7893 14.5304 20 14 20H2C1.46957 20 0.960859 19.7893 0.585786 19.4142C0.210714 19.0391 0 18.5304 0 18V2C0 1.46957 0.210714 0.960859 0.585786 0.585786C0.960859 0.210714 1.46957 0 2 0H10ZM14 18V7H9V2H2V18H14ZM8 17L4 13H6.5V10H9.5V13H12L8 17Z"
                                            fill="#464F60" />
                                    </svg>
                                </button>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="<?=$arrRegistros[$p]['notici_imagen2']?>" target="_blank">
                                <button class="btn-sm">
                                    <svg width="16" height="20" viewBox="0 0 16 20" fill="none">
                                        <path
                                            d="M10 0L16 6V18C16 18.5304 15.7893 19.0391 15.4142 19.4142C15.0391 19.7893 14.5304 20 14 20H2C1.46957 20 0.960859 19.7893 0.585786 19.4142C0.210714 19.0391 0 18.5304 0 18V2C0 1.46957 0.210714 0.960859 0.585786 0.585786C0.960859 0.210714 1.46957 0 2 0H10ZM14 18V7H9V2H2V18H14ZM8 17L4 13H6.5V10H9.5V13H12L8 17Z"
                                            fill="#464F60" />
                                    </svg>
                                </button>
                            </a>
                        </td>
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
                        href="<?= ($data['pagina']==1) ? "#" : base_url()."/gnoticias/page/".($data['pagina']-1) ?>">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.5 11L6.5 8L9.5 5" stroke="#868FA0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg></a>

                    <div class="total-pages"><?= $data['pagina'] ?> / <?= $data['total_paginas'] ?>
                    </div>
                    <a class="button-small-arrow arrow-right"
                        href="<?= base_url() ?>/gnoticias/page/<?= ($data['pagina']< $data['total_paginas']) ? $data['pagina']+1 : $data['total_paginas'] ?>"><svg
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
<?php getModal('modalNoticias',$data);?>
</div>
<?php
footerAdmin($data);
?>
<script>
objRef = document.body;
objRef.classList.remove('home');
objRef.classList.add('admin');
objRef.setAttribute('data-x', 'tab');
$('#aNoticias').addClass('active');
</script>