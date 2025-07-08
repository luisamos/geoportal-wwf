<?php
headerPublic($data);
$arrRegistros = $data['noticia'];
?>
<?php header_body_Public($data);?>

  <div id="content" role="main">

    <div class="title-wrap-bg">
      <div class="container pad">
        <div class="title-app-page" style="background-color: #434040;">
            <svg width="45" height="45" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 3H4C2.89 3 2 3.89 2 5V19C2 20.11 2.89 21 4 21H20C21.11 21 22 20.11 22 19V5C22 3.89 21.11 3 20 3ZM5 7H10V13H5V7ZM19 17H5V15H19V17ZM19 13H12V11H19V13ZM19 9H12V7H19V9Z" fill="#fff"/>
                </svg>

          NOTICIAS        </div>
      </div>
    </div>

    <div class="container pad">
        <div class="article">
            <div class="article__headline">
                <h1><?= $arrRegistros['notici_titulo']?></h1>
            </div>

            <div class="article__body">
                <div class="article__cover">
                    <img src="<?= $arrRegistros['img1']?>" alt="">
                </div>
                <div class="article__date">
                    <?= $arrRegistros['created']?>
                </div>


                <p><?= $arrRegistros['notici_descripcion']?></p>

                <div class="article__cover">
                    <img src="<?= $arrRegistros['img2']?>" alt="">
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