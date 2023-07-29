<div class="container">
    <div class="card mb-3">
        <div class="card-header bg-primary bg-gradient text-white">
            <b>REPORTE DE INGRESOS</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Filtro de tipos de pagos</h5>
                            <form method="POST" id="frmReporteClientes">
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="estadopago" id="estadopago1" checked value="1">
                                            <label class="form-check-label" for="estadopago1">
                                                Pagos y facturas
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="estadopago" id="estadopago2" value="2">
                                            <label class="form-check-label" for="estadopago2">
                                                Solo pagos
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="estadopago" id="estadopago3" value="3">
                                            <label class="form-check-label" for="estadopago3">
                                                Solo facturas
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- filtro de clientes -->
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"></li>
                                    <h5 class="card-title mb-4 mt-3">Filtro de clientes</h5>
                                    <div class="row">
                                        <div class="col-12 col-sm-6 col-md-6 d-flex justify-content-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="estadocli" id="estadocli" checked value="1" onclick="activar_boton_clientes(this, true)">
                                                <label class="form-check-label" for="estadocli">
                                                    Todos
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 d-flex justify-content-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="estadocli" id="estadocli" value="2" onclick="activar_boton_clientes(this, false)">
                                                <label class="form-check-label" for="estadocli">
                                                    Por cliente
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- modal para traer clientes -->
                                    <div class="row">
                                        <div class="col-12 col-sm-6 text-center mb-2">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-regular fa-user"></i></span>
                                                <div class="form-floating">
                                                    <input type="text" class="form-control text-primary" id="nombrecli" name="nombrecli" placeholder="Nombre cliente" readonly>
                                                    <label for="apellido">Nombre cliente</label>
                                                    <input type="text" id="id_cliente" name="id_cliente" hidden readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="d-grid">
                                                <button class="btn btn-primary pt-3 pb-3" type="button" id="btCliente" onclick="abrir_modal('#modal_cliente')" disabled><i class="fa-solid fa-magnifying-glass me-2"></i>Buscar cliente</button>
                                            </div>
                                        </div>
                                    </div>
                                </ul>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"></li>
                                    <h5 class="card-title mb-4 mt-3">Filtro de fecha</h5>
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-solid fa-calendar-days"></i></i></span>
                                                <div class="form-floating">
                                                    <input type="date" class="form-control text-primary" id="fechai" name="fechai" placeholder="Fecha inicio">
                                                    <label for="fechai">Fecha inicio</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text bg-dark bg-gradient text-white"><i class="fa-solid fa-calendar-days"></i></i></span>
                                                <div class="form-floating">
                                                    <input type="date" class="form-control text-primary" id="fechaf" name="fechaf" placeholder="Fecha final">
                                                    <label for="fechaf">Fecha final</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <li class="list-group-item"></li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-12 col-sm-6 mt-3 d-flex justify-content-center">
                                                <button class="btn btn-outline-success" type="button" onclick="reporte_ingresos('xlsx')"><i class="fa-solid fa-file-excel me-2"></i>Reporte en Excel</button>
                                            </div>
                                            <div class="col-12 col-sm-6 mt-3 d-flex justify-content-center">
                                                <button class="btn btn-outline-danger" type="button" onclick="reporte_ingresos('pdf')"><i class="fa-solid fa-file-pdf me-2"></i>Reporte en PDF</button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>