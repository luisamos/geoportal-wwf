<div class="lbox" id="modalFormPortada">
    <div class="lbox--title" id="titleModal">Formulario Portada</div>
    <form action="post" id="formPortada" name="formPortada">

        <input type="hidden" id="id_portada" name="id_portada" value="">
        <input type="hidden" id="foto_actual" name="foto_actual" value="">
        <input type="hidden" id="foto_remove" name="foto_remove" value="0">

        <div class="grid">

            <div class="col--12">
                <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>
            </div>

            <div class="col--6">Titulo *</div>
            <div class="col--6">
                <input class="form-control" id="titulo" name="titulo" type="text"
                    onblur="javascript:this.value=this.value.toUpperCase();" required="">
            </div>
            <div class="col--6">Descripción *</div>
            <div class="col--6">
                <textarea class="form-control" id="descripcion" name="descripcion" maxlength="300"></textarea>
            </div>
            <div class="col--6">Imagen (570 x 380)</div>
            <div class="col--6">
                <div class="photo">
                    <label for="foto">Formatos: .jpg .png .jpge</label>
                    <div class="prevPhoto">
                        <span class="delPhoto notBlock">X</span>
                        <label for="foto"></label>
                        <div>
                            <img id="img" src="<?= media(); ?>/images/uploads/noticia_noticia.png">
                        </div>
                    </div>
                    <div class="upimg">
                        <input type="file" name="foto" id="foto">
                    </div>
                    <div id="form_alert"></div>
                </div>
            </div>

            <div class="col--12">
                <div class="text-center"><br>
                    <button class="btn btn--small">Guardar</button>
                    <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> Cancelar</button>
                </div>
            </div>

        </div>
    </form>
</div>

<div class="lbox" id="modalFormPortadaDel">
    <div class="lbox--title" id="titleModal">Eliminar Portada</div>
    <form name="formPotadaDel" id="formPotadaDel">

        <input type="hidden" name="id_portada_del" id="id_portada_del">

        <div class="grid">
            <div class="col--12">¿Realmente quiere eliminar la Portada?</div>

            <div class="col--12">
                <div class="text-center"><br>
                    <button class="btn btn--small">Si, eliminar!</button>
                    <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> No,
                        cancelar!</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="lbox" id="modalFormRecortarPortada">
    <div class="lbox--title" id="titleModalRecortar">Formulario Portada</div>
    <form action="post" id="formRecortarPortada" name="formRecortarPortada">

        <input type="hidden" id="id_portada_recortar" name="id_portada_recortar" value="">
        <div class="grid">

            <div class="col--12">
                <img id="imgCrop" src="../../../Assets/images/uploads/img2.jpg">
            </div>

            <div class="col--12">
                <div class="text-center"><br>
                    <button class="btn btn--small">Recortar</button>
                    <button class="btn btn--small bg-gray" type="button" onclick="Fancybox.close()"> Cancelar</button>
                </div>
            </div>

        </div>
    </form>
</div>