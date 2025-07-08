<?php 
headerPublic($data);
$arrRegistros = $data['registros'];
?>
<?php header_body_Public($data);?>

<div id="content" role="main">

    <?php 
            for ($i=0; $i < count($arrRegistros); $i++) {
      ?>
    <div class="layer-metas">
        <div class="side-list-metas">
            <div class="side-list-metas-title color-1">
                <div class="container">
                    <span class="circle-meta">E<?= $i+1?></span> <?= $arrRegistros[$i]['paisaj_estrategia'] ?>
                    <div class="progress-obj__content">
                        <div class="progress-obj">
                            <div class="progress-obj__line"
                                style="width: <?= $arrRegistros[$i]['paisaj_indicador'] ?>%;"></div>
                        </div>
                        <div class="percent-total">
                            <?= $arrRegistros[$i]['paisaj_indicador'] ?>%
                        </div>
                    </div>
                </div>
            </div>

            <div id="accordion" class="list-objective">
                <div class="description__e">
                    <?= $arrRegistros[$i]['paisaj_objetivo'] ?>
                </div>

                <div class="accordion-header ">
                    <span><b>M1</b> <?= $arrRegistros[$i]['paisaj_meta1'] ?></span>
                </div>
                <div class="accordion-header complete">
                    <span><b>M2</b> <?= $arrRegistros[$i]['paisaj_meta2'] ?></span>
                </div>

                <?php  if( $arrRegistros[$i]['paisaj_meta3'] ){ ?>
                <div class="accordion-header ">
                    <span><b>M3</b> <?= $arrRegistros[$i]['paisaj_meta3'] ?></span>
                </div>
                <?php 
                }
              ?>

                <?php  if( $arrRegistros[$i]['paisaj_meta4'] ){ ?>
                <div class="accordion-header complete">
                    <span><b>M4</b> <?= $arrRegistros[$i]['paisaj_meta4'] ?></span>
                </div>
                <?php 
                }
              ?>

                <?php  if( $arrRegistros[$i]['paisaj_meta5'] ){ ?>
                <div class="accordion-header ">
                    <span><b>M5</b> <?= $arrRegistros[$i]['paisaj_meta5'] ?></span>
                </div>
                <?php 
                }
              ?>

            </div>
        </div>
    </div>
    <?php
        }
      ?>

</div>
<?php 
  footerPublic($data);
?>

<script>
objRef = document.body;
objRef.classList.remove('home');
objRef.classList.add('app');

objRef.setAttribute('data-x', 'tab');
</script>