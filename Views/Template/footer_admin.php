    <footer class="footer">
        <div class="container">
            <div class="footer-details flex between">
                <div class="footer-details__wrap-logo-button flex g-30 align-center">
                    <div class="mr-logo"> <img src="<?= media(); ?>/public/img/wwf-logo.svg" alt="WWF" width="90"></div>
                    <div class="label-wwf">Por un futuro en el que los<br> humanos vivan en armonía<br> con la
                        naturaleza.</div>
                </div>
                <div class="footer-details__links">
                    <div class="footer-details__title">Enlaces de interés</div>
                    <div class="list-links-rl">
                        <div class="list-links__link"><a href="#">Lorems Ipsum</a></div>
                        <div class="list-links__link"><a href="#">Adipisicing elit</a></div>
                        <div class="list-links__link"><a href="#">Necessitatibus deserunt</a></div>
                        <div class="list-links__link"><a href="#">Necessitatibus deserunt</a></div>
                        <div class="list-links__link"><a href="#">Molestiae dicta</a></div>
                        <div class="list-links__link"><a href="#">Adipisicing elit</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="more-footer-details">
            <div class="container">Fotos y gráficos © WWF o usados con permiso. Texto disponible bajo <a
                    href="http://creativecommons.org/licenses/by-sa/3.0/deed.es" target="_blank">licencia de Creative
                    Commons</a>.</div>
        </div>
    </footer>

    <script>
const base_url = "<?= base_url(); ?>";
    </script>
    <!-- Essential javascripts for application to work-->

    <script type="text/javascript" src="<?= media(); ?>/public/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?= media(); ?>/public/js/fancybox.umd.js" defer></script>
    <script type="text/javascript" src="<?= media(); ?>/public/js/app.js" defer></script>

    <script type="text/javascript" src="<?= media(); ?>/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="<?= media();?>/js/functions_admin.js" defer></script>
    <script type="text/javascript" src="<?= media(); ?>/js/<?= $data['page_functions_js']; ?>" defer></script>

    </div>
    </body>

    </html>