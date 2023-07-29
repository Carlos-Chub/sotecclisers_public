<div class="container">
    <!-- TITUTLO DE LA TABLA DE CLIENTES -->
    <div class="row mt-2 justify-content-center">
        <div class="col-auto text-center pe-5 ps-5 border-bottom border-primary">
            <h4 class="text-primary">EDITAR CONTRATO</h4>
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

    <?php if (session('error')) { ?>
        <div class="row mt-3">
            <div class="col">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> <?= session('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php }; ?>

    <!-- nombres y apellidos -->
    <form action="<?= base_url('inicio/actadd'); ?>" method="post" id="formcontrato">
        <div class="row mt-1 mb-3">
            <div class="col-auto text-center ms-3 pe-5 ps-5 border-bottom border-primary">
                <h5 class="text-primary-emphasis" style="font-size: 1rem;">ENCABEZADO DE FACTURA</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="nombrecli" placeholder="Nombre cliente" disabled value="<?= $data['cliente'][0]['nombre']; ?>">
                        <label for="apellido">Nombre cliente</label>
                        <input type="text" id="id_cliente" name="id_cliente" hidden readonly value="<?= $data['cliente'][0]['id_cliente']; ?>">
                        <input type="text" id="id_factura" name="id_factura" hidden readonly value="<?= $data['cliente'][0]['id_factura']; ?>">
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="nomcom" placeholder="Nombre comercial" disabled value="<?= $data['cliente'][0]['nomcom']; ?>">
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
                        <input type="text" class="form-control text-primary" id="telefono" placeholder="Teléfono" disabled value="<?= $data['cliente'][0]['telefono']; ?>">
                        <label for="direccion">Teléfono</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="departamento" placeholder="Departamento" disabled value="<?= $data['cliente'][0]['departamento']; ?>">
                        <label for="departamento">Departamento</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-solid fa-credit-card"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="transaccion" placeholder="No. Transacción" name="transaccion" disabled value="<?= $data['cliente'][0]['transaccion']; ?>">
                        <label for="transaccion">No. Transacción</label>
                    </div>
                </div>
            </div>
        </div>
        <!-- secccion de detalle de factura -->
        <div class="row mt-4 mb-2">
            <div class="col-12 col-sm-6 text-center mb-2">
                <div class="border-bottom border-primary">
                    <h5 class="text-primary-emphasis" style="font-size: 1rem;">DIGITAR UN DETALLE</h5>
                </div>
            </div>
            <div class="col-12 col-sm-3">
                <div class="d-grid">
                    <button class="btn btn-success" id="guardar" type="button" onclick="submitContrato3()"><i class="fa-solid fa-plus me-2"></i>Agregar</button>
                    <button class="btn btn-warning" id="editar" type="button" onclick="submitContrato2()"><i class="fa-solid fa-pen-to-square me-2"></i>Editar</button>
                </div>
            </div>
            <div class="col-12 col-sm-3">
                <div class="d-grid">
                    <button class="btn btn-danger" id="cancelar" type="button" onclick="limpiarinput(['fechai','fechaf','saldo','monto','abono','cuota','observaciones']); HabDes_boton(0);"><i class="fa-solid fa-ban me-2"></i>Cancelar</button>
                </div>
            </div>
        </div>
        <!-- fechainicio, fechafinal, servicio -->
        <div class="row">
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="date" class="form-control text-primary" id="fechai" placeholder="Fecha inicio" name="fechai">
                        <input type="text" id="id_detalle" name="id_detalle" readonly hidden>
                        <label for="fechai">Fecha inicio</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="date" class="form-control text-primary" id="fechaf" placeholder="Fecha final" name="fechaf">
                        <label for="fechai">Fecha final</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <select class="form-select text-primary" aria-label="Default select example" id="servicio" name="servicio">
                            <option value="0" selected>Seleccione</option>
                            <?php foreach ($data['servicios'] as $dato) { ?>
                                <option value="<?= $dato['id']; ?>"><?= $dato['nombre']; ?></option>
                            <?php }; ?>
                        </select>
                        <label for="servicio">Servicio</label>
                    </div>
                </div>
            </div>
        </div>
        <!-- monto, abono, saldo, cuota -->
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="monto" placeholder="Monto total" onchange="calcularsaldo()" name="monto">
                        <label for="monto">Monto total</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="abono" placeholder="Abono" onchange="calcularsaldo()" name="abono">
                        <label for="abono">Abono</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="saldo" placeholder="Saldo" disabled>
                        <label for="saldo">Saldo</label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                    <div class="form-floating">
                        <input type="text" class="form-control text-primary" id="cuota" placeholder="Cuota" name="cuota">
                        <label for="cuota">Cuota</label>
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
                        <textarea class="form-control text-primary" placeholder="Observaciones" id="observaciones" name="observaciones"></textarea>
                        <label for="observaciones">Observaciones</label>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row mt-1 mb-3">
        <div class="col-auto text-center ms-3 pe-5 ps-5 border-bottom border-primary">
            <h5 class="text-primary-emphasis" style="font-size: 1rem;">DETALLE DE FACTURA</h5>
        </div>
    </div>
    <!-- repnom, reptel, email -->
    <div class="table-responsive">
        <table class="display" style="width:100%!important" id="tablacontratos">
            <thead style="font-size: 0.8rem;">
                <tr>
                    <th scope="col">IdF</th>
                    <th scope="col">IdS</th>
                    <th scope="col">Accion</th>
                    <th scope="col">Servicio</th>
                    <th scope="col">F1</th>
                    <th scope="col">F2</th>
                    <th scope="col">Monto</th>
                    <th scope="col">Abono</th>
                    <th scope="col">Saldo</th>
                    <th scope="col">Cuota</th>
                    <th scope="col">Observaciones</th>

                </tr>
            </thead>
            <tbody id="tbody-tablacontratos" style="font-size: 0.8rem;">
                <?php foreach ($data['detalles'] as $dato) { ?>
                    <tr>
                        <th scope="row"><?php echo $dato['id']; ?></th>
                        <td><?php echo $dato['id_servicio']; ?></td>
                        <td>
                            <a href="<?= base_url('inicio/dser/' . $dato['id']); ?>" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                            <button class="btn btn-success btn-sm" onclick="HabDes_boton(1); 
                                modal_principal('id_detalle,fechai,fechaf,servicio,monto,abono,cuota,observaciones/A,A,A,A,A,A,A,A/'+'/fechai,fechaf,servicio,monto,abono,cuota,observaciones/#',['<?= $dato['id']; ?>','<?= $dato['fechai']; ?>','<?= $dato['fechaf']; ?>','<?= $dato['id_servicio']; ?>','<?= $dato['monto']; ?>','<?= $dato['abono']; ?>','<?= $dato['cuota']; ?>','<?= $dato['observaciones']; ?>']); calcularsaldo()"><i class="fa-solid fa-eye"></i></button>
                        </td>
                        <td><?php echo $dato['servicio']; ?></td>
                        <td><?php echo $dato['fechai']; ?></td>
                        <td><?php echo $dato['fechaf']; ?></td>
                        <td><?php echo $dato['monto']; ?></td>
                        <td><?php echo $dato['abono']; ?></td>
                        <td><?php echo ($dato['monto'] - $dato['abono']); ?></td>
                        <td><?php echo $dato['cuota']; ?></td>
                        <td><?php echo $dato['observaciones']; ?></td>
                    </tr>
                <?php }; ?>
            </tbody>
        </table>
    </div>

    <!-- botones -->
    <div class="row mt-3 mb-3">
        <div class="col-12 d-flex justify-content-center">
            <div class="">
                <a href="<?= base_url('inicio'); ?>" class="btn btn-danger"><i class="fa-solid fa-ban me-2"></i>Cancelar</a>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            HabDes_boton(0);
            var tabla = $('#tablacontratos').on('search.dt')
                .DataTable({
                    "lengthMenu": [
                        [5, 10, 15, -1],
                        ['5 filas', '10 filas', '15 filas', 'Mostrar todos']
                    ],
                    "language": {
                        "lengthMenu": "Mostrar _MENU_ registros",
                        "zeroRecords": "No se encontraron registros",
                        "info": " ",
                        "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "infoFiltered": "(filtrado de un total de: _MAX_ registros)",
                        "sSearch": "Buscar: ",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Ultimo",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "sProcessing": "Procesando...",
                    },
                });
            // var column = tabla.column(0);
            // column.visible(false);
            // document.getElementById('abono').disabled = true;

            // function habilitar_deshabilitar(hab, des) {
            //     var i = 0;
            //     while (i < hab.length) {
            //         document.getElementById(hab[i]).disabled = false;
            //         i++;
            //     }
            //     var i = 0;
            //     while (i < des.length) {
            //         document.getElementById(des[i]).disabled = true;
            //         i++;
            //     }
            // }
        });
    </script>