<?php 
headerPublic($data);
$arrProgramas = $data['programas'];
$arrRegistros = $data['registros'];
?>
<?php header_body_Public($data);?>

  <div id="content" role="main">

    <div class="title-wrap-bg bg-3">
      <div class="container pad">
        <div class="title-app-page bg-brown">
          <svg width="38" height="37" viewBox="0 0 49 44" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M32.6667 39.1111L16.3333 33.9533V4.88889L32.6667 10.0467M47.6389 0C47.4756 0 47.3394 0 47.2033 0L32.6667 5.13333L16.3333 0L0.98 4.64444C0.408333 4.81556 0 5.25556 0 5.81778V42.7778C0 43.1019 0.143403 43.4128 0.39866 43.642C0.653918 43.8712 1.00012 44 1.36111 44C1.49722 44 1.66056 44 1.79667 43.9267L16.3333 38.8667L32.6667 44L48.02 39.3556C48.5917 39.1111 49 38.7444 49 38.1822V1.22222C49 0.898069 48.8566 0.587192 48.6013 0.357981C48.3461 0.12877 47.9999 0 47.6389 0Z" fill="white"></path></svg>
          <?=$data['page_title']?>
        </div>
      </div>
    </div>

    <div class="container pad">
      <div class="row"> 
        <div class="tabs">
          <div class="x-tab right" role="tablist" aria-label="">
            <?php if(!empty($arrProgramas)){ 
              for ($p=0; $p < count($arrProgramas); $p++) {
            ?>
            <a href="<?= base_url() ?>/planoteca/programas/<?=$arrProgramas[$p]['id_programa']?>/1"><button id="tab-lastnews-<?=$arrProgramas[$p]['id_programa']?>" role="tab" aria-selected="<?= ($arrProgramas[$p]['id_programa']==$data['id_programa']) ? "true":"false" ?>" aria-controls="panel--<?=$arrProgramas[$p]['id_programa']?>" tabindex="0" onclick="xsl.ui.tab(this, event)"><span><?=$arrProgramas[$p]['progra_nombre']?></span></button></a>
            <?php } 
              }
            ?>
          </div>
          <div id="panel--1" role="tabpanel" tabindex="0" aria-labelledby="tab-lastnews-2">
            <?php if(!empty($arrRegistros)){ ?>
            <div class="pannel-table-wwf" role="tabpanel" tabindex="0" aria-labelledby="tab-lastnews-1">
              <div class="pannel-table-wwf--header">

                <form method="get" action="<?= base_url() ?>/planoteca/search">
                  <select name="t" id="t">
                    <option value="1">Nombre</option>
                    <option value="2">Tag</option>
                  </select>
                  <input type="hidden" name="p" value="1">
                  <input type="hidden" name="id_programa" value="<?=$data['id_programa']?>">
                  <input type="text" id="s" name="s" placeholder="Nombre del plano" style="width:30%">
                </form>

              </div>
              <div class="table-pro-content">
                <table class="table-pro display" style="width:100%">
                  <thead>
                    <tr>
                      <th  style="text-align: center;" width="15%">CÓDIGO</th>
                      <th  style="text-align: center;" width="45%">NOMBRE DE PLANO</th>
                      <th  style="text-align: center;" width="10%">ARCHIVO</th>
                      <th  style="text-align: center;" width="30%">IMAGEN</th>
                    </tr>
                  </thead>
                  <tbody>
                  	<?php 
  										for ($p=0; $p < count($arrRegistros); $p++) { 
  								  ?>
                      <tr>
                        <td><?=$arrRegistros[$p]['planot_codigo']?></td>
                        <td>
                          <?=$arrRegistros[$p]['planot_nombre']?>
                          <br>
                          <b>Tag's:</b> <?=$arrRegistros[$p]['planot_tag']?>
                        </td>
                        <td class="text-center">
                          <a href="<?=$arrRegistros[$p]['planot_archivo']?>" target="_blank"><button class="btn-sm" >
                          	<svg width="16" height="20" viewBox="0 0 16 20" fill="none"><path d="M10 0L16 6V18C16 18.5304 15.7893 19.0391 15.4142 19.4142C15.0391 19.7893 14.5304 20 14 20H2C1.46957 20 0.960859 19.7893 0.585786 19.4142C0.210714 19.0391 0 18.5304 0 18V2C0 1.46957 0.210714 0.960859 0.585786 0.585786C0.960859 0.210714 1.46957 0 2 0H10ZM14 18V7H9V2H2V18H14ZM8 17L4 13H6.5V10H9.5V13H12L8 17Z" fill="#464F60"/></svg>
                          </button>
                        </a>
                        </td>
                        <td style="text-align: center;"  class="text-center">
                          <img src="<?= media() ?>/images/uploads/<?=$arrRegistros[$p]['planot_img']?>" style="max-width: 30%;" />
                        </td>
                      </tr>
                    <?php 
  										  } 
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="footer-pagination">
                <div class="total-items"><?= sizeof($data['registros']) ?> de <?= $data['total_registros'] ?></div>
                <div class="pagination-datatable">
                  <a class="button-small-arrow arrow-left" href="<?= ($data['pagina']==1) ? "#" : base_url()."/planoteca/programas/".$data['id_programa']."/".($data['pagina']-1) ?>"> <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.5 11L6.5 8L9.5 5" stroke="#868FA0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>

                  <div class="total-pages"><?= $data['pagina'] ?> / <?= $data['total_paginas'] ?></div><a class="button-small-arrow arrow-right" href="<?= base_url() ?>/planoteca/programas/<?= $data['id_programa']?>/<?= ($data['pagina']< $data['total_paginas']) ? $data['pagina']+1 : $data['total_paginas'] ?>"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.5 11L9.5 8L6.5 5" stroke="#464F60" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                </div>
              </div>
            </div>
            <?php 
                }
                else{ 
              ?>
                <p>No hay registros para mostrar <a href="<?= base_url() ?>home"> < Regresar al Inicio</a></p>
            <?php
                }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php footerPublic($data);?>

<script>
	objRef = document.body;
	objRef.classList.remove('home');
	objRef.classList.add('app');

	objRef.setAttribute('data-x','tab');
</script>