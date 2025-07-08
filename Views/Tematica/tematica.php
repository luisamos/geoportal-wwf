<?php 
  headerAdmin($data);
  $listaCategorias = $data['lista_categorias'];
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<?php header_body_Admin($data);?>
<div
    style="display: flex; justify-content: space-around; padding: 20px; background-color: #ffffff; border-radius: 10px;">
    <div class="scrollable">
        <div class="header-row">
            <h5>Categorías</h5>
            <button id="guardarCategoria" class="btn-sm" title="Guardar orden">
                <svg width="16" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M19,0H3A3,3,0,0,0,0,3V24H24V5ZM7,2H17V6H7ZM22,22H2V3A1,1,0,0,1,3,2H5V8H19V2.828l3,3Z"
                        fill="#464F60" />
                    <path d="M12,10a4,4,0,1,0,4,4A4,4,0,0,0,12,10Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,16Z" fill="#464F60" />
                </svg>
            </button>
        </div>
        <ul id="listaCategorias" class="sortable-list">
            <?php
                for ($i=0; $i < count($listaCategorias); $i++) {
                    echo "<li id='{$listaCategorias[$i]['id_tematica']}' class='item'>
                            <span class='handle'>☰</span>
                            {$listaCategorias[$i]['nombre']}
                        </li>";
                }
                ?>
        </ul>
    </div>
    <div>&nbsp; &nbsp;</div>
    <div style="display: flex; flex-direction: column; justify-content: center; gap: 10px;">
        <button class="btn btn--medium bg-blue" data-fancybox data-src="#modalFormCategoria"
            onclick="openModalCategoriaAdd()" title="Agregar categoría">
            <div class="bn--icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 10H10V16H6V10H0V6H6V0H10V6H16V10Z" fill="white" />
                </svg></div>
        </button>
        <button class="btn btn--medium bg-blue" onclick="openModalCategoriaEdit();" title="Modificar categoría">
            <div class="btn--icon"><svg width="20" height="19" viewBox="0 0 20 19" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M18 7V6L12 0H2C0.89 0 0 0.89 0 2V16C0 17.11 0.9 18 2 18H8V16.13L16.39 7.74C16.83 7.3 17.39 7.06 18 7ZM11 1.5L16.5 7H11V1.5ZM19.85 11.19L18.87 12.17L16.83 10.13L17.81 9.15C18 8.95 18.33 8.95 18.53 9.15L19.85 10.47C20.05 10.67 20.05 11 19.85 11.19ZM16.13 10.83L18.17 12.87L12.04 19H10V16.96L16.13 10.83Z"
                        fill="white" />
                </svg></div>
        </button>
        <button class="btn btn--medium bg-blue" onclick="openModalCategoriaDel();" title="Eliminar categoría">
            <div class="btn--icon"><svg width="16" height="18" viewBox="0 0 16 18" fill="none">
                    <path
                        d="M5 0V1H0V3H1V16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H13C13.5304 18 14.0391 17.7893 14.4142 17.4142C14.7893 17.0391 15 16.5304 15 16V3H16V1H11V0H5ZM5 5H7V14H5V5ZM9 5H11V14H9V5Z"
                        fill="white" />
                </svg></div>
        </button>
    </div>
    <div>&nbsp; &nbsp;</div>
    <div class="scrollable">
        <div class="header-row">
            <h5>Subcategorías</h5>
            <button id="guardarSubCategoria" class="btn-sm">
                <svg width="16" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M19,0H3A3,3,0,0,0,0,3V24H24V5ZM7,2H17V6H7ZM22,22H2V3A1,1,0,0,1,3,2H5V8H19V2.828l3,3Z"
                        fill="#464F60" />
                    <path d="M12,10a4,4,0,1,0,4,4A4,4,0,0,0,12,10Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,16Z" fill="#464F60" />
                </svg>
            </button>
        </div>
        <ul id="listaSubCategorias" class="sortable-list">
            <li id="-1" class="item"><span class="handle">☰</span>Ninguno</li>
        </ul>
    </div>
    <div>&nbsp; &nbsp;</div>
    <div style="display: flex; flex-direction: column; justify-content: center; gap: 10px;">
        <button class="btn btn--medium bg-blue" data-src="#modalFormSubCategoria" onclick="openModalSubCategoriaAdd();">
            <div class="bn--icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 10H10V16H6V10H0V6H6V0H10V6H16V10Z" fill="white" />
                </svg></div>
        </button>
        <button class="btn btn--medium bg-blue" onclick="openModalSubCategoriaEdit();">
            <div class="btn--icon"><svg width="20" height="19" viewBox="0 0 20 19" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M18 7V6L12 0H2C0.89 0 0 0.89 0 2V16C0 17.11 0.9 18 2 18H8V16.13L16.39 7.74C16.83 7.3 17.39 7.06 18 7ZM11 1.5L16.5 7H11V1.5ZM19.85 11.19L18.87 12.17L16.83 10.13L17.81 9.15C18 8.95 18.33 8.95 18.53 9.15L19.85 10.47C20.05 10.67 20.05 11 19.85 11.19ZM16.13 10.83L18.17 12.87L12.04 19H10V16.96L16.13 10.83Z"
                        fill="white" />
                </svg></div>
        </button>
        <button class="btn btn--medium bg-blue" onclick="openModalSubCategoriaDel()">
            <div class="btn--icon"><svg width="16" height="18" viewBox="0 0 16 18" fill="none">
                    <path
                        d="M5 0V1H0V3H1V16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H13C13.5304 18 14.0391 17.7893 14.4142 17.4142C14.7893 17.0391 15 16.5304 15 16V3H16V1H11V0H5ZM5 5H7V14H5V5ZM9 5H11V14H9V5Z"
                        fill="white" />
                </svg></div>
        </button>
    </div>
    <div>&nbsp; &nbsp;</div>
    <div class="scrollable">
        <div class="header-row">
            <h5>Capas y/o Servicios</h5>
            <button id="guardarCapasServicios" class="btn-sm">
                <svg width="16" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M19,0H3A3,3,0,0,0,0,3V24H24V5ZM7,2H17V6H7ZM22,22H2V3A1,1,0,0,1,3,2H5V8H19V2.828l3,3Z"
                        fill="#464F60" />
                    <path d="M12,10a4,4,0,1,0,4,4A4,4,0,0,0,12,10Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,16Z" fill="#464F60" />
                </svg>
            </button>
        </div>
        <ul id="listaCapasServicios" class="sortable-list">
            <li id="-1" class="item"><span class="handle">☰</span>Ninguno</li>
        </ul>
    </div>
</div>

</div>
</div>
<?php getModal('modalTematica',$data);?>
</div>

<?php footerAdmin($data);?>

<script>
objRef = document.body;
objRef.classList.remove('home');
objRef.classList.add('admin');
objRef.setAttribute('data-x', 'tab');

$('#aTematica').addClass('active');
</script>