<div class="container">
    <!-- TITUTLO DE LA TABLA DE CLIENTES -->
    <div class="row mt-2 justify-content-center">
        <div class="col-auto text-center pe-5 ps-5 border-bottom border-primary">
            <h4 class="text-primary">Nuevo servicio</h4>
        </div>
    </div>
    <!-- INPUT PARA REGISTRO -->
    <?php if (session('mensaje')) { ?>
        <div class="row mt-3">
            <div class="col">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> <?= session('mensaje'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php }; ?>
    <!-- nombres y apellidos -->
    <form action="<?= base_url('servicios/add_create'); ?>" method="post">
        <div class="row mt-4 mb-2">
            <div class="col-auto text-center ms-3 pe-5 ps-5 border-bottom border-primary">
                <h5 class="text-primary-emphasis" style="font-size: 1rem;">PRINCIPAL</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="nombre" placeholder="Nombre" name="nombre" >
                        <label for="nombre">Nombre</label>
                    </div>
                </div>
            </div>
        </div>
        <!-- descripcion -->
        <div class="row">
            <div class="col-12">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="descripcion" placeholder="Descripción" name="descripcion">
                        <label for="descripcion">Descripción</label>
                    </div>
                </div>
            </div>
        </div>
        <!-- botones -->
        <div class="row mt-5">
            <div class="col-12 col-sm-6 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-2"></i>Guardar</button>
            </div>
            <div class="col-12 col-sm-6">
                <a href="<?= base_url('servicios'); ?>" class="btn btn-danger"><i class="fa-solid fa-ban me-2"></i>Cancelar</a>
            </div>
        </div>
    </form>
</div>