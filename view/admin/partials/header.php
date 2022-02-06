<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Panel de administración</title>

    <!--CSS-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['public'] ?>css/admin.css">

</head>

<body>
<nav>
    <div class="nav-wrapper">
        <!--Logo-->
        <a href="<?php echo $_SESSION['home'] ?>admin" class="brand-logo" title="Inicio">
            <img src="<?php echo $_SESSION['public'] ?>img/logo1.png" alt="Logo Harry Potter"><!--Logo Harry Potter-->
        </a>

        <?php if (isset($_SESSION['persona'])){ ?>

            <!--Botón menú móviles-->
            <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>

            <!--Menú de navegación-->
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li>
                    <a href="<?php echo $_SESSION['home'] ?>admin" title="Inicio">Inicio</a>
                </li>
                <?php if ($_SESSION['juegos'] == 1){ ?>
                    <li>
                        <a href="<?php echo $_SESSION['home'] ?>admin/juegos" title="Juegos">Juegos</a>
                    </li>
                <?php } ?>
                <?php if ($_SESSION['personas'] == 1){ ?>
                    <li>
                        <a href="<?php echo $_SESSION['home'] ?>admin/personas" title="Usuarios">Personas</a>
                    </li>
                <?php } ?>
                <li>
                    <a href="<?php echo $_SESSION['home'] ?>admin/salir" title="Salir">Salir</a>
                </li>
            </ul>

        <?php } ?>

    </div>
</nav>

<?php if (isset($_SESSION['persona'])){ ?>

    <!--Menú de navegación móvil-->
    <ul class="sidenav" id="mobile-demo">
        <li>
            <a href="<?php echo $_SESSION['home'] ?>admin" title="Inicio">Inicio</a>
        </li>
        <?php if ($_SESSION['juegos'] == 1){ ?>
            <li>
                <a href="<?php echo $_SESSION['home'] ?>admin/juegos" title="Juegos">Juegos</a>
            </li>
        <?php } ?>
        <?php if ($_SESSION['personas'] == 1){ ?>
            <li>
                <a href="<?php echo $_SESSION['home'] ?>admin/personas" title="Personas">Personas</a>
            </li>
        <?php } ?>
        <li>
            <a href="<?php echo $_SESSION['home'] ?>admin/salir" title="Salir">Salir</a>
        </li>
    </ul>

<?php } ?>

<!-- Si existen mensajes  -->
<?php if (isset($_SESSION["mensaje"])) { ?>

    <!-- Los muestro ocultos -->
    <input type="hidden" name="tipo-mensaje" value="<?php echo $_SESSION["mensaje"]['tipo'] ?>">
    <input type="hidden" name="texto-mensaje" value="<?php echo $_SESSION["mensaje"]['texto'] ?>">

    <!-- Borro mensajes -->
    <?php unset($_SESSION["mensaje"]) ?>

<?php } ?>

<main>

    <header>
        <h1>Panel de administración</h1>

        <?php if (isset($_SESSION['persona'])){ ?>

            <h2>
                Persona: <strong><?php echo $_SESSION['persona'] ?></strong>
            </h2>

        <?php } else { ?>

            <h2>Bienvenido, introduce la persona y contraseña.</h2>

        <?php } ?>
    </header>

    <section class="container-fluid">