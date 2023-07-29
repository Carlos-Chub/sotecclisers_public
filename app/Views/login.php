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
	<!-- libreria sweet alert -->
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<link rel="stylesheet" href="<?= base_url('css/styles.css'); ?>">
</head>

<body>
	<div class="container" style="display: flex; height: 100vh; max-width: none !important;">
		<div class="row">
			<div class="col-12 col-md-6 d-flex align-items-center" style="padding-top: 3rem; padding-bottom: 3rem; 			padding-left: 4rem; padding-right: 4rem !important;">
				<div class="row">
					<?php if (session('mensaje')) { ?>
						<div class="row mt-3">
							<div class="col">
								<div class="alert alert-warning alert-dismissible fade show" role="alert">
									<strong>Ups!</strong> <?= session('mensaje'); ?>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
							</div>
						</div>
					<?php }; ?>
					<!-- titulo de login -->
					<div class="row">
						<h3 class="fs-2 text-center text-primary"><b>Iniciar Sesión</b></h3>
					</div>
					<!-- imagen de microsystem -->
					<div class="row d-flex justify-content-center">
						<div class="col-auto">
							<img src="<?= base_url('img/fondologo.png'); ?>" alt="logo" width="40%" class="mx-auto d-block">
						</div>
					</div>
					<div class="row mt-2">
						<h5 style="font-size: 1rem;" class="text-center text-secondary">Por favor inicia sesión con tu cuenta</h5>
					</div>
					<form method="POST" action="<?= base_url('/login'); ?>">
						<div class="row mt-2">
							<div class="col">
								<div class="input-group mb-3">
									<span class="input-group-text bg-primary bg-gradient text-white"><i class="fa-solid fa-user"></i></span>
									<div class="form-floating">
										<input type="text" class="form-control" id="usuario" placeholder="Usuario" name="usuario">
										<label for="nombre">Usuario</label>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="input-group mb-3">
									<span class="input-group-text bg-primary bg-gradient text-white"><i class="fa-solid fa-lock"></i></span>
									<div class="form-floating">
										<input type="password" class="form-control border-end-0" id="password" placeholder="Contraseña" name="password">
										<label for="password">Contraseña</label>
									</div>
									<span class="input-group-text bg-transparent border-start-0 text-primary"><i class="fa-regular fa-eye" id="togglePassword"></i></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col d-flex justify-content-center mt-4">
								<button type="submit" id="btnEnviar" class="col btn btn-primary"><b>LOG IN</b></button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="col-12 col-md-6 d-flex align-items-center" style="background-color: #f4f3ee !important; padding-top: 3rem; padding-bottom: 3rem; padding-left: 4.3rem; padding-right: 4.3rem !important;">
				<div>
					<div class="row d-flex justify-content-center">
						<div class="col-auto">
							<img src="<?= base_url('img/logosoctecpro.png'); ?>" alt="logo" width="60%" class="mx-auto d-block">
						</div>
					</div>
					<div class="row mt-5">

						<h5 style="font-size: 1.3rem;" class="text-center text-success">Soluciones Tecnologicas Profesionales</h5>
					</div>
					<div class="row mt-3">
						<h5 style="font-size: 3rem; font-weight: bold; color:#495057 !important;" class="text-center">S O T E C C L I S E R S</h5>
					</div>
					<!-- <div class="row">
                            <button class="btn btn-primary" id="eliminarsesion">Eliminar sesion</button>
                        </div> -->
				</div>
			</div>
		</div>
	</div>
	<!-- efecto de loader -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
	</script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<script type="text/javascript" src="<?= base_url('js/scritp.js'); ?>"></script>
</body>

</html>