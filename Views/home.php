<?php
  headerPublic($data);
  $arrPortadas = $data['portada_imagenes'];
  $arrNoticias = $data['noticias'];
?>

<link rel="stylesheet" href="<?= media() ?>/public/css/home.css">
<!-- Aqui escribir script si es que se necesita -->

<?php
header_body_Public($data);
?>

<div id="content" role="main">
    <div class="splide-container">
        <section class="splide" id="splideMain" aria-label="">
            <div class="splide__track">
                <ul class="splide__list">

                    <?php
                for ($i=0; $i < count($arrPortadas) ; $i++) {
              ?>
                    <li class="splide__slide">
                        <div class="splide__slide__container">
                            <div class="container">
                                <div class="paragraph">
                                    <div class="title-slide"><?= $arrPortadas[$i]['portad_titulo'];?></div>
                                    <div class="caption-slide"><?= $arrPortadas[$i]['portad_descrip'];?></div>
                                </div>
                            </div><img class="background-slide" src="<?= $arrPortadas[$i]['portad_imagen'];?>" alt="">
                        </div>
                    </li>
                    <?php
              }
              ?>
                </ul>
            </div>
        </section>
        <div class="container-menu-apps">
            <div class="menu-apps container" style="background: none !important;">
                <a style="margin-right: 15px;border-radius: 20px;" class="item-app item-app-01"
                    href="<?= base_url() ?>visor" target="_blank">
                    <!-- <svg width="49" height="45" viewBox="0 0 49 45" fill="none">
                    <path d="M32.6667 39.6111L16.3333 34.4533V5.38889L32.6667 10.5467M47.6389 0.5C47.4756 0.5 47.3394 0.5 47.2033 0.5L32.6667 5.63333L16.3333 0.5L0.98 5.14444C0.408333 5.31556 0 5.75556 0 6.31778V43.2778C0 43.6019 0.143403 43.9128 0.39866 44.142C0.653918 44.3712 1.00012 44.5 1.36111 44.5C1.49722 44.5 1.66056 44.5 1.79667 44.4267L16.3333 39.3667L32.6667 44.5L48.02 39.8556C48.5917 39.6111 49 39.2444 49 38.6822V1.72222C49 1.39807 48.8566 1.08719 48.6013 0.857981C48.3461 0.62877 47.9999 0.5 47.6389 0.5Z" fill="#fff"/>
                    </svg> -->
                    <div class="menu-apps--icon"><img src="<?= media() ?>/public/img/ubicacion.png" alt=""
                            style="width: 70px; height: 70px;"></div>

                    <div class="item-app--title">Visor</div>
                </a>
                <a style="margin-right: 15px; border-radius: 15px;" class="item-app item-app-02"
                    href="https://experience.arcgis.com/experience/d1225f50d78443068ccb330154aeaaf8" target="_blank">
                    <div class="menu-apps--icon"><img src="<?= media() ?>/public/img/indicadores.png" alt=""
                            style="width: 85px; height: 85px;"></div>Indicadores
                </a>
                <a style="margin-right: 15px; border-radius: 15px;" class="item-app item-app-03"
                    href="https://app.powerbi.com/groups/me/reports/0321b9cf-d30c-405a-bb58-9891b2ac7225/b5f810673c480b42d063?ctid=68a878b0-8b0b-4459-ac6c-2082f0e189db&experience=power-bi"
                    target="_blank">
                    <div class="menu-apps--icon"><img src="<?= media() ?>/public/img/repositorio.png" alt=""
                            style="width: 55px; height: 55px;"></div>Repositorio WWF-Perú
                </a>
                <a style="margin-right: 15px; border-radius: 15px;" class="item-app item-app-04"
                    href="https://app.powerbi.com/groups/me/reports/0321b9cf-d30c-405a-bb58-9891b2ac7225/b5f810673c480b42d063?ctid=68a878b0-8b0b-4459-ac6c-2082f0e189db&experience=power-bi"
                    target="_blank">
                    <div class="menu-apps--icon"><img src="<?= media() ?>/public/img/solicitudes.png" alt=""
                            style="width: 75px; height: 75px;"></div>Solicitudes GIS
                </a>
                <a style="margin-right: 15px; border-radius: 20px;" class="item-app item-app-05"
                    href="<?= base_url() ?>aplicaciones">
                    <svg width="50" height="45" viewBox="0 0 50 45" fill="none">
                        <path
                            d="M45.4545 0.5H4.54545C2.04545 0.5 0 2.48 0 4.9V40.1C0 42.52 2.04545 44.5 4.54545 44.5H45.4545C47.9545 44.5 50 42.52 50 40.1V4.9C50 2.48 47.9545 0.5 45.4545 0.5ZM22.7273 20.3H18.1818V23.6C18.1818 26.02 16.1364 28 13.6364 28C16.1364 28 18.1818 29.98 18.1818 32.4V35.7H22.7273V40.1H18.1818C15.6818 40.1 13.6364 38.12 13.6364 35.7V34.6C13.6364 32.18 11.5909 30.2 9.09091 30.2V25.8C11.5909 25.8 13.6364 23.82 13.6364 21.4V20.3C13.6364 17.88 15.6818 15.9 18.1818 15.9H22.7273V20.3ZM40.9091 30.2C38.4091 30.2 36.3636 32.18 36.3636 34.6V35.7C36.3636 38.12 34.3182 40.1 31.8182 40.1H27.2727V35.7H31.8182V32.4C31.8182 29.98 33.8636 28 36.3636 28C33.8636 28 31.8182 26.02 31.8182 23.6V20.3H27.2727V15.9H31.8182C34.3182 15.9 36.3636 17.88 36.3636 20.3V21.4C36.3636 23.82 38.4091 25.8 40.9091 25.8V30.2ZM45.4545 11.5H4.54545V4.9H45.4545V11.5Z"
                            fill="#fff" />
                    </svg>
                    <div class="item-app--title">Aplicaciones</div>
                </a>
            </div>
        </div>
    </div>
    <div class="block-inserve">
        <div class="container pad xs">
            <div class="title-block inverse"><span>QUE ES PGI </span></div>
            <div class="mr-bottom-35">
                <p>Plataforma de Gestión de la Información es un espacio donde encontrara información geoespacial y
                    técnico documentario de los proyectos que WWF Perú viene realizando en los paisajes amazónicos y
                    pacífico.</p>
            </div>
        </div>
    </div>
    <div style="display: none;" class="container pad">
        <div class="title-block"><span>NOTICIAS </span></div>
        <section class="splide mr-bottom-40" id="splideNews" aria-label="">
            <div class="splide__track">
                <ul class="splide__list">

                    <?php
                  if(!empty($arrNoticias)){
                    for ($p=0; $p < count($arrNoticias); $p++) {
                      $ruta = $arrNoticias[$p]['notici_ruta'];
                      if($arrNoticias[$p]['imagen1']){
                        $portada = $arrNoticias[$p]['imagen1'];
                      }else{
                        $portada = media().'/images/uploads/product.png';
                      }
                ?>

                    <li class="splide__slide">
                        <div class="splide__slide__container">
                            <div class="card-new">
                                <div class="cover-card"> <a
                                        href="<?= $arrNoticias[$p]['notici_tipo']==1 ? base_url().'/noticias/ver/'.$arrNoticias[$p]['id_noticia'].'/'.$ruta : $arrNoticias[$p]['notici_url'] ?>"
                                        target="_blank"><img src="<?= $portada ?>"
                                            alt="<?= $arrNoticias[$p]['notici_titulo'] ?>" alt=""></a></div>
                                <div class="caption-card">
                                    <h2> <a href="<?= $arrNoticias[$p]['notici_tipo']==1 ? base_url().'/noticias/ver/'.$arrNoticias[$p]['id_noticia'].'/'.$ruta : $arrNoticias[$p]['notici_url'] ?>"
                                            target="_blank"><?= $arrNoticias[$p]['notici_titulo'] ?></a></h2>
                                </div>
                            </div>
                        </div>
                    </li>

                    <?php
                    }
                  }
                ?>
                </ul>
            </div>
        </section>
        <div class="row text-right mr-bottom-40"><a class="btn" href="#">VER MÁS NOTICIAS <svg width="15" height="22"
                    viewBox="0 0 15 22" fill="none">
                    <path
                        d="M13.685 12.1068L4.83084 20.9609C4.17981 21.612 3.20325 21.612 2.61731 20.9609L1.11992 19.5287C0.533987 18.8776 0.533987 17.9011 1.11992 17.3151L7.435 11.0652L1.11992 4.75008C0.533987 4.16414 0.533987 3.18758 1.11992 2.53654L2.61731 1.03915C3.20325 0.453218 4.17981 0.453218 4.83084 1.03915L13.685 9.89328C14.2709 10.5443 14.2709 11.5209 13.685 12.1068Z"
                        fill="white" />
                </svg></a></div>
    </div>

    <script src="<?= media(); ?>/public/js/splide.min.js" defer></script>
    <?php
footerPublic($data);
?>