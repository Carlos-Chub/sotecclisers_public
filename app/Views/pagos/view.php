<div class="container">
    <!-- TITUTLO DE LA TABLA DE CLIENTES -->
    <div class="row mt-4 justify-content-center">
        <div class="col-auto text-center pe-5 ps-5 border-bottom border-primary">
            <h5 class="text-primary">Detalle pago</h5>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col ps-5 pe-5 me-5 ms-5">
            <ul class="list-group" style="border: 0 !important; border: none !important;">
                <li class="d-flex justify-content-start align-items-center p-0 bg-transparent my-3">
                    <div class="col-auto text-center pe-5 ps-5 border-bottom border-success">
                        <h5 class="text-success" style="font-size: 0.8rem;">Cliente</h5>
                    </div>
                </li>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Nombre cliente:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $data[0]['nombre'] ?> </span>
                    </li>
                </span>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Nombre comercial:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $data[0]['nomcom'] ?> </span>
                    </li>
                </span>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Nit:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $data[0]['nit'] ?> </span>
                    </li>
                </span>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Teléfono:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $data[0]['telefono'] ?> </span>
                    </li>
                </span>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Departamento:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $data[0]['departamento'] ?> </span>
                    </li>
                </span>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Transaccion:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $data[0]['transaccion'] ?> </span>
                    </li>
                </span>
                <li class="d-flex justify-content-start align-items-center p-0 bg-transparent my-3">
                    <div class="col-auto text-center pe-5 ps-5 border-bottom border-success">
                        <h5 class="text-success" style="font-size: 0.8rem;">Datos pago</h5>
                    </div>
                </li>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Código pago:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $data[0]['id'] ?> </span>
                    </li>
                </span>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Fecha de pago:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $data[0]['fecha'] ?> </span>
                    </li>
                </span>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Numero boleta/transferencia:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $data[0]['numdoc'] ?> </span>
                    </li>
                </span>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Tipo de servicio:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $data[0]['servicio'] ?> </span>
                    </li>
                </span>
                <span class="border-bottom border-success mb-1">
                    <li class="d-flex justify-content-start align-items-center p-0 bg-transparent m-0">
                        <span class="input-group-text" style="border: none!important; border-radius: .375rem .375rem 0rem 0rem!important;"><i class="fa-solid fa-check me-3"></i><b>Monto:</b> </span>
                        <span class=" d-block ps-0"><span class="ms-2"></span><?= $data[0]['monto'] ?> </span>
                    </li>
                </span>
            </ul>
        </div>
    </div>

    <!-- botones -->
    <div class="row mt-5 justify-content-center">
        <div class="col-auto text-center">
            <a href="<?= base_url('pagos'); ?>" class="btn btn-danger"><i class="fa-solid fa-turn-down-left"></i>Regresar</a>
        </div>
    </div>
</div>