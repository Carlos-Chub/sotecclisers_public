<div class="container">
    <!-- TITUTLO DE LA TABLA DE CLIENTES -->
    <div class="row mt-2 justify-content-center">
        <div class="col-auto text-center pe-5 ps-5 border-bottom border-primary">
            <h4 class="text-primary">Nuevo pago</h4>
        </div>
    </div>
    <!-- INPUT PARA REGISTRO -->
    <?php if (session('mensaje')) { ?>
        <div class="row mt-3">
            <div class="col">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>En hora buena!</strong> <?= session('mensaje'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php }; ?>

    <!-- INPUT PARA REGISTRO -->
    <?php if (session('mensaje_error')) { ?>
        <div class="row mt-3">
            <div class="col">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> <?= session('mensaje_error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php }; ?>

    <!-- nombres y apellidos -->
    <form action="<?= base_url('pagos/actualizar'); ?>" method="post" id="formpago">
        <div class="row mt-4 mb-2">
            <div class="col-12 col-sm-6 text-center mb-2">
                <div class="border-bottom border-primary">
                    <h5 class="text-primary-emphasis" style="font-size: 1rem;">DETALLE DE CLIENTE Y FACTURA</h5>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="nombrecli" placeholder="Nombre cliente" disabled value="<?= $data[0]['nombre']?>">
                        <label for="nombrecli">Nombre cliente</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="nomcom" placeholder="Nombre comercial" disabled value="<?= $data[0]['nomcom']?>">
                        <label for="apellido">Nombre comercial</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="nit" placeholder="Nit" disabled value="<?= $data[0]['nit']?>">
                        <label for="apellido">Nit</label>
                    </div>
                </div>
            </div>
        </div>
        <!-- nit, direccion, telefono, departamento -->
        <div class="row">
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="telefono" placeholder="Teléfono" disabled value="<?= $data[0]['telefono']?>">
                        <label for="direccion">Teléfono</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="departamento" placeholder="Departamento" disabled value="<?= $data[0]['departamento']?>">
                        <label for="departamento">Departamento</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-solid fa-credit-card"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="transaccion" placeholder="No. Transacción" name="transaccion" disabled value="<?= $data[0]['transaccion']?>">
                        <label for="transaccion">No. Transacción</label>
                    </div>
                </div>
            </div>
        </div>
        <!-- seccion de pago -->
        <div class="row mt-4 mb-2">
            <div class="col-12 col-sm-6 text-center mb-2">
                <div class="border-bottom border-primary">
                    <h5 class="text-primary-emphasis" style="font-size: 1rem;">DIGITAR UN DETALLE</h5>
                </div>
            </div>
        </div>
        <!-- fechainicio, fechafinal, servicio -->
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-solid fa-calendar-days"></i></span>
                    <div class="form-floating">
                        <input type="date" class="form-control text-primary" id="fechapago" placeholder="Fecha pago" name="fechapago" value="<?= $data[0]['fecha']?>">
                        <input type="text" id="id_detalle" name="id_detalle" readonly hidden value="<?= $data[0]['id_detalle']?>">
                        <input type="text" id="id_pago" name="id_pago" readonly hidden value="<?= $data[0]['id']?>">
                        <label for="fechapago">Fecha pago</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-solid fa-credit-card"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="documento" placeholder="No. Documento" name="documento" value="<?= $data[0]['numdoc']?>">
                        <label for="fechai">No. Documento</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-solid fa-credit-card"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="servicio" placeholder="Servicio" name="servicio" disabled value="<?= $data[0]['servicio']?>">
                        <label for="servicio">Servicio</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-solid fa-money-bill"></i></span>
                    <div class="form-floating">
                        <input type="number" class="form-control text-primary" id="monto" placeholder="Monto" name="monto" value="<?= $data[0]['monto']?>">
                        <label for="fechai">Monto</label>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- botones -->
    <div class="row mt-5">
        <div class="col-12 col-sm-6 d-flex justify-content-end">
            <button type="button" class="btn btn-primary" onclick="editarpago()"><i class="fa-solid fa-floppy-disk me-2"></i>Guardar</button>
        </div>
        <div class="col-12 col-sm-6">
            <a href="<?= base_url('pagos'); ?>" class="btn btn-danger"><i class="fa-solid fa-ban me-2"></i>Cancelar</a>
        </div>
    </div>

</div>