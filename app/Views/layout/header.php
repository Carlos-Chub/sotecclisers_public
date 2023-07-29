<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOTECCLISERS</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('img/fav-sotec.ico'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!-- FONT AWESOME -->
    <script src="https://kit.fontawesome.com/6acb25b06f.js" crossorigin="anonymous"></script>
    <!-- DATATABLES -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- <link href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.css" />
    <link href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.css" /> -->
    <!-- css -->
    <!-- libreria sweet alert -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="<?= base_url('css/styles.css'); ?>">

    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css"/> -->
    <!-- Javascript -->
    <!-- <script type="text/javascript" src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script> -->
    <!-- <script type="text/javascript" src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap5.min.js"></script> -->



</head>

<body style="background-color: #1D293F;">
    <div class="text-center loader" id="loader_div" style="display:none;">
        <div class="spinner-border gira" role="status"></div>
        <strong style="font-size: 50px;">Cargando...</strong>
    </div>
    <div class="fondo" style="background-color: #1D293F;">

    <?php $uri = new \CodeIgniter\HTTP\URI(current_url()); ?>

        <!-- Aca debe de ir la seccion del header -->
        <div class="container" style="width: 100% !important;">
            <header class="d-flex flex-wrap justify-content-center py-3">
                <a href="<?= base_url('inicio'); ?>" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                    <div class="me-3">
                        <img src="
                        <?= base_url('img/logosoctecpro.png'); ?>
                        " alt="Logo" width="65" height="100%" class="img-fluid">
                    </div>
                    <span class="fs-4 text-primary"><b>SOTECCLISERS</b></span>
                </a>

                <ul class="nav nav-pills">
                    <li class="nav-item"><a href="<?= base_url('inicio') ?>" class="nav-link text-white <?= ($uri->getSegment(3)=="inicio") ? "active" : "" ?>" aria-current="page" onclick="">Inicio</a></li>
                    <li class="nav-item"><a href="<?= base_url('clientes') ?>" class="nav-link text-white <?= ($uri->getSegment(3)=="clientes") ? "active" : "" ?>">Clientes</a></li>
                    <li class="nav-item"><a href="<?= base_url('servicios') ?>" class="nav-link text-white <?= ($uri->getSegment(3)=="servicios") ? "active" : "" ?>">Servicios</a></li>
                    <li class="nav-item"><a href="<?= base_url('pagos') ?>" class="nav-link text-white <?= ($uri->getSegment(3)=="pagos") ? "active" : "" ?>">Pagos</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white <?= ($uri->getSegment(3)=="reportes") ? "active" : "" ?>" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Reportes
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('reportes') ?>">Reporte de clientes, servicios y pagos</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('reportes/ingresos') ?>">Reporte de ingresos</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><span class="nav-link"><i class="fa-regular fa-user me-1"></i><?= session('user') ?></span></li>
                    <li class="nav-item"><a href="<?= base_url('/salir') ?>" class="nav-link text-white" title="Cerrar sesión"><i class="fa-solid fa-right-from-bracket"></i></a></li>
                </ul>
            </header>
        </div>
    </div>
    <div class="fondo py-3" style="background-color: #e5e5f7;">