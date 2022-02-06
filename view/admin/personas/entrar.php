<?php echo "hola"?>
<h3>Acceso</h3>
<div class="row">
    <form class="col m12 l6" method="POST">
        <div class="row">
            <div class="input-field col s12">
                <input id="persona" type="text" name="persona" value="">
                <label for="persona">Persona</label>
            </div>
            <div class="input-field col s12">
                <input id="clave" type="password" name="clave" value="">
                <label for="clave">Contraseña</label>
            </div>
            <div class="input-field col s12">
                <button class="btn waves-effect waves-light" type="submit" name="acceder">Acceder
                    <i class="material-icons right">person</i>
                </button>
            </div>
        </div>
    </form>
</div>