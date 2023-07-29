<div class="container">
    <!-- TITUTLO DE LA TABLA DE CLIENTES -->
    <div class="row mt-4 justify-content-center">
        <div class="col-auto text-center pe-5 ps-5 border-bottom border-primary">
            <h5 class="text-primary">Detalle factura</h5>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col ps-5 pe-5 me-5 ms-5">
            <ul class="list-group" style="border: 0 !important; border: none !important;">
                <li class="d-flex justify-content-start align-items-center p-0 bg-transparent my-3">
                    <div class="col-auto text-center pe-5 ps-5 border-bottom border-success">
                        <h3 class="text-success" style="font-size: 0.8rem;">ENCABEZADO DE FACTURA</h3>
                    </div>
                </li>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Código:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $datos['cliente'][0]['id_cliente'] ?> </span>
                    </li>
                </span>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Nombre:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $datos['cliente'][0]['nombre'] ?> </span>
                    </li>
                </span>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Nombre comercial:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $datos['cliente'][0]['nomcom'] ?> </span>
                    </li>
                </span>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Teléfono:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $datos['cliente'][0]['telefono'] ?> </span>
                    </li>
                </span>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Departamento:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $datos['cliente'][0]['departamento'] ?> </span>
                    </li>
                </span>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Código factura:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $datos['cliente'][0]['id_factura'] ?> </span>
                    </li>
                </span>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Transacción:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $datos['cliente'][0]['transaccion'] ?> </span>
                    </li>
                </span>
                <li class="d-flex justify-content-start align-items-center p-0 bg-transparent my-3">
                    <div class="col-auto text-center pe-5 ps-5 border-bottom border-success">
                        <h3 class="text-success" style="font-size: 0.8rem;">DETALLE DE FACTURA</h3>
                    </div>
                </li>
                <?php
                $numservicio = 1;
                foreach ($datos['detalles'] as $dato) {
                    //titulo de servicio
                    echo '<li class="d-flex justify-content-start align-items-center p-0 bg-transparent my-3 ps-5">
                            <div class="col-auto text-center pe-5 ps-5 border-bottom border-primary">
                            <h5 class="text-primary" style="font-size: 0.8rem;"><b> SERVICIO O PRODUCTO No. ' . $numservicio . '</b></h5>
                            </div>
                          </li>';
                    //nombre servicio
                    echo '<span class="border-bottom border-success mb-1 ms-5">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-asterisk me-3"></i><b>Nombre servicio:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span>' . $dato['servicio'] . '</span>
                    </li>
                    </span>';
                    //fecha inicio
                    echo '<span class="border-bottom border-success mb-1 ms-5">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-asterisk me-3"></i><b>Fecha Inicio:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span>' . $dato['fechai'] . '</span>
                    </li>
                    </span>';
                    //fecha final
                    echo '<span class="border-bottom border-success mb-1 ms-5">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-asterisk me-3"></i><b>Fecha Final:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span>' . $dato['fechaf'] . '</span>
                    </li>
                    </span>';
                    //monto
                    echo '<span class="border-bottom border-success mb-1 ms-5">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-asterisk me-3"></i><b>Monto:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span>' . $dato['monto'] . '</span>
                    </li>
                    </span>';
                    //abono
                    echo '<span class="border-bottom border-success mb-1 ms-5">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-asterisk me-3"></i><b>Abono:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span>' . $dato['abono'] . '</span>
                    </li>
                    </span>';
                    //saldo
                    echo '<span class="border-bottom border-success mb-1 ms-5">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-asterisk me-3"></i><b>Saldo:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span>' . (floatval($dato['monto']) - floatval($dato['abono']) - floatval($dato['abnenpagos'])) . '</span>
                    </li>
                    </span>';
                    //cuota
                    echo '<span class="border-bottom border-success mb-1 ms-5">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-asterisk me-3"></i><b>Cuota:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span>' . $dato['cuota'] . '</span>
                    </li>
                    </span>';
                    //observaciones
                    echo '<span class="border-bottom border-success mb-1 ms-5">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-asterisk me-3"></i><b>Observaciones:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span>' . $dato['observaciones'] . '</span>
                    </li>
                    </span>';
                    //estado
                    if ($dato['estadod'] == 1) {
                        $textestado = "Activo";
                    } else {
                        $textestado = "Inactivo";
                    }

                    echo '<span class="border-bottom border-success mb-1 ms-5">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-asterisk me-3"></i><b>Estado:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span>' . $textestado . '</span>
                    </li>
                    </span>';
                    $numservicio++;
                }
                ?>
            </ul>
        </div>
    </div>

    <!-- botones -->
    <div class="row mt-5 justify-content-center">
        <div class="col-auto text-center">
            <a href="<?= base_url('inicio'); ?>" class="btn btn-danger"><i class="fa-solid fa-turn-down-left"></i>Regresar</a>
        </div>
    </div>
</div>