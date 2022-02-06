<h3>
    <a href="<?php echo $_SESSION['home'] ?>admin" title="Inicio">Inicio</a> <span>| </span>
    <a href="<?php echo $_SESSION['home'] ?>admin/personas" title="Personas">Personas</a> <span>| </span>
    <?php if ($datos->id){ ?>
        <span>Editar <?php echo $datos->persona ?></span>
    <?php } else { ?>
        <span>Nueva persona</span>
    <?php } ?>
</h3>
<div class="row">
    <?php $id = ($datos->id) ? $datos->id : "nuevo" ?>
    <form class="col m12 l6" method="POST" action="<?php echo $_SESSION['home'] ?>admin/personas/editar/<?php echo $id ?>">
        <div class="row">
            <div class="input-field col s12">
                <input id="persona" type="text" name="persona" value="<?php echo $datos->persona ?>">
                <label for="persona">Persona</label>
            </div>
            <?php $clase = ($datos->id) ? "hide" : "" ?>
            <div class="input-field col s12 <?php echo $clase ?>" id="password">
                <input id="clave" type="password" name="clave" value="">
                <label for="clave">Contraseña</label>
            </div>
            <?php $clase = ($datos->id) ? "" : "hide" ?>
            <p class="<?php echo $clase ?>">
                <label for="cambiar_clave">
                    <input id="cambiar_clave" name="cambiar_clave" type="checkbox">
                    <span>Pulsa para cambiar la clave</span>
                </label>
            </p>
        </div>
        <div class="row">
            <p>Permisos</p>
            <p>
                <label for="juegos">
                    <input id="juegos" name="juegos" type="checkbox" <?php echo ($datos->juegos == 1) ? "checked" : "" ?>>
                    <span>Juegos</span>
                </label>
            </p>
            <p>
                <label for="personas">
                    <input id="personas" name="personas" type="checkbox" <?php echo ($datos->personas == 1) ? "checked" : "" ?>>
                    <span>Personas</span>
                </label>
            </p>
            <?php $clase = ($datos->id) ? "" : "hide" ?>
            <p class="<?php echo $clase ?>">
                Último acceso: <strong><?php echo date("d/m/Y H:i", strtotime($datos->fecha_acceso)) ?></strong>
            </p>
            <div class="input-field col s12">
                <a href="<?php echo $_SESSION['home'] ?>admin/personas" title="Volver">
                    <button class="btn waves-effect waves-light" type="button">Volver
                        <i class="material-icons right">replay</i>
                    </button>
                </a>
                <button class="btn waves-effect waves-light" type="submit" name="guardar">Guardar
                    <i class="material-icons right">save</i>
                </button>
            </div>
        </div>
    </form>
</div>
