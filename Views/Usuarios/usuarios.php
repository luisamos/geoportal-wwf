<?php 
  headerAdmin($data);
  $arrRegistros = $data['registros'];
?>
<?php 
  header_body_Admin($data);
?>
<div class="admin-layer--content">

    <div class="row flex g-10 justify-right mr-bottom-10">
        <button class="btn btn--medium bg-blue" data-fancybox data-src="#modalFormUsuarios" onclick="openModal()">
            <div class="btn--icon"><svg width="22" height="16" viewBox="0 0 22 16" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M14 10C11.33 10 6 11.33 6 14V16H22V14C22 11.33 16.67 10 14 10ZM5 6V3H3V6H0V8H3V11H5V8H8V6M14 8C15.0609 8 16.0783 7.57857 16.8284 6.82843C17.5786 6.07828 18 5.06087 18 4C18 2.93913 17.5786 1.92172 16.8284 1.17157C16.0783 0.421427 15.0609 0 14 0C12.9391 0 11.9217 0.421427 11.1716 1.17157C10.4214 1.92172 10 2.93913 10 4C10 5.06087 10.4214 6.07828 11.1716 6.82843C11.9217 7.57857 12.9391 8 14 8Z"
                        fill="white" />
                </svg></div>Crear Usuario
        </button>
        <button class="btn btn--medium bg-blue" onclick="fntEditInfo();">
            <div class="btn--icon"><svg width="18" height="17" viewBox="0 0 18 17" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M17.7 9.35L16.7 10.35L14.65 8.3L15.65 7.3C15.86 7.09 16.21 7.09 16.42 7.3L17.7 8.58C17.91 8.79 17.91 9.14 17.7 9.35ZM8 14.94L14.06 8.88L16.11 10.93L10.06 17H8V14.94ZM8 10C3.58 10 0 11.79 0 14V16H6V14.11L10 10.11C9.34 10.03 8.67 10 8 10ZM8 0C6.93913 0 5.92172 0.421427 5.17157 1.17157C4.42143 1.92172 4 2.93913 4 4C4 5.06087 4.42143 6.07828 5.17157 6.82843C5.92172 7.57857 6.93913 8 8 8C9.06087 8 10.0783 7.57857 10.8284 6.82843C11.5786 6.07828 12 5.06087 12 4C12 2.93913 11.5786 1.92172 10.8284 1.17157C10.0783 0.421427 9.06087 0 8 0Z"
                        fill="white" />
                </svg></div>Actualizar usuario
        </button>
        <button class="btn btn--medium bg-blue" onclick="openModalDel();">
            <div class="btn--icon"><svg width="18" height="19" viewBox="0 0 18 19" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9.99979 0C11.0607 0 12.0781 0.421427 12.8282 1.17157C13.5784 1.92172 13.9998 2.93913 13.9998 4C13.9998 5.95 12.5998 7.58 10.7498 7.93L6.06979 3.25C6.41979 1.4 8.04979 0 9.99979 0ZM10.2798 10L16.2798 16L17.9998 17.72L16.7298 19L13.7298 16H1.99979V14C1.99979 12.16 4.49979 10.61 7.86979 10.14L0.779785 3.05L2.04979 1.78L10.2798 10ZM17.9998 14V15.18L13.1398 10.32C15.9998 10.93 17.9998 12.35 17.9998 14Z"
                        fill="white" />
                </svg></div>Eliminar usuario
        </button>
    </div>
    <div class="row flex g-10 justify-right mr-bottom-10">
        <button class="btn btn--medium bg-blue" onclick="habilitarUsuario()">
            🟢Habilitar / Inhabilitar
        </button>
    </div>

    <div class="pannel-table-wwf" role="tabpanel" tabindex="0" aria-labelledby="tab-lastnews-1">
        <div class="table-pro-content">
            <table class="table-pro display" style="width:100%" id="tableUsuarios">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>ROL</th>
                        <th>USUARIO</th>
                        <th>PERSONA</th>
                        <th>ESTADO</th>
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
                                    <input type="radio" name="ckUsuarios"
                                        data-id="<?=$arrRegistros[$p]['id_usuario']?>"><span>&nbsp;</span>
                                </label>
                            </div>
                        </td>
                        <td><?=$arrRegistros[$p]['id_usuario']?></td>
                        <td><?=$arrRegistros[$p]['rol_nombre']?></td>
                        <td><?=$arrRegistros[$p]['usuari_nombre']?></td>
                        <td><?=$arrRegistros[$p]['person_nombres']." ".$arrRegistros[$p]['person_apellidos']?></td>
                        <td><?= $arrRegistros[$p]['usuari_estado'] == 1 ? '🟢 Activo' : '🔴 Inactivo'; ?></td>
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
                        href="<?= ($data['pagina']==1) ? "#" : base_url()."/usuarios/page/".($data['pagina']-1) ?>">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.5 11L6.5 8L9.5 5" stroke="#868FA0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg></a>

                    <div class="total-pages"><?= $data['pagina'] ?> / <?= $data['total_paginas'] ?>
                    </div>
                    <a class="button-small-arrow arrow-right"
                        href="<?= base_url() ?>/usuarios/page/<?= ($data['pagina']< $data['total_paginas']) ? $data['pagina']+1 : $data['total_paginas'] ?>"><svg
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
<?php getModal('modalUsuarios',$data);?>
</div>
<?php
footerAdmin($data); 
?>

<script>
objRef = document.body;
objRef.classList.remove('home');
objRef.classList.add('admin');
objRef.setAttribute('data-x', 'tab');

$('#aUsuarios').addClass('active');
</script>