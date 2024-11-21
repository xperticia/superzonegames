<div class="modal fade hide" id="modalMapaUbicacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form>
        <input type="hidden" name="origen" value="" />

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>    
            <h3>Seleccionar ubicaci&oacute;n</h3>        
        </div>
        <div class="modal-body" data-apimapa="<?php if(isset($sitio_apimapa)) { echo($sitio_apimapa); } ?>" data-typemapa="<?php if(isset($sitio_typemapa)) { echo($sitio_typemapa); } ?>" data-keymapa="<?php if(isset($sitio_keymapa)) { echo($sitio_keymapa); } ?>" style="padding: 0px;">
            <div id="mapa"></div>
        </div>
        <div class="modal-footer">
            <input type="hidden" id="codigo_pais" />
            <input type="hidden" id="pais" />
            <input type="hidden" id="provincia" />
            <input type="hidden" id="departamento" />
            <input type="hidden" id="ciudad" />
            <input type="hidden" id="direccion" />

                <div class="row-fluid">
                    <div class="span4">
                        <div class="input-prepend">
                            <span class="add-on">Latitud</span>
                            <input type="text" id="vLatitud" class="input-small" value="" placeholder="latitud" title="Latitud" />
                        </div>

                    </div>
                    <div class="span4">
                        <div class="input-prepend">
                            <span class="add-on">Longitud</span>
                            <input type="text" id="vLongitud" class="input-small" value="" placeholder="longitud" title="Longitud" />
                        </div>

                    </div>
                    <div class="span4">
                        <button type='button' class='btn btn-secondary btn-small' id="btn-detectar-ubicacion" title="Detectar mi ubicación"><i class="icon-user"></i></button>
                        <button type="button" class="btn btn-primary btn-small" id="btnModalGuardarMapa" data-dismiss="modal">Aceptar</button>
                        <button type="button" class="btn btn-secondary btn-small" id="btnModalCancelar" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
