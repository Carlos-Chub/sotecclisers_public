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
    <form action="<?= base_url('pagos/add_create'); ?>" method="post" id="formpago">
        <div class="row mt-4 mb-2">
            <div class="col-12 col-sm-6 text-center mb-2">
                <div class="border-bottom border-primary">
                    <h5 class="text-primary-emphasis" style="font-size: 1rem;">CLIENTE Y FACTURA</h5>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="d-grid">
                    <button class="btn btn-primary" type="button" onclick="abrir_modal('#modal_cliente')"><i class="fa-solid fa-magnifying-glass me-2"></i>Buscar cliente</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="nombrecli" placeholder="Nombre cliente" disabled>
                        <label for="apellido">Nombre cliente</label>
                        <input type="text" id="id_cliente" name="id_cliente" hidden readonly>
                        <input type="text" id="id_factura" name="id_factura" hidden readonly>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="nomcom" placeholder="Nombre comercial" disabled>
                        <label for="apellido">Nombre comercial</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="nit" placeholder="Nit" disabled>
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
                        <input type="text" class="form-control text-primary" id="telefono" placeholder="Teléfono" disabled>
                        <label for="direccion">Teléfono</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="departamento" placeholder="Departamento" disabled>
                        <label for="departamento">Departamento</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-solid fa-credit-card"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="transaccion" placeholder="No. Transacción" name="transaccion" disabled value="">
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
                        <input type="date" class="form-control text-primary" id="fechapago" placeholder="Fecha pago" name="fechapago">
                        <!-- <input type="text" id="id_detalle" name="id_detalle" readonly hidden> -->
                        <label for="fechai">Fecha pago</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-solid fa-credit-card"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="documento" placeholder="No. Documento" name="documento">
                        <label for="fechai">No. Documento</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-solid fa-user-gear"></i></span>
                    <div class="form-floating">
                        <select class="form-select text-primary" aria-label="Default select example" id="servicio" name="servicio">
                            <option value="0" selected>Seleccione</option>
                        </select>
                        <label for="servicio">Servicio</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-solid fa-money-bill"></i></span>
                    <div class="form-floating">
                        <input type="number" class="form-control text-primary" id="monto" placeholder="Monto" name="monto">
                        <label for="fechai">Monto</label>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- botones -->
    <div class="row mt-5">
        <div class="col-12 col-sm-6 d-flex justify-content-end">
            <button type="button" class="btn btn-primary" onclick="guardarpago()"><i class="fa-solid fa-floppy-disk me-2"></i>Guardar</button>
        </div>
        <div class="col-12 col-sm-6">
            <a href="<?= base_url('pagos'); ?>" class="btn btn-danger"><i class="fa-solid fa-ban me-2"></i>Cancelar</a>
        </div>
    </div>

</div>
<script>
    // function buscar_servicios() {
    // console.log('buscando servicio con ajax');
    // id_detalle=$('#id_factura').val();
    // // console.log("/pagos/prueba");
    // // return;
    // $.ajax({
    //     url: "/pagos/prueba",
    //     type: 'post',
    //     data: { 'id_detalle': 'carlos' },
    //     dataType: 'json',
    //     success: function (response) {
    //         console.log(response);
    //         // var len = response.length;

    //         // $("#sel_user").empty();
    //         // for (var i = 0; i < len; i++) {
    //         //     var id = response[i]['id'];
    //         //     var name = response[i]['name'];

    //         //     $("#sel_user").append("<option value='" + id + "'>" + name + "</option>");

    //         // }
    //     }
    // }); 
    // }
</script>