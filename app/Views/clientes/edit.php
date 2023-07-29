<div class="container">
    <!-- TITUTLO DE LA TABLA DE CLIENTES -->
    <div class="row mt-2 justify-content-center">
        <div class="col-auto text-center pe-5 ps-5 border-bottom border-primary">
            <h4 class="text-primary">Editar cliente</h4>
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
    <?php
    // echo '<pre>';    
    // echo print_r($data);
    // echo '</pre>';

    ?>   
    <!-- nombres y apellidos -->
    <form action="<?= base_url('clientes/actualizar'); ?>" method="post">
        <div class="row mt-4 mb-2">
            <div class="col-auto text-center ms-3 pe-5 ps-5 border-bottom border-primary">
                <h5 class="text-primary-emphasis" style="font-size: 1rem;">PRINCIPAL</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="nombre" placeholder="Nombre cliente" name="nombre" value="<?= $data['cliente'][0]['nombre']; ?>">
                        <input type="text" id="id" name="id" hidden value="<?= $data['cliente'][0]['id']; ?>">
                        <label for="nombre">Nombre cliente</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="nomcom" placeholder="Nombre comercial" name="nomcom" value="<?= $data['cliente'][0]['nomcom']; ?>">
                        <label for="apellido">Nombre comercial</label>
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
                        <input type="text" class="form-control text-primary" id="nit" placeholder="Nit cliente" name="nit" value="<?= $data['cliente'][0]['nit']; ?>">
                        <label for="dpi">Nit cliente</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="telefono" placeholder="Teléfono" name="telefono" value="<?= $data['cliente'][0]['telefono']; ?>">
                        <label for="direccion">Teléfono</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <select class="form-select" aria-label="Default select example" id="departamento" name="departamento">
                            <option value="0" selected>Seleccione un departamento</option>
                            <?php
                                $selected = "";
                                foreach ($data['departamento'] as $dato) {
                                    ($dato['id'] == $data['cliente'][0]['id_departamento']) ? $selected = "selected" : $selected = "";
                            ?>
                                <option value="<?= $dato['id']; ?>" <?= $selected ?>><?= $dato['nombre']; ?></option>
                            <?php }; ?>
                        </select>
                        <label for="telefono">Departamento</label>
                    </div>
                </div>
            </div>
        </div>
        <!-- direcion y fecha -->
        <div class="row">
            <div class="col-12 col-sm-8">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="direccion" placeholder="Dirección" name="direccion" value="<?= $data['cliente'][0]['direccion']; ?>">
                        <label for="direccion">Dirección</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="date" class="form-control text-primary" id="fecha" placeholder="Fecha" name="fecha" value="<?= $data['cliente'][0]['fecha']; ?>">
                        <label for="fecha">Fecha</label>
                    </div>
                </div>
            </div>
        </div>
        <!-- observaciones -->
        <div class="row">
            <div class="col-12">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <textarea class="form-control text-primary" placeholder="Observaciones" id="observaciones" name="observaciones"><?= $data['cliente'][0]['observaciones']; ?></textarea>
                        <label for="observaciones">Observaciones</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-1 mb-2">
            <div class="col-auto text-center ms-3 pe-5 ps-5 border-bottom border-primary">
                <h5 class="text-primary-emphasis" style="font-size: 1rem;">DATOS REPRESENTANTE</h5>
            </div>
        </div>
        <!-- repnom, reptel, email -->
        <div class="row">
            <div class="col-12">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="repnom" placeholder="Nombre representante" name="repnom" value="<?= $data['cliente'][0]['repnom']; ?>">
                        <label for="repnom">Nombre representante</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="reptel" placeholder="Teléfono representante" name="reptel" value="<?= $data['cliente'][0]['reptel']; ?>">
                        <label for="reptel">Teléfono representante</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="email" class="form-control text-primary" id="email" placeholder="Email representante" name="email" value="<?= $data['cliente'][0]['email']; ?>">
                        <label for="email">Email representante</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-1 mb-2">
            <div class="col-auto text-center ms-3 pe-5 ps-5 border-bottom border-primary">
                <h5 class="text-primary-emphasis" style="font-size: 1rem;">DATOS CONTADOR</h5>
            </div>
        </div>
        <!-- nombre de contador y telefono -->
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="contnom" placeholder="Nombre contador" name="contnom" value="<?= $data['cliente'][0]['contnom']; ?>">
                        <label for="contnom">Nombre contador</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="conttel" placeholder="Teléfono contador" name="conttel" value="<?= $data['cliente'][0]['conttel']; ?>">
                        <label for="conttel">Teléfono contador</label>
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
                <a href="<?= base_url('clientes'); ?>" class="btn btn-danger"><i class="fa-solid fa-ban me-2"></i>Cancelar</a>
            </div>
        </div>
    </form>
    
</div>