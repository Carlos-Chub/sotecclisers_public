//FUNCIONES PARA AGREGAR SERVICIOS CON CLIENTES, CONTRATOS
var countrow = 0;
var countid = 0;
function newrow(valores) {
    var t = $('#tablacontratos').DataTable();
    t.row.add([getInput('a', countrow, valores[2]), '<button class="btn btn-danger btn-sm" type="button" onclick="deleterow(this)"><i class="fa-solid fa-trash"></i></button>', getInput('b', countrow, valores[8]), getInput('c', countrow, valores[0]), getInput('d', countrow, valores[1]), getInput('e', countrow, valores[3]), getInput('f', countrow, valores[4]), getInput('g', countrow, valores[5]), getInput('h', countrow, valores[6]), getInput('i', countrow, valores[7])]).draw(false);
    countrow++;
    countid++;
    mostrardatos();

}

function deleterow(etiquetatr) {
    var t = $('#tablacontratos').DataTable();
    t.row($(etiquetatr).parents('tr')).remove().draw();
    countid--;
    mostrardatos();
}

function calcularsaldo() {
    var monto = parseFloat($('#monto').val());
    var abono = parseFloat($('#abono').val());
    var total = monto - abono;
    $('#saldo').val(total);
}

function mostrardatos() {
    console.log('row:' + countrow + "   -  id" + countid);
}

function getInput(letra, id, valor) {
    return '<input type="text" value="' + valor + '" id="' + letra + id + '" name="' + letra + id + '" style="background: none !important; border: 0 !important; display: inline-block !important; outline: none !important;" readonly>'
}

//validaciones
function validarencabezado() {
    var cliente = $('#id_cliente').val();
    var cliente2 = $('#nombrecli').val();
    if (cliente == "" || cliente == null || cliente2 == "" || cliente2 == null) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe seleccionar un cliente' });
        return;
    }
}
function validartransaccion() {
    var transaccion = $('#transaccion').val();
    if (transaccion == "" || transaccion == null) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe digitar número de transacción' });
        return;
    }
}

//validar campo vacio
function validacionesinputvacios(valores, mensaje) {
    var i = 0;
    while (i < valores.length) {
        if (valores[i] == '' || valores[i] == null) {
            Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo ' + mensaje[i] });
            return;
        }
        i++;
    }
}

//validacion select
function validarselect(valor, mensaje) {
    if (valor == '0') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Seleccione un ' + mensaje });
        return;
    }
}
//obtener valores
function getinputsval(datos) {
    const inputs = [''];
    var i = 0;
    while (i < datos.length) {
        inputs[i] = document.getElementById(datos[i]).value;
        i++;
    }
    return inputs;
}
function getOptionSelect(id) {
    var combo = document.getElementById(id);
    return combo.options[combo.selectedIndex].text;
}


//abrir y cerrar modal
function abrir_modal(id_modal) {
    $(id_modal).modal("show");
}

function cerrar_modal(id_modal) {
    $(id_modal).modal("hide");
}

function habilitar_deshabilitar(hab, des) {
    var i = 0;
    while (i < hab.length) {
        document.getElementById(hab[i]).disabled = false;
        i++;
    }
    var i = 0;
    while (i < des.length) {
        document.getElementById(des[i]).disabled = true;
        i++;
    }
}

function limpiar(valores) {
    var i = 0;
    while (i < valores.length) {
        document.getElementById(valores[i]).value = "";
        i++;
    }
}

function modal_principal(id_hidden, valores) {
    //ver si sacar el dato de un idhidden o directamente un toString
    var cadena = id_hidden.substr(0, 1);
    if (cadena == "#") {
        //todo el input
        var todo = ($(id_hidden).val()).split("/");
    }
    else {
        //todo la cadena
        var todo = id_hidden.split("/");
    }

    //se extraen los nombres de los inputs
    var nomInputs = (todo[0].toString()).split(",");
    //se extraen los rangos
    var rangos = (todo[1].toString()).split(",");
    //se extrae el separador
    var separador = todo[2].toString();

    //todo lo relacionado a la habilitacion o deshabilitacion
    var habilitar = [];
    var deshabilitar = [];
    if (todo[3].toString() != "#") {
        habilitar = (todo[3].toString()).split(",")
    }
    if (todo[4].toString() != "#") {
        deshabilitar = (todo[4].toString()).split(",")
    }
    habilitar_deshabilitar(habilitar, deshabilitar);
    //----fin de la habilitacion y deshabilitacion 
    // tratar de validar o unir campos para mandarlos a un solo input
    var contador = 0;
    for (var index = 0; index < nomInputs.length; index++) {
        if (rangos[index] !== 'A') {
            var aux = rangos[index].toString();
            var arrayaux = aux.split("-");
            var concatenacion = "";
            for (var index2 = arrayaux[0]; index2 <= arrayaux[1]; index2++) {
                if (index2 === arrayaux[0]) {
                    concatenacion = concatenacion + valores[index2 - 1];
                } else {
                    concatenacion = concatenacion + " " + separador + " ";
                    concatenacion = concatenacion + valores[index2 - 1];
                }
                contador++;
            }
            $("#" + nomInputs[index]).val(concatenacion);
        } else {
            $("#" + nomInputs[index]).val(valores[contador]);
            contador++;
        }
    }
}

function seleccionar_servicio(valor) {
    // document.getElementById("servicio").selectedIndex = valor;
    $('#servicio option[value=' + valor + ']').attr('selected', 'selected');
}

function seleccionar_cliente(id_hidden, valores) {
    cerrar_modal('#modal_cliente');
    modal_principal(id_hidden, valores);
}

function agregarunservicio() {
    //validacion de encabezado y transacciones
    var cliente = $('#id_cliente').val();
    var cliente2 = $('#nombrecli').val();
    if (cliente == "" || cliente == null || cliente2 == "" || cliente2 == null) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe seleccionar un cliente' });
        return;
    }
    var transaccion = $('#transaccion').val();
    if (transaccion == "" || transaccion == null) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe digitar número de transacción' });
        return;
    }
    //obtener valores
    var valores = getinputsval(['fechai', 'fechaf', 'servicio', 'monto', 'abono', 'saldo', 'cuota', 'observaciones']);
    valores.push(getOptionSelect('servicio'));
    //validar campos de forma manual
    if (valores[0] == '') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo fecha inicio' });
        return;
    }
    if (valores[1] == '') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo fecha final' });
        return;
    }
    //validar que la fecha inicio no puede ser mayor que la final
    if (valores[0] > valores[1]) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'La fecha de inicio no puede ser mayor que la fecha final' });
        return;
    }
    if (valores[2] == '0') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Seleccione un servicio' });
        return;
    }
    if (valores[3] == '') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo monto total' });
        return;
    }
    if (valores[4] == '') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo abono' });
        return;
    }
    if (valores[6] == '') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo cuota' });
        return;
    }
    //validar que sean valores numericos
    if (isNaN(valores[3]) || isNaN(valores[4]) || isNaN(valores[5]) || isNaN(valores[6]) || valores[3] < 0 || valores[4] < 0 || valores[5] < 0 || valores[6] < 0) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Verifique que los campos monto total, abono, sueldo y cuota sean valores numericos y sean positivos' });
        return;
    }

    //inserta una nueva fila
    newrow(valores)
}

function submitContrato() {
    var cliente = $('#id_cliente').val();
    var cliente2 = $('#nombrecli').val();
    if (cliente == "" || cliente == null || cliente2 == "" || cliente2 == null) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe seleccionar un cliente' });
        return;
    }
    var transaccion = $('#transaccion').val();
    if (transaccion == "" || transaccion == null) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe digitar número de transacción' });
        return;
    }
    if (countid == 0) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Tiene que agregar al menos un detalle' });
        return;
    }
    var t = $('#tablacontratos').DataTable();
    var column = t.column(0);
    column.visible(true);
    document.getElementById('formcontrato').submit();
}

function submitContrato3() {
    if (($('#id_cliente').val()) == "" || ($('#nombrecli').val()) == "") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe seleccionar un cliente' });
        return;
    }
    var transaccion = $('#transaccion').val();
    if (transaccion == "" || transaccion == null) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe digitar número de transacción' });
        return;
    }
    $('#id_detalle').val("");

    //obtener valores
    var valores = getinputsval(['fechai', 'fechaf', 'servicio', 'monto', 'abono', 'saldo', 'cuota', 'observaciones']);
    valores.push(getOptionSelect('servicio'));
    //validar campos de forma manual
    if (valores[0] == '') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo fecha inicio' });
        return;
    }
    if (valores[1] == '') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo fecha final' });
        return;
    }
    //validar que la fecha inicio no puede ser mayor que la final
    if (valores[0] > valores[1]) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'La fecha de inicio no puede ser mayor que la fecha final' });
        return;
    }
    if (valores[2] == '0') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Seleccione un servicio' });
        return;
    }
    if (valores[3] == '') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo monto total' });
        return;
    }
    if (valores[4] == '') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo abono' });
        return;
    }
    if (valores[6] == '') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo cuota' });
        return;
    }
    //validar que sean valores numericos
    if (isNaN(valores[3]) || isNaN(valores[4]) || isNaN(valores[5]) || isNaN(valores[6]) || valores[3] < 0 || valores[4] < 0 || valores[5] < 0 || valores[6] < 0) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Verifique que los campos monto total, abono, sueldo y cuota sean valores numericos y sean positivos' });
        return;
    }
    //enviar los datos mediante post
    document.getElementById('formcontrato').submit();
}

function submitContrato2() {
    if (($('#id_cliente').val()) == "" || ($('#nombrecli').val()) == "") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe seleccionar un cliente' });
        return;
    }
    var transaccion = $('#transaccion').val();
    if (transaccion == "" || transaccion == null) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe digitar número de transacción' });
        return;
    }

    if (($('#id_detalle').val()) == "" || ($('#abono').val()) == "") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe seleccionar un detalle de factura' });
        return;
    }

    //obtener valores
    var valores = getinputsval(['fechai', 'fechaf', 'servicio', 'monto', 'abono', 'saldo', 'cuota', 'observaciones']);
    valores.push(getOptionSelect('servicio'));
    //validar campos de forma manual
    if (valores[0] == '') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo fecha inicio' });
        return;
    }
    if (valores[1] == '') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo fecha final' });
        return;
    }
    //validar que la fecha inicio no puede ser mayor que la final
    if (valores[0] > valores[1]) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'La fecha de inicio no puede ser mayor que la fecha final' });
        return;
    }
    if (valores[2] == '0') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Seleccione un servicio' });
        return;
    }
    if (valores[3] == '') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo monto total' });
        return;
    }
    if (valores[4] == '') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo abono' });
        return;
    }
    if (valores[6] == '') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo cuota' });
        return;
    }
    //validar que sean valores numericos
    if (isNaN(valores[3]) || isNaN(valores[4]) || isNaN(valores[5]) || isNaN(valores[6]) || valores[3] < 0 || valores[4] < 0 || valores[5] < 0 || valores[6] < 0) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Verifique que los campos monto total, abono, sueldo y cuota sean valores numericos y sean positivos' });
        return;
    }
    //enviar los datos mediante post
    document.getElementById('formcontrato').submit();
}


function limpiarinput(inputs) {
    limpiar(inputs);
    document.getElementById('servicio').value = "0";
    document.getElementById('id_detalle').value = "";
}

function HabDes_boton(valor) {
    if (valor == 1) {
        $('#guardar').hide();
        $('#editar').show();
    }
    if (valor == 0) {
        $('#guardar').show();
        $('#editar').hide();
    }
}
//------------------------FIN-----------------------------
function loader_on() {
    //se debe de quitar el HIDDEN
    document.getElementById("loader_div").style.display = "block";
}
function loader_off() {
    //se debe de AGREGAR el HIDDEN
    document.getElementById("loader_div").style.display = "none";
}

//FUNCION PARA SELECCIONAR CLIENTE EN PAGOS
function seleccionar_cliente2(id_hidden, valores, ruta) {
    modal_principal(id_hidden, valores);
    buscar_servicios(ruta);
    cerrar_modal('#modal_cliente');
}
//FUNCIONES PARA LA SECCION DE PAGOS
function buscar_servicios(ruta) {
    id_factura = $('#id_factura').val();
    $.ajax({
        url: ruta + "/pagos/services",
        type: 'post',
        data: { 'id_factura': id_factura },
        dataType: 'json',
        beforeSend: function () {
            loader_on();
        },
        success: function (response) {
            // console.log(response);
            var len = response.length;
            $("#servicio").empty();
            for (var i = 0; i < len; i++) {
                var id = response[i]['id'];
                var name = response[i]['nombre'];
                $("#servicio").append("<option value='" + id + "'>" + name + "</option>");

            }
        },
        complete: function () {
            loader_off();
        }
    });
}

//funcion para obtener fecha actual
function formato_fecha() {
    let yourDate = new Date()
    yourDate.toISOString().split('T')[0]
    const offset = yourDate.getTimezoneOffset()
    yourDate = new Date(yourDate.getTime() - (offset * 60 * 1000))
    return yourDate.toISOString().split('T')[0]
}

//funcion para guardar pago
function editarpago() {
    if (($('#id_factura').val()) == "" || ($('#nombrecli').val()) == "") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe seleccionar un cliente' });
        return;
    }
    //validar fecha
    if (($('#fechapago').val()) == "") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe ingresar una fecha de pago' });
        return;
    }
    //validar numero de documento
    if (($('#documento').val()) == "") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe digitar un numero de documento' });
        return;
    }
    //validar servicio
    if (($('#servicio').val()) == "" || ($('#servicio').val()) == "0") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe seleccionar un servicio a pagar' });
        return;
    }
    //validar monto
    if (($('#monto').val()) == "") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe ingresar un monto' });
        return;
    }
    //obtener valores
    var valores = getinputsval(['fechapago', 'documento', 'servicio', 'monto']);
    //validar campos de forma manual
    var fechahoy = formato_fecha();
    //validar que sea la fecha de hoy
    if (valores[0] > fechahoy) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'La fecha de pago no puede ser mayor que la de hoy' });
        return;
    }
    //validar monto
    if (valores[3] < 1) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'El monto debe ser un numero positivo' });
        return;
    }
    //validar que sean valores numericos
    if (isNaN(valores[3])) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Verifique que el campo monto tiene que ser un numero' });
        return;
    }
    //enviar los datos mediante post
    document.getElementById('formpago').submit();
}

function guardarpago() {
    if (($('#id_factura').val()) == "" || ($('#nombrecli').val()) == "") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe seleccionar un cliente' });
        return;
    }
    //validar id pago y detalle
    if (($('#id_detalle').val()) == "" || ($('#id_pago').val())) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'No se tiene un id de pago para realizar la actualizacion' });
        return;
    }
    //validar fecha
    if (($('#fechapago').val()) == "") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe ingresar una fecha de pago' });
        return;
    }
    //validar numero de documento
    if (($('#documento').val()) == "") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe digitar un numero de documento' });
        return;
    }
    //validar servicio
    if (($('#servicio').val()) == "") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe digitar un servicio' });
        return;
    }
    //validar monto
    if (($('#monto').val()) == "") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe ingresar un monto' });
        return;
    }
    //obtener valores
    var valores = getinputsval(['fechapago', 'documento', 'monto']);
    //validar campos de forma manual
    var fechahoy = formato_fecha();
    //validar que sea la fecha de hoy
    if (valores[0] > fechahoy) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'La fecha de pago no puede ser mayor que la de hoy' });
        return;
    }
    //validar monto
    if (valores[3] < 1) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'El monto debe ser un numero positivo' });
        return;
    }
    //validar que sean valores numericos
    if (!isNaN(valores[3])) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Verifique que el campo monto sea un numero válido' });
        return;
    }
    //enviar los datos mediante post
    document.getElementById('formpago').submit();
}

//funcion para habilitar y deshabilitar boton
function activar_boton_clientes(radio, estado) {
    if (radio.checked) {
        if (estado) {
            document.getElementById('btCliente').disabled = estado;
            $("#id_cliente").val("");
            $("#nombrecli").val("");
            // document.getElementById('nombrecli').disabled = true;
        }
        else {
            //cuando se seleccionan una cuenta se habilita el select
            document.getElementById('btCliente').disabled = estado;
            // document.getElementById('nombrecli').disabled = true;
        }
    }
}

//------------------------FIN-----------------------------
//FUNCIONES PARA LOGIN
$("#togglePassword").click(function (e) {
    e.preventDefault();
    var type = $(this).parent().parent().find("#password").attr("type");
    if (type == "password") {
        $(this).removeClass("fa-regular fa-eye");
        $(this).addClass("fa-regular fa-eye-slash");
        $(this).parent().parent().find("#password").attr("type", "text");
    } else if (type == "text") {
        $(this).removeClass("fa-regular fa-eye-slash");
        $(this).addClass("fa-regular fa-eye");
        $(this).parent().parent().find("#password").attr("type", "password");
    }
});


//#region TODO TIPO DE REPORTES

//REPORTE DE CLIENTES CON EXCEL
$("#frmReporteClientes").on('submit', function (e) {
    e.preventDefault();
    if ((document.querySelector('input[name="estadocli"]:checked').value) == "") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe seleccionar un estado' });
    }
    else {
        var dataForm = $(this).serialize();
        $.ajax({
            url: "./reportes/reportesclientes",
            async: true,
            type: 'post',
            dataType: "html",
            contentType: "application/x-www-form-urlencoded",
            data: dataForm,
            beforeSend: function () {
                loader_on();
            },
            success: function (data) {
                // console.log(data);
                var opResult = JSON.parse(data);
                if (opResult.status == 1) {
                    var $a = $("<a>");
                    $a.attr("href", opResult.data);
                    $("body").append($a);
                    $a.attr("download", opResult.namefile + ".xlsx");
                    $a[0].click();
                    $a.remove();
                    Swal.fire({ icon: 'success', title: 'Muy Bien!', text: opResult.mensaje })
                }
                else {
                    Swal.fire({
                        icon: 'error', title: '¡ERROR!', text: opResult.mensaje
                    })
                }
            },
            complete: function () {
                loader_off();
            }
        });
    }
});

//REPORTE DE INGRESOS
function reporte_ingresos(tipo) {
    if ((document.querySelector('input[name="estadopago"]:checked').value) == "") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe seleccionar un estado' });
        return;
    }
    if ((document.querySelector('input[name="estadocli"]:checked').value) == "") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe seleccionar un estado' });
        return;
    }
    if ((document.querySelector('input[name="estadocli"]:checked').value) == "2") {
        if (($('#id_cliente').val()) == "" || ($('#nombrecli').val()) == "") {
            Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe seleccionar un cliente' });
            console.log(document.querySelector('input[name="estadocli"]:checked').value);
            return;
        }
    }
    var valores = getinputsval(['fechai', 'fechaf']);
    //validar campos de forma manual
    if (valores[0] == '') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo fecha inicio' });
        return;
    }
    if (valores[1] == '') {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'LLene el campo fecha final' });
        return;
    }
    //validar que la fecha inicio no puede ser mayor que la final
    if (valores[0] > valores[1]) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'La fecha de inicio no puede ser mayor que la fecha final' });
        return;
    }

    var dataForm = $("#frmReporteClientes").serialize();
    dataForm = dataForm + ("&tipo=" + tipo);

    $.ajax({
        url: "./reportesingresos",
        async: true,
        type: 'post',
        dataType: "html",
        // dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        data: dataForm,
        beforeSend: function () {
            loader_on();
        },
        success: function (data) {
            // console.log(JSON.parse(data));
            var opResult = JSON.parse(data);
            if (opResult.status == 1) {
                var $a = $("<a>");
                $a.attr("href", opResult.data);
                $("body").append($a);
                $a.attr("download", opResult.namefile + "." + tipo);
                $a[0].click();
                $a.remove();
                Swal.fire({ icon: 'success', title: 'Muy Bien!', text: opResult.mensaje })
            }
            else {
                Swal.fire({
                    icon: 'error', title: '¡ERROR!', text: opResult.mensaje
                })
            }
        },
        complete: function () {
            loader_off();
        }
    });

}

//REPORTE DE SERVICIOS CON CLIENTES
$("#frmReporteServicios").on('submit', function (e) {
    e.preventDefault();
    if ((document.querySelector('input[name="reporteser"]:checked').value) == "") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe seleccionar un estado' });
    }
    else {
        var dataForm = $(this).serialize();
        $.ajax({
            url: "./reportes/reportesservicios",
            async: true,
            type: 'post',
            dataType: "html",
            contentType: "application/x-www-form-urlencoded",
            data: dataForm,
            beforeSend: function () {
                loader_on();
            },
            success: function (data) {
                console.log(data);
                var opResult = JSON.parse(data);
                if (opResult.status == 1) {
                    var $a = $("<a>");
                    $a.attr("href", opResult.data);
                    $("body").append($a);
                    $a.attr("download", opResult.namefile + ".xlsx");
                    $a[0].click();
                    $a.remove();
                    Swal.fire({ icon: 'success', title: 'Muy Bien!', text: opResult.mensaje })
                }
                else {
                    Swal.fire({
                        icon: 'error', title: '¡ERROR!', text: opResult.mensaje
                    })
                }
            },
            complete: function () {
                loader_off();
            }
        });
    }
});

//FUNCION PARA GENERAR REPORTE DE FACTURA
function reporteFactura(id) {
    //validar campos de forma manual
    if (id == '' || id == null) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'No ha seleccionado un registro para generar el reporte' });
        return;
    }
    $.ajax({
        url: "./reportes/reportefactura",
        async: true,
        type: 'post',
        dataType: "html",
        contentType: "application/x-www-form-urlencoded",
        data: {'id': id},
        beforeSend: function () {
            loader_on();
        },
        success: function (data) {
            console.log(data);
            var opResult = JSON.parse(data);
            if (opResult.status == 1) {
                var $a = $("<a>");
                $a.attr("href", opResult.data);
                $("body").append($a);
                $a.attr("download", opResult.namefile + ".pdf");
                $a[0].click();
                $a.remove();
                Swal.fire({ icon: 'success', title: 'Muy Bien!', text: opResult.mensaje })
            }
            else {
                Swal.fire({
                    icon: 'error', title: '¡ERROR!', text: opResult.mensaje
                })
            }
        },
        complete: function () {
            loader_off();
        }
    });

}

//REPORTE DE UN PAGO
function reportePago(id) {
    //validar campos de forma manual
    if (id == '' || id == null) {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'No ha seleccionado un registro para generar el reporte' });
        return;
    }
    $.ajax({
        url: "./reportes/reportepago",
        async: true,
        type: 'post',
        dataType: "html",
        contentType: "application/x-www-form-urlencoded",
        data: {'id': id},
        beforeSend: function () {
            loader_on();
        },
        success: function (data) {
            console.log(data);
            var opResult = JSON.parse(data);
            if (opResult.status == 1) {
                var $a = $("<a>");
                $a.attr("href", opResult.data);
                $("body").append($a);
                $a.attr("download", opResult.namefile + ".pdf");
                $a[0].click();
                $a.remove();
                Swal.fire({ icon: 'success', title: 'Muy Bien!', text: opResult.mensaje })
            }
            else {
                Swal.fire({
                    icon: 'error', title: '¡ERROR!', text: opResult.mensaje
                })
            }
        },
        complete: function () {
            loader_off();
        }
    });

}

//REPORTE DE SERVICIOS CON CLIENTES
$("#frmReportePagos").on('submit', function (e) {
    e.preventDefault();
    if ((document.querySelector('input[name="reportepago"]:checked').value) == "") {
        Swal.fire({ icon: 'error', title: '¡ERROR!', text: 'Debe seleccionar un estado' });
    }
    else {
        var dataForm = $(this).serialize();
        $.ajax({
            url: "./reportes/reportespagos",
            async: true,
            type: 'post',
            dataType: "html",
            contentType: "application/x-www-form-urlencoded",
            data: dataForm,
            beforeSend: function () {
                loader_on();
            },
            success: function (data) {
                console.log(data);
                var opResult = JSON.parse(data);
                if (opResult.status == 1) {
                    var $a = $("<a>");
                    $a.attr("href", opResult.data);
                    $("body").append($a);
                    $a.attr("download", opResult.namefile + ".xlsx");
                    $a[0].click();
                    $a.remove();
                    Swal.fire({ icon: 'success', title: 'Muy Bien!', text: opResult.mensaje })
                }
                else {
                    Swal.fire({
                        icon: 'error', title: '¡ERROR!', text: opResult.mensaje
                    })
                }
            },
            complete: function () {
                loader_off();
            }
        });
    }
});
//#endregion

