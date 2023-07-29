<div class="container">
    <!-- CLIENTES -->
    <div class="row">
        <div class="col">
            <h3 class="text-center">PAGOS DE CLIENTES DE SOTECPRO</h3>
        </div>
    </div>
    <!-- BOTON AGREGAR -->
    <div class="row mt-2">
        <div class="col text-center">
            <a href="<?= base_url('pagos/add'); ?>" class="btn btn-success"><i class="fa-solid fa-user-plus me-2"></i>Agregar</a>
        </div>
    </div>
    <!-- TITUTLO DE LA TABLA DE CLIENTES -->
    <div class="row mt-4 justify-content-center">
        <div class="col-auto text-center pe-5 ps-5 border-bottom border-primary">
            <h5 class="text-primary">Listado de pagos</h5>
        </div>
    </div>

    <?php if (session('mensaje')) { ?>
        <div class="row">
            <div class="col">
                <div class="row mt-3">
                    <div class="col">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>En hora buena!</strong> <?= session('mensaje'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }; ?>

    <?php if (session('mensaje_error')) { ?>
        <div class="row">
            <div class="col">
                <div class="row mt-3">
                    <div class="col">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>En hora buena!</strong> <?= session('mensaje_error'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }; ?>

    <?php
    // echo "<pre>";
    // print_r($datos['select']);
    // echo "</pre>";
    ?>

    <div class="row mt-2">
        <div class="col">
            <div class="table-responsive">
                <table class="table" id="tablapagos">
                    <thead style="font-size: 0.8rem;">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">No. Transaccion</th>
                            <th scope="col">Documento</th>
                            <th scope="col">Monto</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Nombre comercial</th>
                            <th scope="col">Servicio</th>
                            <th scope="col">Departamento</th>
                            <th scope="col">Editar/Eliminar</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.8rem;">
                        <?php foreach ($datos as $dato) { ?>
                            <tr>
                                <th scope="row"><?php echo $dato['id']; ?></th>
                                <td><?php echo $dato['transaccion']; ?></td>
                                <td><?php echo $dato['numdoc']; ?></td>
                                <td><?php echo $dato['monto']; ?></td>
                                <td><?php echo $dato['fecha']; ?></td>
                                <td><?php echo $dato['nombre']; ?></td>
                                <td><?php echo $dato['nomcom']; ?></td>
                                <td><?php echo $dato['servicio']; ?></td>
                                <td><?php echo $dato['departamento']; ?></td>
                                <td>
                                    <a href="<?= base_url('pagos/edit/' . $dato['id']); ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="<?= base_url('pagos/delete/' . $dato['id']); ?>" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                    <a href="<?= base_url('pagos/view/' . $dato['id']); ?>" class="btn btn-success btn-sm"><i class="fa-solid fa-eye"></i></a>
                                    <button onclick="reportePago('<?= $dato['id']; ?>')" class="btn btn-secondary btn-sm"><i class="fa-solid fa-file-pdf"></i></button>
                                </td>
                            </tr>
                        <?php }; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#tablapagos').on('search.dt')
                .DataTable({
                    "lengthMenu": [
                        [10, 15, -1],
                        ['10 filas', '15 filas', 'Mostrar todos']
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
        });
    </script>
</div>