<div class="lbox" id="modalFormCategoria">
    <div class="lbox--title" id="titleModalCategoria">Formulario Categoría</div>
    <form action="post" id="formCategoria" name="formCategoria">

        <input type="hidden" name="idCategoria" id="idCategoria" value="">

        <div class="grid">
            <div class="col--4">Nombre:</div>
            <div class="col--8">
                <input type="text" name="nombreCategoria" id="nombreCategoria" maxlength="60" required>
            </div>
            <div class="col--4">Visible:</div>
            <div class="col--8">
                <select name="visibleCategoria" id="visibleCategoria">
                    <option value="1" selected>Si</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="col--12">
                <div class="text-center"><br>
                    <button class="btn btn--small">Guardar</button>
                    <button class="btn btn--small bg-gray" onclick="Fancybox.close()"> Cancelar</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="lbox" id="modalFormCategoriaDel">
    <div class="lbox--title">Eliminar Categoría</div>
    <form name="formCategoriaDel" id="formCategoriaDel">

        <input type="hidden" name="idCategoriaDel" id="idCategoriaDel">

        <div class="grid">
            <div class="col--12">¿Realmente quiere eliminar la categoría?</div>

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

<div class="lbox" id="modalFormSubCategoria">
    <div class="lbox--title" id="titleModalSubCategoria">Formulario Subcategoría</div>
    <form action="post" id="formSubCategoria" name="formSubCategoria">

        <input type="hidden" name="idSubCategoria" id="idSubCategoria" value="">
        <input type="hidden" name="idPadre" id="idPadre" value="">

        <div class="grid">
            <div class="col--4">Categoria:</div>
            <div class="col--8">
                <input type="text" name="nombrePadre" id="nombrePadre" maxlength="60" required>
            </div>
            <div class="col--4">Nombre:</div>
            <div class="col--8">
                <input type="text" name="nombreSubCategoria" id="nombreSubCategoria" maxlength="60" required>
            </div>
            <div class="col--4">Visible:</div>
            <div class="col--8">
                <select name="visibleSubCategoria" id="visibleSubCategoria">
                    <option value="1" selected>Si</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="col--12">
                <div class="text-center"><br>
                    <button class="btn btn--small">Guardar</button>
                    <button class="btn btn--small bg-gray" onclick="Fancybox.close()"> Cancelar</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="lbox" id="modalFormSubCategoriaDel">
    <div class="lbox--title">Eliminar Subcategoría</div>
    <form name="formSubCategoriaDel" id="formSubCategoriaDel">

        <input type="hidden" name="idSubCategoriaDel" id="idSubCategoriaDel">

        <div class="grid">
            <div class="col--12">¿Realmente quiere eliminar la subcategoría?</div>

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