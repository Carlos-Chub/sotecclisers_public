<!-- ---------------------------------TERMINA EL MODAL  -->
<div class="modal" id="modal_cliente">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Búsqueda de clientes</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tabla_m_cliente" class="table table-striped table-hover" style="width: 100% !important;">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Transacción</th>
                                <th scope="col">Nombre cliente</th>
                                <th scope="col">Nit</th>
                                <th scope="col">Departamento</th>
                                <th scope="col">Acción</th>
                            </tr>
                        </thead>
                        <!-- `id_hidden,cod_cuenta,descripcion,tipo/A,A,A,A//#/cod_cuenta` -->
                        <tbody style="font-size: 0.8rem;">
                        <?php foreach ($datos as $dato) { ?>
                            <tr>
                                <th scope="row"><?php echo $dato['id_factura']; ?></th>
                                <td><?php echo $dato['transaccion']; ?></td>
                                <td><?php echo $dato['nombre']; ?></td>
                                <td><?php echo $dato['nit']; ?></td>
                                <td><?php echo $dato['departamento']; ?></td>
                                <td>
                                    <button class="btn btn-success btn-sm" onclick="seleccionar_cliente2('id_factura,nombrecli,nomcom,nit,telefono,departamento,transaccion/A,A,A,A,A,A,A'+'/'+'/#/#',['<?= $dato['id_factura']; ?>','<?= $dato['nombre']; ?>','<?= $dato['nomcom']; ?>','<?= $dato['nit']; ?>','<?= $dato['telefono']; ?>','<?= $dato['departamento']; ?>','<?= $dato['transaccion']; ?>'],'<?= base_url(); ?>')"><i class="fa-solid fa-check me-2"></i>Aceptar</button>
                                </td>
                            </tr>
                        <?php }; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="" onclick="cerrar_modal('#modal_cliente')">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- ---------------------------------TERMINA EL MODAL  -->

<script>
    // para cuentas de ahorro
    $(document).ready(function() {
        var table = $('#tabla_m_cliente').on('search.dt')
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
    });
</script>