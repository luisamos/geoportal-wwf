<?php headerPublic($data);?>
<?php header_body_Public($data);?>

  <div id="content" class="bg-indicador" role="main">
    <div class="title-wrap-bg">
      <div class="container pad">
        <div class="title-app-page bg-violet">INDICADORES Y OBJETIVOS</div>
      </div>
    </div>
    <div class="container pad">
      <div class="admin-layer"> 
        <div class="admin-layer--indicador indicador-r">

          <div class="flex g-20 justify-center">
            <div class="col">
              <a href="<?= base_url() ?>/estrategias/paisaje/1">
                <div class="item-indicador-col" style="background-image: url(<?= media() ?>/public/img/bosque-amazonia.jpg);">
                  <div class="item-indicador-col__title">Amazonía</div>
                </div>
              </a>
            </div>
            <div class="col">
                <a href="<?= base_url() ?>/estrategias/paisaje/2">
                  <div class="item-indicador-col" style="background-image: url(<?= media() ?>/public/img/bosque-de-altura.jpg);">
                    <div class="item-indicador-col__title">Pacífico</div>
                  </div>
                </a>
              </div>
           
          
          </div>
        </div>
      </div>
    </div>

  </div>
<?php 
  footerPublic($data);
?>

<script>
  objRef = document.body;
  objRef.classList.remove('home');
  objRef.classList.add('app');

  objRef.setAttribute('data-x','tab');
</script>