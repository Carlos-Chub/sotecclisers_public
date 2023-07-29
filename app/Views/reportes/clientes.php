<div class="container">
    <div class="card mb-3">
        <div class="card-header bg-primary bg-gradient text-white">
            <b>REPORTE DE CLIENTES</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Filtro de estados</h5>
                            <form method="POST" id="frmReporteClientes">
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="estadocli" id="estadocli" checked value="1">
                                            <label class="form-check-label" for="estadocli">
                                                Todos
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="estadocli" id="estadocli" value="2">
                                            <label class="form-check-label" for="estadocli">
                                                Activos
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="estadocli" id="estadocli" value="3">
                                            <label class="form-check-label" for="estadocli">
                                                Inactivos
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"></li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col mt-3 d-flex justify-content-center">
                                                <button class="btn btn-outline-success" type="submit"><i class="fa-solid fa-file-excel me-2"></i>Reporte en excel</button>
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
    <div class="card mb-3">
        <div class="card-header bg-primary bg-gradient text-white">
            <b>REPORTE DE SERVICIOS</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Filtro de estados</h5>
                            <form method="POST" id="frmReporteServicios">
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="reporteser" id="reporteser" value="1" checked>
                                            <label class="form-check-label" for="reporteser">
                                                Todos
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="reporteser" value="2" id="reporteser">
                                            <label class="form-check-label" for="reporteser">
                                                Activos
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="reporteser" value="3" id="reporteser">
                                            <label class="form-check-label" for="reporteser">
                                                Inactivos
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"></li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col mt-3 d-flex justify-content-center">
                                                <button class="btn btn-outline-success" type="submit"><i class="fa-solid fa-file-excel me-2"></i>Reporte en excel</button>
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
    <div class="card">
        <div class="card-header bg-primary bg-gradient text-white">
            <b>REPORTE DE PAGOS</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Filtro de estados</h5>
                            <form method="POST" id="frmReportePagos">
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="reportepago" id="reportepago" value="1" checked>
                                            <label class="form-check-label" for="reportepago">
                                                Todos
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="reportepago" value="2" id="reportepago">
                                            <label class="form-check-label" for="reportepago">
                                                Activos
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 d-flex justify-content-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="reportepago" value="3" id="reportepago">
                                            <label class="form-check-label" for="reportepago">
                                                Eliminados
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"></li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col mt-3 d-flex justify-content-center">
                                                <button class="btn btn-outline-success" type="submit"><i class="fa-solid fa-file-excel me-2"></i>Reporte en excel</button>
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