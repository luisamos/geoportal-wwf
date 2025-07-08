<?php headerAdmin($data);?>
</head>

<body class="home" data-x="home">
    <div id="wrap">

        <header class="header" id="headerSite">
            <div class="container">
                <div class="header-inner">
                    <div class="top-header flex between">
                        <div class="start-header align-center flex">
                            <div class="logo-wwf"><a href="<?= base_url() ?>home"><img
                                        src="<?= media() ?>/public/img/wwf-logo.svg" alt=""></a></div>
                            <div class="slogan-wwf"><?= $data['page_title'] ?></div>
                            <div class="main-menu">
                                <div class="item-nav"><a href="<?= base_url() ?>home">INICIO</a></div>
                                <div class="item-nav"><a href="#">NOSOTROS </a></div>
                            </div>
                        </div>
                        <div class="end-header">
                            <div class="language"><img src="<?= media(); ?>/public/img/global.svg" width="30"
                                    height="31" alt=""></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div id="content" role="main">
            <div class="title-wrap-bg">
                <div class="container pad">
                    <a href="<?= base_url() ?>gestion">
                        <div class="title-app-page bg-violet"><img src="<?= media() ?>/public/img/cms.png" alt=""
                                style="width: 45px; height: 42px;"><?= $data['page_title']; ?></div>
                    </a>
                </div>
            </div>

            <div class="container pad">

                <div class="login-box">

                    <form class="login-form" name="formLogin" id="formLogin" action="">
                        <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>INICIAR SESIÓN</h3>
                        <div class="form-group">
                            <label class="control-label">USUARIO</label>
                            <input id="txtUsuario" name="txtUsuario" class="form-control" type="text"
                                placeholder="Usuario" autofocus>
                        </div>
                        <div class="form-group">
                            <label class="control-label">CLAVE</label>
                            <input id="txtClave" name="txtClave" class="form-control" type="password"
                                placeholder="Clave">
                        </div>
                        <div class="form-group">
                            <div class="utility">
                                <!-- <p class="semibold-text mb-2"><a href="#" data-toggle="flip">¿Olvidaste tu contraseña?</a></p> -->
                            </div>
                        </div>
                        <div id="alertLogin" class="text-center"></div>
                        <div class="form-group btn-container">
                            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i>
                                Ingresar</button>
                        </div>
                    </form>

                    <form id="formRecetPass" name="formRecetPass" class="forget-form" action="" style="display:none;">
                        <h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>¿Olvidaste contraseña?</h3>
                        <div class="form-group">
                            <label class="control-label">EMAIL</label>
                            <input id="txtUsuarioReset" name="txtUsuarioReset" class="form-control" type="text"
                                placeholder="Usuario">
                        </div>
                        <div class="form-group btn-container">
                            <button type="submit" class="btn btn-primary btn-block"><i
                                    class="fa fa-unlock fa-lg fa-fw"></i>REINICIAR</button>
                        </div>
                        <div class="form-group mt-3">
                            <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i
                                        class="fa fa-angle-left fa-fw"></i> Iniciar sesión</a></p>
                        </div>
                    </form>

                </div>

            </div>

        </div>

        <?php footerAdmin($data);?>

        <script>
        objRef = document.body;
        objRef.classList.remove('home');
        objRef.classList.add('admin');
        objRef.setAttribute('data-x', 'tab');

        $('#aCapasGeograficas').addClass('active');
        </script>