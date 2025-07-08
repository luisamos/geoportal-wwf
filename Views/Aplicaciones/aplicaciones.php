<?php headerPublic($data);?>
<link rel="stylesheet" href="<?= media() ?>/public/css/main.css">
<?php header_body_Public($data);?>

<div id="content" role="main">
    <div class="title-wrap-bg">
        <div class="container pad">
            <div class="title-app-page bg-aplicaciones">
                <svg width="44" height="40" viewBox="0 0 44 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M40 0H4C1.8 0 0 1.8 0 4V36C0 38.2 1.8 40 4 40H40C42.2 40 44 38.2 44 36V4C44 1.8 42.2 0 40 0ZM20 18H16V21C16 23.2 14.2 25 12 25C14.2 25 16 26.8 16 29V32H20V36H16C13.8 36 12 34.2 12 32V31C12 28.8 10.2 27 8 27V23C10.2 23 12 21.2 12 19V18C12 15.8 13.8 14 16 14H20V18ZM36 27C33.8 27 32 28.8 32 31V32C32 34.2 30.2 36 28 36H24V32H28V29C28 26.8 29.8 25 32 25C29.8 25 28 23.2 28 21V18H24V14H28C30.2 14 32 15.8 32 18V19C32 21.2 33.8 23 36 23V27ZM40 10H4V4H40V10Z"
                        fill="white" />
                </svg>
                APLICACIONES
            </div>
        </div>
    </div>
    <div class="container pad">
        <div class="menu-apps">
            <!--<a class="menu-apps--item bg-green" href="#" target="_blank">
          <div class="menu-apps--icon"><img src="<?= media() ?>/public/img/trazapp.png" alt=""></div>TrazApp </a>-->

            <!-- <a class="menu-apps--item bg-medium_green" href="<?= base_url() ?>/visor" target="_blank">
          <div class="menu-apps--icon"><img src="<?= media() ?>/public/img/location.png" alt="" style="width: 74px; height: 80px;"></div>Visor </a> -->

            <!-- <a class="menu-apps--item bg-base" href="<?= base_url() ?>/paisajes" target="_blank">
          <div class="menu-apps--icon"> <img src="<?= media() ?>/public/img/benchmarking.png" alt="" style="height: 85px; width: 84"></div>Seguimiento de indicadores </a>
          <a class="menu-apps--item bg-blue" href="<?= base_url() ?>/archivos_campo/tipo/5/1" target="_blank">
          <div class="menu-apps--icon"><img src="<?= media() ?>/public/img/cms.png" alt="" style="width: 80px; height: 75px;"></div>Trabajos de campo </a>
           -->
            <a class="menu-apps--item bg-violet" href="<?= base_url() ?>ingreso" target="_blank">
                <div class="menu-apps--icon"><img src="<?= media() ?>/public/img/cms.png" alt=""
                        style="width: 80px; height: 75px;"></div>Gestión de geoportal
            </a>

            <a class="menu-apps--item bg-aqua"
                href="https://www.trazapp.org/">
                <div class="menu-apps--icon"><img src="<?= media() ?>/public/img/trazapp.png" alt="">
                </div>
                <div class="item-app--title">TrazApp</div>
            </a>
        </div>
    </div>
</div>

<?php footerPublic($data);?>

<script>
objRef = document.body;
objRef.classList.remove('home');
objRef.classList.add('app');

objRef.setAttribute('data-x', 'tab');
</script>