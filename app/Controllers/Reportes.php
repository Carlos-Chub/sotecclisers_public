<?php

namespace App\Controllers;

use App\Models\ModelServicio;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\ThirdParty\FPDF;

date_default_timezone_set('America/Guatemala');

class Reportes extends BaseController
{
	public function index()
	{
		//envio de datos a la vista
		echo view('/layout/header');
		echo view('/reportes/clientes');
		echo view('/layout/footer');
	}

	public function ingresos()
	{
		//Se hace la consulta a para llenar el modal
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_clientes cl');
		$builder->select('cl.id, cl.nombre, cl.nomcom, cl.telefono, dep.nombre departamento');
		$builder->join('tb_departamento dep', 'cl.id_departamento = dep.id');
		$builder->where('cl.estado', '1');
		$datos['datos'] = $builder->get()->getResultArray();

		//envio de datos a la vista
		echo view('/layout/header');
		echo view('/reportes/ingresos');
		echo view('/layout/footer');
		echo view('/reportes/modalcli', $datos);
	}

	public function reportesclientes()
	{
		if (!$_POST) {
			return redirect()->to(base_url('reportes'));
			exit;
		}
		$tipo = $_POST['estadocli'];
		$hoy = date("Y-m-d H:i:s");
		$hoy_archivo = date("d-m-Y");

		$fuente_encabezado = "Arial";
		$fuente = "Courier";
		$tamanioFecha = 9; //tamaño de letra de la fecha y usuario
		$tamanioEncabezado = 14; //tamaño de letra del encabezado
		$tamanioTabla = 11; //tamaño de letra de la fecha y usuario


		$spreadsheet = new Spreadsheet();
		$spreadsheet
			->getProperties()
			->setCreator("SOTECPRO")
			->setLastModifiedBy('SOTECPRO')
			->setTitle('Reporte')
			->setSubject('Listado de clientes')
			->setDescription('Reporte generado por sotecclisers')
			->setKeywords('PHPSpreadsheet')
			->setCategory('Excel');

		# Como ya hay una hoja por defecto, la obtenemos, no la creamos
		$hojaReporte = $spreadsheet->getActiveSheet();
		$hojaReporte->setTitle("Reporte de clientes");

		//insertarmos la fecha y usuario
		$hojaReporte->setCellValue("A1", $hoy);
		$hojaReporte->setCellValue("A3", 'CLIENTES DE SOTECPRO');
		$textoreporte = "Listado de todos los clientes";
		$textoarchivo = "Reporte de clientes - ";
		if ($tipo == '2') {
			$textoreporte = "Listado de clientes activos";
			$textoarchivo = "Reporte de clientes activos - ";
		}
		if ($tipo == '3') {
			$textoreporte = "Listado de clientes inactivos";
			$textoarchivo = "Reporte de clientes inactivos - ";
		}
		$hojaReporte->setCellValue("A4", $textoreporte);
		//hacer pequeño las letras de la fecha, definir arial como tipo de letra
		$hojaReporte->getStyle("A1:Q1")->getFont()->setSize($tamanioFecha)->setName($fuente_encabezado);
		$hojaReporte->getStyle("A3:Q3")->getFont()->setSize($tamanioFecha)->setName($fuente_encabezado);
		//centrar el texto de la fecha
		$hojaReporte->getStyle("A1:Q1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$hojaReporte->getStyle("A3:Q3")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		//hacer grande las letras del encabezado
		$hojaReporte->getStyle("A3:Q3")->getFont()->setSize($tamanioEncabezado)->setName($fuente_encabezado);
		$hojaReporte->getStyle("A4:Q4")->getFont()->setSize($tamanioEncabezado)->setName($fuente_encabezado);
		$hojaReporte->getStyle("A6:Q6")->getFont()->setSize($tamanioEncabezado)->setName($fuente_encabezado);
		//centrar el texto del encabezado
		$hojaReporte->getStyle("A4:Q4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$hojaReporte->getStyle("A6:Q6")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		//hacer pequeño las letras del encabezado de titulo
		$hojaReporte->getStyle("A4:Q4")->getFont()->setSize($tamanioTabla)->setName($fuente);
		$hojaReporte->getStyle("A6:Q6")->getFont()->setSize($tamanioTabla)->setName($fuente);
		//centrar los encabezado de la tabla
		$hojaReporte->getStyle("A4:Q4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$hojaReporte->getStyle("A6:Q6")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		$hojaReporte->mergeCells('A1:Q1');
		$hojaReporte->mergeCells('A3:Q3');
		$hojaReporte->mergeCells('A4:Q4');

		# Escribir encabezado de la tabla
		$encabezado_tabla = ["No.", "NOMBRE", "NOMBRE COMERCIAL", "NIT", "DIRECCIÓN", "TELEFONO", "DEPARTAMENTO", "NOMBRE REPRESENTANTE", "TELEFONO REPRESENTANTE", "EMAIL", "NOMBRE CONTADOR", "TELEFONO CONTADOR", "FECHA", "OBSERVACIONES", "CREATED_AT", "UPDATED_AT", "DELETED_AT"];


		# El último argumento es por defecto A1 pero lo pongo para que se explique mejor
		$hojaReporte->fromArray($encabezado_tabla, null, 'A6')->getStyle('A6:Q6')->getFont()->setName($fuente);
		//ingreso de los datos de tabla
		$contador = 7;
		//REALIZAR LA CONSULTA
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_clientes cl');
		$builder->select('cl.*, dep.nombre departamento');
		$builder->join('tb_departamento dep', 'cl.id_departamento = dep.id');
		if ($tipo == '2') {
			$builder->where('cl.estado', '1');
		}
		if ($tipo == '3') {
			$builder->where('cl.estado', '0');
		}
		$builder->orderBy('cl.fecha', 'DESC');
		$datos = $builder->get()->getResultArray();
		$numero = 1;
		foreach ($datos as $dato) {
			$hojaReporte->setCellValueByColumnAndRow(1, $contador, $numero);
			$hojaReporte->setCellValueByColumnAndRow(2, $contador, $dato['nombre']);
			$hojaReporte->setCellValueByColumnAndRow(3, $contador, $dato['nomcom']);
			$hojaReporte->setCellValueByColumnAndRow(4, $contador, $dato['nit']);
			$hojaReporte->setCellValueByColumnAndRow(5, $contador, $dato['direccion']);
			$hojaReporte->setCellValueByColumnAndRow(6, $contador, $dato['telefono']);
			$hojaReporte->setCellValueByColumnAndRow(7, $contador, $dato['departamento']);
			$hojaReporte->setCellValueByColumnAndRow(8, $contador, $dato['repnom']);
			$hojaReporte->setCellValueByColumnAndRow(9, $contador, $dato['reptel']);
			$hojaReporte->setCellValueByColumnAndRow(10, $contador, $dato['email']);
			$hojaReporte->setCellValueByColumnAndRow(11, $contador, $dato['contnom']);
			$hojaReporte->setCellValueByColumnAndRow(12, $contador, $dato['conttel']);
			$hojaReporte->setCellValueByColumnAndRow(13, $contador, $dato['fecha']);
			$hojaReporte->setCellValueByColumnAndRow(14, $contador, $dato['observaciones']);
			$hojaReporte->setCellValueByColumnAndRow(15, $contador, $dato['created_at']);
			$hojaReporte->setCellValueByColumnAndRow(16, $contador, $dato['updated_at']);
			$hojaReporte->setCellValueByColumnAndRow(17, $contador, $dato['deleted_at']);
			$contador++;
			$numero++;
		};
		//redimensionar celdas
		$hojaReporte->getColumnDimension('A')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('B')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('C')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('D')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('E')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('F')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('G')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('H')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('I')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('J')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('K')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('L')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('M')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('N')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('O')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('P')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('Q')->setAutoSize(TRUE);

		//opcion de envio de datos---------------------------------------------------------
		ob_start();
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save("php://output");
		$xlsData = ob_get_contents();
		ob_end_clean();
		//envio de repuesta a ajax para descargarlos
		$opResult = array(
			'status' => 1,
			'namefile' => $textoarchivo . $hoy_archivo,
			'mensaje' => $textoarchivo . ' generado correctamente',
			'data' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
		);
		echo json_encode($opResult);
		exit;
		//-----------------------------------------------------------------------------------

		// Creación del objeto de la clase heredada
		// $pdf = new PDF('Reporte de clientes');
		// $pdf->AliasNbPages();
		// $pdf->AddPage();
		// $pdf->Cell(0, 0, 'Hola mundo FPDF desde Codeigniter', 0, 1, 'C');

		// ob_start();
		// $pdf->Output();
		// $pdfData = ob_get_contents();
		// ob_end_clean();

		// $opResult = array(
		// 	'status' => 1,
		// 	'mensaje' => 'Reporte generado correctamente',
		// 	'namefile' => "CREDITOS",
		// 	'data' => "data:application/vnd.ms-word;base64," . base64_encode($pdfData)
		// );
		// echo json_encode($opResult);
	}

	public function reportesingresos()
	{
		if (!$_POST) {
			return redirect()->to(base_url('reportes/ingresos'));
			exit;
		}
		$k = 0;
		$sumaanterior = 0;
		$tituloreporte = "";
		$data[] = [];

		$fuente = "Courier";
		$tamanio_linea = 4; //altura de la linea/celda
		$ancho_linea = 30; //anchura de la linea/celda

		//consulta de pagos y facturas
		if ($_POST['estadopago'] == '1') {
			//SUMA DE LO ANTERIOR
			$tituloreporte = "REPORTE DE INGRESOS DE FACTURAS Y PAGOS DE TODOS LOS CLIENTES ENTRE LA FECHA " . $_POST['fechai'] . " AL " . $_POST['fechaf'];
			//suma todo lo anterior a la fecha inicial
			$db      = \Config\Database::connect();
			$builder = $db->table('tb_detalle det');
			$builder->select("cl.id AS idcliente, det.id AS iddetalle, CONCAT(cl.nombre, ' - ',cl.nomcom) AS nombre, DATE(ft.created_at) AS fecha, ft.transaccion, SUM(det.abono) AS monto, ser.nombre AS servicio");
			$builder->join('tb_factura ft', 'det.id_factura = ft.id');
			$builder->join('tb_servicios ser', 'det.id_servicio = ser.id');
			$builder->join('tb_clientes cl', 'ft.id_cliente = cl.id');
			$builder->join('tb_departamento dep', 'cl.id_departamento=dep.id');
			$builder->where('ft.estado', '1');
			$builder->where('det.estado', '1');
			//filtro por cliente
			if ($_POST['estadocli'] == '2') {
				$builder->where('cl.id', $_POST['id_cliente']);
				$tituloreporte = "REPORTE DE INGRESOS DE FACTURAS Y PAGOS DEL CLIENTE " . $_POST['nombrecli'] . " ENTRE LA FECHA " . $_POST['fechai'] . " AL " . $_POST['fechaf'];
			}
			//filtro de fecha
			$builder->where('DATE(ft.created_at) <',  $_POST['fechai']);

			$datos['datos'] = $builder->get()->getResultArray();
			$db2      = \Config\Database::connect();
			$builder2 = $db2->table('tb_detalle det');
			$builder2->select("det.id AS iddetalle, CONCAT(cl.nombre, ' - ',cl.nomcom) AS nombre, pg.fecha AS fecha, pg.numdoc AS transaccion, SUM(pg.monto) AS monto, ser.nombre AS servicio");
			$builder2->join('tb_factura ft', 'det.id_factura = ft.id');
			$builder2->join('tb_servicios ser', 'det.id_servicio = ser.id');
			$builder2->join('tb_clientes cl', 'ft.id_cliente = cl.id');
			$builder2->join('tb_departamento dep', 'cl.id_departamento=dep.id');
			$builder2->join('tb_pago pg', 'det.id = pg.id_detalle');
			$builder2->where('ft.estado', '1');
			$builder2->where('det.estado', '1');
			$builder2->where('pg.estado', '1');
			//filtro por cliente
			if ($_POST['estadocli'] == '2') {
				$builder2->where('cl.id', $_POST['id_cliente']);
			}
			//filtro de fecha
			$builder2->where('DATE(pg.fecha) <',  $_POST['fechai']);

			$datos2['datos2'] = $builder2->get()->getResultArray();
			$date_now = date('Y-m-d');
			$date_past = strtotime('-1 day', strtotime($date_now));
			$date_past = date('Y-m-d', $date_past);
			$sumaanterior = $datos['datos'][0]['monto'] + $datos2['datos2'][0]['monto'];
			$data[$k]['idcliente'] = '';
			$data[$k]['iddetalle'] = '';
			$data[$k]['nombre'] = '';
			$data[$k]['fecha'] = $date_past;
			$data[$k]['transaccion'] = '';
			$data[$k]['monto'] = $sumaanterior;
			$data[$k]['servicio'] = '';
			$k++;

			//SUMA DE LO ACTUAL
			//primera consulta de montos de facturas
			$db      = \Config\Database::connect();
			$builder = $db->table('tb_detalle det');
			$builder->select("cl.id AS idcliente, det.id AS iddetalle, CONCAT(cl.nombre, ' - ',cl.nomcom) as nombre, DATE(ft.created_at) as fecha, ft.transaccion as transaccion, det.abono as monto, ser.nombre as servicio");
			$builder->join('tb_factura ft', 'det.id_factura = ft.id');
			$builder->join('tb_servicios ser', 'det.id_servicio = ser.id');
			$builder->join('tb_clientes cl', 'ft.id_cliente = cl.id');
			$builder->join('tb_departamento dep', 'cl.id_departamento=dep.id');
			$builder->where('ft.estado', '1');
			$builder->where('det.estado', '1');
			//filtro por cliente
			if ($_POST['estadocli'] == '2') {
				$builder->where('cl.id', $_POST['id_cliente']);
			}
			//filtro de fecha
			$builder->where('DATE(ft.created_at) >=',  $_POST['fechai']);
			$builder->where('DATE(ft.created_at) <=',  $_POST['fechaf']);

			$datos['datos'] = $builder->get()->getResultArray();

			for ($i = 0; $i < count($datos['datos']); $i++) {
				$data[$k] = $datos['datos'][$i];
				$data[$k]['razon'] = 'PAGO EN FACTURA';
				$k++;
				//consultar uno por uno
				$db2      = \Config\Database::connect();
				$builder2 = $db2->table('tb_detalle det');
				$builder2->select("cl.id AS idcliente, det.id AS iddetalle, CONCAT(cl.nombre, ' - ',cl.nomcom) AS nombre, pg.fecha AS fecha, pg.numdoc AS transaccion, pg.monto AS monto, ser.nombre AS servicio");
				$builder2->join('tb_factura ft', 'det.id_factura = ft.id');
				$builder2->join('tb_servicios ser', 'det.id_servicio = ser.id');
				$builder2->join('tb_clientes cl', 'ft.id_cliente = cl.id');
				$builder2->join('tb_departamento dep', 'cl.id_departamento=dep.id');
				$builder2->join('tb_pago pg', 'det.id = pg.id_detalle');
				$builder2->where('ft.estado', '1');
				$builder2->where('det.estado', '1');
				$builder2->where('pg.estado', '1');
				$builder2->where('pg.id_detalle', $datos['datos'][$i]['iddetalle']);
				//filtro de fecha
				$builder2->where('DATE(pg.fecha) >=',  $_POST['fechai']);
				$builder2->where('DATE(pg.fecha) <=',  $_POST['fechaf']);
				$datos2['datos2'] = $builder2->get()->getResultArray();
				for ($j = 0; $j < count($datos2['datos2']); $j++) {
					$data[$k] = $datos2['datos2'][$j];
					$data[$k]['razon'] = 'PAGO ORDINARIO';
					$k++;
				}
			}
		} else if ($_POST['estadopago'] == '2') {
			//SUMA DE LO ANTERIOR
			$db2      = \Config\Database::connect();
			$builder2 = $db2->table('tb_detalle det');
			$builder2->select("det.id AS iddetalle, CONCAT(cl.nombre, ' - ',cl.nomcom) AS nombre, pg.fecha AS fecha, pg.numdoc AS transaccion, SUM(pg.monto) AS monto, ser.nombre AS servicio");
			$builder2->join('tb_factura ft', 'det.id_factura = ft.id');
			$builder2->join('tb_servicios ser', 'det.id_servicio = ser.id');
			$builder2->join('tb_clientes cl', 'ft.id_cliente = cl.id');
			$builder2->join('tb_departamento dep', 'cl.id_departamento=dep.id');
			$builder2->join('tb_pago pg', 'det.id = pg.id_detalle');
			$builder2->where('ft.estado', '1');
			$builder2->where('det.estado', '1');
			$builder2->where('pg.estado', '1');
			//filtro por cliente
			$tituloreporte = "REPORTE DE INGRESOS DE PAGOS DE TODOS LOS CLIENTES ENTRE LA FECHA " . $_POST['fechai'] . " AL " . $_POST['fechaf'];
			if ($_POST['estadocli'] == '2') {
				$builder2->where('cl.id', $_POST['id_cliente']);
				$tituloreporte = "REPORTE DE INGRESOS DE PAGOS DEL CLIENTE " . $_POST['nombrecli'] . " ENTRE LA FECHA " . $_POST['fechai'] . " AL " . $_POST['fechaf'];
			}
			//filtro de fecha
			$builder2->where('DATE(pg.fecha) <',  $_POST['fechai']);

			$datos['datos'] = $builder2->get()->getResultArray();

			$date_now = date('Y-m-d');
			$date_past = strtotime('-1 day', strtotime($date_now));
			$date_past = date('Y-m-d', $date_past);
			$sumaanterior = $datos['datos'][0]['monto'];
			$data[$k]['idcliente'] = '';
			$data[$k]['iddetalle'] = '';
			$data[$k]['nombre'] = '';
			$data[$k]['fecha'] = $date_past;
			$data[$k]['transaccion'] = '';
			$data[$k]['monto'] = $sumaanterior;
			$data[$k]['servicio'] = '';
			$k++;

			//SUMA DE LO ACTUAL
			$db2      = \Config\Database::connect();
			$builder2 = $db2->table('tb_detalle det');
			$builder2->select("cl.id AS idcliente, det.id AS iddetalle, CONCAT(cl.nombre, ' - ',cl.nomcom) AS nombre, pg.fecha AS fecha, pg.numdoc AS transaccion, pg.monto AS monto, ser.nombre AS servicio");
			$builder2->join('tb_factura ft', 'det.id_factura = ft.id');
			$builder2->join('tb_servicios ser', 'det.id_servicio = ser.id');
			$builder2->join('tb_clientes cl', 'ft.id_cliente = cl.id');
			$builder2->join('tb_departamento dep', 'cl.id_departamento=dep.id');
			$builder2->join('tb_pago pg', 'det.id = pg.id_detalle');
			$builder2->where('ft.estado', '1');
			$builder2->where('det.estado', '1');
			$builder2->where('pg.estado', '1');
			//filtro por cliente
			if ($_POST['estadocli'] == '2') {
				$builder2->where('cl.id', $_POST['id_cliente']);
			}
			//filtro de fecha
			$builder2->where('DATE(pg.fecha) >=',  $_POST['fechai']);
			$builder2->where('DATE(pg.fecha) <=',  $_POST['fechaf']);

			$datos['datos'] = $builder2->get()->getResultArray();
			for ($i = 0; $i < count($datos['datos']); $i++) {
				$data[$k] = $datos['datos'][$i];
				$data[$k]['razon'] = 'PAGO ORDINARIO';
				$k++;
			}
		} else {
			//SUMA DE LO ANTERIOR
			$db      = \Config\Database::connect();
			$builder = $db->table('tb_detalle det');
			$builder->select("cl.id AS idcliente, det.id AS iddetalle, CONCAT(cl.nombre, ' - ',cl.nomcom) AS nombre, DATE(ft.created_at) AS fecha, ft.transaccion, SUM(det.abono) AS monto, ser.nombre AS servicio");
			$builder->join('tb_factura ft', 'det.id_factura = ft.id');
			$builder->join('tb_servicios ser', 'det.id_servicio = ser.id');
			$builder->join('tb_clientes cl', 'ft.id_cliente = cl.id');
			$builder->join('tb_departamento dep', 'cl.id_departamento=dep.id');
			$builder->where('ft.estado', '1');
			$builder->where('det.estado', '1');
			//filtro por cliente
			$tituloreporte = "REPORTE DE INGRESOS DE FACTURAS DE TODOS LOS CLIENTES ENTRE LA FECHA " . $_POST['fechai'] . " AL " . $_POST['fechaf'];
			if ($_POST['estadocli'] == '2') {
				$builder->where('cl.id', $_POST['id_cliente']);
				$tituloreporte = "REPORTE DE INGRESOS DE FACTURAS DEL CLIENTE " . $_POST['nombrecli'] . " ENTRE LA FECHA " . $_POST['fechai'] . " AL " . $_POST['fechaf'];
			}
			//filtro de fecha
			$builder->where('DATE(ft.created_at) <',  $_POST['fechai']);

			$datos['datos'] = $builder->get()->getResultArray();
			$date_now = date('Y-m-d');
			$date_past = strtotime('-1 day', strtotime($date_now));
			$date_past = date('Y-m-d', $date_past);
			$sumaanterior = $datos['datos'][0]['monto'];
			$data[$k]['idcliente'] = '';
			$data[$k]['iddetalle'] = '';
			$data[$k]['nombre'] = '';
			$data[$k]['fecha'] = $date_past;
			$data[$k]['transaccion'] = '';
			$data[$k]['monto'] = $sumaanterior;
			$data[$k]['servicio'] = '';
			$k++;

			//SUMA DE LO ACTUAL
			$db      = \Config\Database::connect();
			$builder = $db->table('tb_detalle det');
			$builder->select("cl.id AS idcliente, det.id AS iddetalle, CONCAT(cl.nombre, ' - ',cl.nomcom) as nombre, DATE(ft.created_at) as fecha, ft.transaccion as transaccion, det.abono as monto, ser.nombre as servicio");
			$builder->join('tb_factura ft', 'det.id_factura = ft.id');
			$builder->join('tb_servicios ser', 'det.id_servicio = ser.id');
			$builder->join('tb_clientes cl', 'ft.id_cliente = cl.id');
			$builder->join('tb_departamento dep', 'cl.id_departamento=dep.id');
			$builder->where('ft.estado', '1');
			$builder->where('det.estado', '1');
			//filtro por cliente
			if ($_POST['estadocli'] == '2') {
				$builder->where('cl.id', $_POST['id_cliente']);
			}
			//filtro de fecha
			$builder->where('DATE(ft.created_at) >=',  $_POST['fechai']);
			$builder->where('DATE(ft.created_at) <=',  $_POST['fechaf']);

			$datos['datos'] = $builder->get()->getResultArray();
			for ($i = 0; $i < count($datos['datos']); $i++) {
				$data[$k] = $datos['datos'][$i];
				$data[$k]['razon'] = 'PAGO EN FACTURA';
				$k++;
			}
		}
		//ya se tienen todos los datos necesarios

		//CONDICION PARA REPORTE EN PDF
		if ($_POST['tipo'] == 'pdf') {
			//CODIGO PARA DESCARGA DE ARCHIVO
			// Creación del objeto de la clase heredada
			$pdf = new PDF($tituloreporte);
			$pdf->AliasNbPages();
			$pdf->AddPage();
			//ACA DE SE VAN A CARGAR LOS DATOS
			$pdf->SetFont($fuente, '', 8);
			//impresion de los resultados anteriores a la fecha mencionada
			$pdf->CellFit($ancho_linea - 15, $tamanio_linea + 1, utf8_decode('1'), 0, 0, 'C', 0, '', 1, 0);
			$pdf->CellFit($ancho_linea + 37, $tamanio_linea + 1, utf8_decode(" "), 0, 0, 'C', 0, '', 1, 0);
			$pdf->CellFit($ancho_linea - 3, $tamanio_linea + 1, utf8_decode($data[0]['fecha']), 0, 0, 'C', 0, '', 1, 0);
			$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, utf8_decode(" "), 0, 0, 'C', 0, '', 1, 0);
			$pdf->CellFit($ancho_linea + 12, $tamanio_linea + 1, number_format(($data[0]['monto']), 2), 0, 0, 'R', 0, '', 1, 0);
			$pdf->CellFit($ancho_linea + 20, $tamanio_linea + 1, utf8_decode(" "), 0, 0, 'C', 0, '', 1, 0);
			$pdf->CellFit($ancho_linea + 21, $tamanio_linea + 1, utf8_decode("SALDO ANTERIOR A LA FECHA INICIAL"), 0, 0, 'C', 0, '', 1, 0);
			$pdf->Ln(5);
			//impresion de los resultados en las fechas seleccionadas
			$aux = "";
			for ($i = 1; $i < count($data); $i++) {
				if ($aux == $data[$i]['idcliente']) {
					$pdf->CellFit($ancho_linea - 15, $tamanio_linea + 1, utf8_decode($i + 1), 0, 0, 'C', 0, '', 1, 0);
					$pdf->CellFit($ancho_linea + 37, $tamanio_linea + 1, utf8_decode(" "), 0, 0, 'C', 0, '', 1, 0);
					$pdf->CellFit($ancho_linea - 3, $tamanio_linea + 1, utf8_decode($data[$i]['fecha']), 0, 0, 'C', 0, '', 1, 0);
					$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, utf8_decode($data[$i]['transaccion']), 0, 0, 'L', 0, '', 1, 0);
					$pdf->CellFit($ancho_linea + 12, $tamanio_linea + 1, number_format(($data[$i]['monto']), 2), 0, 0, 'R', 0, '', 1, 0);
					$pdf->CellFit($ancho_linea + 20, $tamanio_linea + 1, utf8_decode($data[$i]['servicio']), 0, 0, 'C', 0, '', 1, 0);
					$pdf->CellFit($ancho_linea + 21, $tamanio_linea + 1, utf8_decode($data[$i]['razon']), 0, 0, 'C', 0, '', 1, 0);
					$pdf->Ln(5);
				}
				if ($aux != $data[$i]['idcliente']) {
					$aux = $data[$i]['idcliente'];

					$pdf->CellFit($ancho_linea - 15, $tamanio_linea + 1, utf8_decode($i + 1), 0, 0, 'C', 0, '', 1, 0);
					$pdf->CellFit($ancho_linea + 37, $tamanio_linea + 1, strtoupper(utf8_decode($data[$i]['nombre'])), 0, 0, 'L', 0, '', 1, 0);
					$pdf->CellFit($ancho_linea - 3, $tamanio_linea + 1, utf8_decode($data[$i]['fecha']), 0, 0, 'C', 0, '', 1, 0);
					$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, utf8_decode($data[$i]['transaccion']), 0, 0, 'L', 0, '', 1, 0);
					$pdf->CellFit($ancho_linea + 12, $tamanio_linea + 1, number_format(($data[$i]['monto']), 2), 0, 0, 'R', 0, '', 1, 0);
					$pdf->CellFit($ancho_linea + 20, $tamanio_linea + 1, utf8_decode($data[$i]['servicio']), 0, 0, 'C', 0, '', 1, 0);
					$pdf->CellFit($ancho_linea + 21, $tamanio_linea + 1, utf8_decode($data[$i]['razon']), 0, 0, 'C', 0, '', 1, 0);
					$pdf->Ln(5);
				}
			}
			$pdf->SetFont($fuente, 'B', 8);
			//suma de totales
			$sumamonto = array_sum(array_column($data, 'monto'));
			//imprimir suma total
			$pdf->Cell(0, 0, ' ', 'B', 1, 'C');
			$pdf->CellFit($ancho_linea + 104, $tamanio_linea + 1, 'TOTAL MONTO', 0, 0, 'C', 0, '', 1, 0);
			$pdf->CellFit($ancho_linea + 12, $tamanio_linea + 1, number_format(($sumamonto), 2), 0, 0, 'R', 0, '', 1, 0);
			$pdf->Ln(5);
			$pdf->Cell(0, 0, ' ', 'B', 1, 'C');

			ob_start();
			$pdf->Output();
			$pdfData = ob_get_contents();
			ob_end_clean();

			$opResult = array(
				'status' => 1,
				'mensaje' => 'Reporte generado correctamente',
				'namefile' => "Reporte_ingresos_" . date('d-m-Y'),
				'data' => "data:application/vnd.ms-word;base64," . base64_encode($pdfData)
			);
			echo json_encode($opResult);
		}

		if ($_POST['tipo'] == 'xlsx') {
			$hoy = date("Y-m-d H:i:s");
			$hoy_archivo = date("d-m-Y");

			$fuente_encabezado = "Arial";
			$fuente = "Courier";
			$tamanioFecha = 9; //tamaño de letra de la fecha y usuario
			$tamanioEncabezado = 14; //tamaño de letra del encabezado
			$tamanioTabla = 11; //tamaño de letra de la fecha y usuario
			$spreadsheet = new Spreadsheet();
			$spreadsheet
				->getProperties()
				->setCreator("SOTECPRO")
				->setLastModifiedBy('SOTECPRO')
				->setTitle('Reporte')
				->setSubject('Ingresos')
				->setDescription('Reporte generado por sotecclisers')
				->setKeywords('PHPSpreadsheet')
				->setCategory('Excel');

			# Como ya hay una hoja por defecto, la obtenemos, no la creamos
			$hojaReporte = $spreadsheet->getActiveSheet();
			$hojaReporte->setTitle("Reporte de clientes");

			//insertarmos la fecha y usuario
			$hojaReporte->setCellValue("A1", $hoy);
			$hojaReporte->setCellValue("A3", 'REPORTE DE INGRESOS');

			$hojaReporte->setCellValue("A4", $tituloreporte);
			//hacer pequeño las letras de la fecha, definir arial como tipo de letra
			$hojaReporte->getStyle("A1:G1")->getFont()->setSize($tamanioFecha)->setName($fuente_encabezado);
			$hojaReporte->getStyle("A3:G3")->getFont()->setSize($tamanioFecha)->setName($fuente_encabezado);
			//centrar el texto de la fecha
			$hojaReporte->getStyle("A1:G1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$hojaReporte->getStyle("A3:G3")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

			//hacer grande las letras del encabezado
			$hojaReporte->getStyle("A3:G3")->getFont()->setSize($tamanioEncabezado)->setName($fuente_encabezado);
			$hojaReporte->getStyle("A4:G4")->getFont()->setSize($tamanioEncabezado)->setName($fuente_encabezado);
			$hojaReporte->getStyle("A6:G6")->getFont()->setSize($tamanioEncabezado)->setName($fuente_encabezado);
			//centrar el texto del encabezado
			$hojaReporte->getStyle("A4:G4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$hojaReporte->getStyle("A6:G6")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

			//hacer pequeño las letras del encabezado de titulo
			$hojaReporte->getStyle("A4:G4")->getFont()->setSize($tamanioTabla)->setName($fuente);
			$hojaReporte->getStyle("A6:G6")->getFont()->setSize($tamanioTabla)->setName($fuente);
			//centrar los encabezado de la tabla
			$hojaReporte->getStyle("A4:G4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$hojaReporte->getStyle("A6:G6")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

			$hojaReporte->mergeCells('A1:G1');
			$hojaReporte->mergeCells('A3:G3');
			$hojaReporte->mergeCells('A4:G4');

			# Escribir encabezado de la tabla
			$encabezado_tabla = ["No.", "NOMBRE CLIENTE", "FECHA OPERACIÓN", "TRANSACCIÓN", "MONTO", "SERVICIO/PRODUCTO", "RAZÓN"];


			# El último argumento es por defecto A1 pero lo pongo para que se explique mejor
			$hojaReporte->fromArray($encabezado_tabla, null, 'A6')->getStyle('A6:G6')->getFont()->setName($fuente);

			$contador = 7;
			//ingresar la primera linea
			$hojaReporte->getStyle("E" . $contador)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_GT_SIMPLE);
			$hojaReporte->setCellValueByColumnAndRow(1, $contador, 1);
			$hojaReporte->setCellValueByColumnAndRow(2, $contador, " ");
			$hojaReporte->setCellValueByColumnAndRow(3, $contador, utf8_decode($data[0]['fecha']));
			$hojaReporte->setCellValueByColumnAndRow(4, $contador, " ");
			$hojaReporte->setCellValueByColumnAndRow(5, $contador, ($data[0]['monto']));
			$hojaReporte->setCellValueByColumnAndRow(6, $contador, " ");
			$hojaReporte->setCellValueByColumnAndRow(7, $contador, utf8_decode("SALDO ANTERIOR A LA FECHA INICIAL"));
			$contador++;
			//ingresar los demas registros
			$aux = "";
			for ($i = 1; $i < count($data); $i++) {
				$hojaReporte->getStyle("D" . $contador)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
				$hojaReporte->getStyle("E" . $contador)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_GT_SIMPLE);

				if ($aux == $data[$i]['idcliente']) {
					$hojaReporte->setCellValueByColumnAndRow(1, $contador, ($i + 1));
					$hojaReporte->setCellValueByColumnAndRow(2, $contador, " ");
					$hojaReporte->setCellValueByColumnAndRow(3, $contador, utf8_decode($data[0]['fecha']));
					$hojaReporte->setCellValueByColumnAndRow(4, $contador, utf8_decode($data[$i]['transaccion']));
					$hojaReporte->setCellValueByColumnAndRow(5, $contador, ($data[$i]['monto']));
					$hojaReporte->setCellValueByColumnAndRow(6, $contador, utf8_decode($data[$i]['servicio']));
					$hojaReporte->setCellValueByColumnAndRow(7, $contador, utf8_decode($data[$i]['razon']));
				}
				if ($aux != $data[$i]['idcliente']) {
					$aux = $data[$i]['idcliente'];

					$hojaReporte->setCellValueByColumnAndRow(1, $contador, ($i + 1));
					$hojaReporte->setCellValueByColumnAndRow(2, $contador, strtoupper(utf8_decode($data[$i]['nombre'])));
					$hojaReporte->setCellValueByColumnAndRow(3, $contador, utf8_decode($data[0]['fecha']));
					$hojaReporte->setCellValueByColumnAndRow(4, $contador, utf8_decode($data[$i]['transaccion']));
					$hojaReporte->setCellValueByColumnAndRow(5, $contador, $data[$i]['monto']);
					$hojaReporte->setCellValueByColumnAndRow(6, $contador, utf8_decode($data[$i]['servicio']));
					$hojaReporte->setCellValueByColumnAndRow(7, $contador, utf8_decode($data[$i]['razon']));
				}
				$contador++;
			}
			//suma de totales
			$sumamonto = array_sum(array_column($data, 'monto'));
			//imprimir total
			$hojaReporte->getStyle('A' . $contador . ':G' . $contador)->getFont()->setSize($tamanioTabla)->setName($fuente);
			$hojaReporte->getStyle('A' . $contador . ':G' . $contador)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

			$hojaReporte->getStyle("E" . $contador)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_GT_SIMPLE);
			$hojaReporte->setCellValueByColumnAndRow(1, $contador, "TOTAL MONTO");
			$hojaReporte->setCellValueByColumnAndRow(5, $contador, $sumamonto);
			//merge
			$hojaReporte->mergeCells('A' . $contador . ':D' . $contador);
			//autosize
			$hojaReporte->getColumnDimension('A')->setAutoSize(TRUE);
			$hojaReporte->getColumnDimension('B')->setAutoSize(TRUE);
			$hojaReporte->getColumnDimension('C')->setAutoSize(TRUE);
			$hojaReporte->getColumnDimension('D')->setAutoSize(TRUE);
			$hojaReporte->getColumnDimension('E')->setAutoSize(TRUE);
			$hojaReporte->getColumnDimension('F')->setAutoSize(TRUE);
			$hojaReporte->getColumnDimension('G')->setAutoSize(TRUE);
			//opcion de envio de datos---------------------------------------------------------
			ob_start();
			$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			$writer->save("php://output");
			$xlsData = ob_get_contents();
			ob_end_clean();
			//envio de repuesta a ajax para descargarlos
			$opResult = array(
				'status' => 1,
				'mensaje' => 'Reporte generado correctamente',
				'namefile' => "Reporte_ingresos_" . date('d-m-Y'),
				'data' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
			);
			echo json_encode($opResult);
			exit;
		}
		// echo "<pre>";
		// print_r($data);
		// echo "</pre>";
		// $aux = count($datos['datos']);

		// echo json_encode($data);
	}

	public function reportesservicios()
	{
		if (!$_POST) {
			return redirect()->to(base_url('reportes'));
			exit;
		}
		$tipo = $_POST['reporteser'];
		$hoy = date("Y-m-d H:i:s");
		$hoy_archivo = date("d-m-Y");

		$fuente_encabezado = "Arial";
		$fuente = "Courier";
		$tamanioFecha = 9; //tamaño de letra de la fecha y usuario
		$tamanioEncabezado = 14; //tamaño de letra del encabezado
		$tamanioTabla = 11; //tamaño de letra de la fecha y usuario


		$spreadsheet = new Spreadsheet();
		$spreadsheet
			->getProperties()
			->setCreator("SOTECPRO")
			->setLastModifiedBy('SOTECPRO')
			->setTitle('Reporte')
			->setSubject('Listado de servicios')
			->setDescription('Reporte generado por sotecclisers')
			->setKeywords('PHPSpreadsheet')
			->setCategory('Excel');

		# Como ya hay una hoja por defecto, la obtenemos, no la creamos
		$hojaReporte = $spreadsheet->getActiveSheet();
		$hojaReporte->setTitle("Reporte de servicios");

		//insertarmos la fecha y usuario
		$hojaReporte->setCellValue("A1", $hoy);
		$hojaReporte->setCellValue("A3", 'CLIENTES DE SOTECPRO');
		$textoreporte = "Listado de todos los servicios";
		$textoarchivo = "Reporte de clientes - ";
		if ($tipo == '2') {
			$textoreporte = "Listado de servicios activos";
			$textoarchivo = "Reporte de servicios activos - ";
		}
		if ($tipo == '3') {
			$textoreporte = "Listado de servicios inactivos";
			$textoarchivo = "Reporte de servicios inactivos - ";
		}
		$hojaReporte->setCellValue("A4", $textoreporte);
		//hacer pequeño las letras de la fecha, definir arial como tipo de letra
		$hojaReporte->getStyle("A1:F1")->getFont()->setSize($tamanioFecha)->setName($fuente_encabezado);
		$hojaReporte->getStyle("A3:F3")->getFont()->setSize($tamanioFecha)->setName($fuente_encabezado);
		//centrar el texto de la fecha
		$hojaReporte->getStyle("A1:F1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$hojaReporte->getStyle("A3:F3")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		//hacer grande las letras del encabezado
		$hojaReporte->getStyle("A3:F3")->getFont()->setSize($tamanioEncabezado)->setName($fuente_encabezado);
		$hojaReporte->getStyle("A4:F4")->getFont()->setSize($tamanioEncabezado)->setName($fuente_encabezado);
		$hojaReporte->getStyle("A6:F6")->getFont()->setSize($tamanioEncabezado)->setName($fuente_encabezado);
		//centrar el texto del encabezado
		$hojaReporte->getStyle("A4:F4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$hojaReporte->getStyle("A6:F6")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		//hacer pequeño las letras del encabezado de titulo
		$hojaReporte->getStyle("A4:F4")->getFont()->setSize($tamanioTabla)->setName($fuente);
		$hojaReporte->getStyle("A6:F6")->getFont()->setSize($tamanioTabla)->setName($fuente);
		//centrar los encabezado de la tabla
		$hojaReporte->getStyle("A4:F4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$hojaReporte->getStyle("A6:F6")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		$hojaReporte->mergeCells('A1:F1');
		$hojaReporte->mergeCells('A3:F3');
		$hojaReporte->mergeCells('A4:F4');

		# Escribir encabezado de la tabla
		$encabezado_tabla = ["No.", "NOMBRE SERVICIO", "DESCRIPCIÓN", "CREATED_AT", "UPDATED_AT", "DELETED_AT"];


		# El último argumento es por defecto A1 pero lo pongo para que se explique mejor
		$hojaReporte->fromArray($encabezado_tabla, null, 'A6')->getStyle('A6:F6')->getFont()->setName($fuente);
		//ingreso de los datos de tabla
		$contador = 7;
		//REALIZAR LA CONSULTA
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_servicios ser');
		$builder->select('*');
		if ($tipo == '2') {
			$builder->where('ser.estado', '1');
		}
		if ($tipo == '3') {
			$builder->where('ser.estado', '0');
		}
		$builder->orderBy('ser.id', 'ASC');
		$datos = $builder->get()->getResultArray();
		$numero = 1;
		foreach ($datos as $dato) {
			$hojaReporte->setCellValueByColumnAndRow(1, $contador, $numero);
			$hojaReporte->setCellValueByColumnAndRow(2, $contador, $dato['nombre']);
			$hojaReporte->setCellValueByColumnAndRow(3, $contador, $dato['descripcion']);
			$hojaReporte->setCellValueByColumnAndRow(4, $contador, $dato['created_at']);
			$hojaReporte->setCellValueByColumnAndRow(5, $contador, $dato['updated_at']);
			$hojaReporte->setCellValueByColumnAndRow(6, $contador, $dato['deleted_at']);
			$contador++;
			$numero++;
		};
		//redimensionar celdas
		$hojaReporte->getColumnDimension('A')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('B')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('C')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('D')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('E')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('F')->setAutoSize(TRUE);

		//opcion de envio de datos---------------------------------------------------------
		ob_start();
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save("php://output");
		$xlsData = ob_get_contents();
		ob_end_clean();
		//envio de repuesta a ajax para descargarlos
		$opResult = array(
			'status' => 1,
			'namefile' => $textoarchivo . $hoy_archivo,
			'mensaje' => $textoarchivo . ' generado correctamente',
			'data' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
		);
		echo json_encode($opResult);
		exit;
		//-----------------------------------------------------------------------------------

		echo json_encode('holis');
	}

	public function reportefactura()
	{
		if (!$_POST) {
			return redirect()->to(base_url('inicio'));
			exit;
		}
		$id = $_POST['id'];
		$fuente = "Courier";
		$tamanio_linea = 4; //altura de la linea/celda
		$ancho_linea = 30; //anchura de la linea/celda

		//TRAYENDO LOS DATOS

		//Consultando al cliente y su informacion
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_factura ft');
		$builder->select('cl.id id_cliente, cl.nombre, cl.nomcom, cl.nit, cl.direccion, cl.telefono, cl.fecha, dep.nombre departamento, ft.transaccion, ft.id id_factura');
		$builder->join('tb_clientes cl', 'ft.id_cliente = cl.id');
		$builder->join('tb_departamento dep', 'cl.id_departamento=dep.id');
		$builder->where('cl.estado', '1');
		$builder->where('ft.estado', '1');
		$builder->where('ft.id', $id);
		$dataen['enc'] = $builder->get()->getResultArray();
		//Consultando los detalles de la factura
		$db2      = \Config\Database::connect();
		$builder2 = $db2->table('tb_detalle det');
		$builder2->select('det.id, det.id_servicio, ser.nombre servicio, det.fechai, det.fechaf, det.monto, det.abono, det.cuota, det.observaciones, det.estadod estado');
		$builder2->join('tb_factura ft', 'det.id_factura = ft.id');
		$builder2->join('tb_servicios ser', 'det.id_servicio = ser.id');
		$builder2->where('ft.estado', '1');
		$builder2->where('det.estado', '1');
		$builder2->where('ser.estado', '1');
		$builder2->where('ft.id', $id);
		$datadet['det'] = $builder2->get()->getResultArray();

		$pdf = new PDF1();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		//ACA DE SE VAN A CARGAR LOS DATOS
		$pdf->SetFont($fuente, 'B', 12);
		//IMPRESION DEL ENCABEZADO
		//COMPROBANTE
		$pdf->Cell(0, 3, 'COMPROBANTE', 0, 1, 'C');
		$pdf->Ln(7);
		//CODIGO CLIENTE Y NOMBRE DE CLIENTE Y NO FACTURA:
		//Codigo cliente
		$auxtext="";
		$pdf->SetFont($fuente, '', 10);
		$pdf->CellFit($ancho_linea, $tamanio_linea + 1, strtoupper(utf8_decode('COD. CLIENTE:')), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 15, $tamanio_linea + 1, strtoupper(utf8_decode($dataen['enc'][0]['id_cliente'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 10, $tamanio_linea + 1, strtoupper(utf8_decode('CLIENTE:')), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea + 40, $tamanio_linea + 1, strtoupper(utf8_decode($dataen['enc'][0]['nombre'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea, $tamanio_linea + 1, strtoupper(utf8_decode('FACTURA No.:')), 0, 0, 'R', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, strtoupper(utf8_decode($dataen['enc'][0]['transaccion'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->Ln(7);
		$pdf->CellFit($ancho_linea - 18, $tamanio_linea + 1, strtoupper(utf8_decode('NIT:')), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 4, $tamanio_linea + 1, strtoupper(utf8_decode($dataen['enc'][0]['nit'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea + 3, $tamanio_linea + 1, strtoupper(utf8_decode('NOMBRE COMERCIAL:')), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea + 34, $tamanio_linea + 1, strtoupper(utf8_decode($dataen['enc'][0]['nomcom'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea, $tamanio_linea + 1, strtoupper(utf8_decode('FECHA INICIO:')), 0, 0, 'R', 0, '', 1, 0);
		($dataen['enc'][0]['fecha']==null || $dataen['enc'][0]['fecha']=="")?($auxtext=" "):($auxtext=$dataen['enc'][0]['fecha']);
		$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, strtoupper(utf8_decode($auxtext)), 0, 0, 'L', 0, '', 1, 0);
		$pdf->Ln(7);
		$pdf->CellFit($ancho_linea - 7, $tamanio_linea + 1, strtoupper(utf8_decode('DIRECCIÓN:')), 0, 0, 'L', 0, '', 1, 0);
		($dataen['enc'][0]['direccion']==null || $dataen['enc'][0]['direccion']=="")?($auxtext=" "):($auxtext=$dataen['enc'][0]['direccion']);
		$pdf->CellFit($ancho_linea + 137, $tamanio_linea + 1, strtoupper(utf8_decode($auxtext)), 0, 0, 'L', 0, '', 1, 0); //aqui hay un error
		$pdf->Ln(7);
		$pdf->CellFit($ancho_linea, $tamanio_linea + 1, strtoupper(utf8_decode('DEPARTAMENTO:')), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea + 50, $tamanio_linea + 1, strtoupper(utf8_decode($dataen['enc'][0]['departamento'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea, $tamanio_linea + 1, strtoupper(utf8_decode('TELEFONO:')), 0, 0, 'R', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea + 20, $tamanio_linea + 1, strtoupper(utf8_decode($dataen['enc'][0]['telefono'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->Ln(6);
		$pdf->Cell(0, 0, '', 1, 1, 'C');
		$pdf->Ln(5);
		$pdf->SetFont($fuente, 'B', 10);
		// IMPRESION DE LOS DETALLES DE LA FACTURA
		$pdf->CellFit($ancho_linea - 19, $tamanio_linea + 1, strtoupper(utf8_decode('No.')), 'B', 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, strtoupper(utf8_decode('FECH. INICIO')), 'B', 0, 'C', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 6, $tamanio_linea + 1, strtoupper(utf8_decode('FECH. FINAL')), 'B', 0, 'C', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, strtoupper(utf8_decode('MONTO')), 'B', 0, 'C', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, strtoupper(utf8_decode('ABONO')), 'B', 0, 'C', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, strtoupper(utf8_decode('SALDO')), 'B', 0, 'C', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, strtoupper(utf8_decode('CUOTA')), 'B', 0, 'C', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea, $tamanio_linea + 1, strtoupper(utf8_decode('ESTADO')), 'B', 0, 'C', 0, '', 1, 0);
		$pdf->Ln(6);
		$pdf->SetFont($fuente, '', 8);
		$j = 1;
		$textestado = "";
		$sumamonto = 0;
		$sumaabono = 0;
		$sumasaldo = 0;
		$sumacuota = 0;
		foreach ($datadet['det'] as $dato) {
			$sumamonto = $sumamonto + $dato['monto'];
			$sumaabono = $sumaabono + $dato['abono'];
			$sumasaldo = $sumasaldo + ($dato['monto'] - $dato['abono']);
			$sumacuota = $sumacuota + $dato['cuota'];
			$pdf->CellFit($ancho_linea - 19, $tamanio_linea + 1, strtoupper(utf8_decode($j)), 0, 0, 'L', 0, '', 1, 0);
			$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, strtoupper(utf8_decode($dato['fechai'])), 0, 0, 'C', 0, '', 1, 0);
			$pdf->CellFit($ancho_linea - 6, $tamanio_linea + 1, strtoupper(utf8_decode($dato['fechaf'])), 0, 0, 'C', 0, '', 1, 0);
			$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, utf8_decode(number_format($dato['monto'], 2)), 0, 0, 'R', 0, '', 1, 0);
			$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, utf8_decode(number_format($dato['abono'], 2)), 0, 0, 'R', 0, '', 1, 0);
			$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, utf8_decode(number_format(($dato['monto'] - $dato['abono']), 2)), 0, 0, 'R', 0, '', 1, 0);
			$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, utf8_decode(number_format($dato['cuota'], 2)), 0, 0, 'R', 0, '', 1, 0);
			$dato['estado'] == 1 ? $textestado = "ACTIVO" : $textestado = "INACTIVO";
			$pdf->CellFit($ancho_linea, $tamanio_linea + 1, strtoupper(utf8_decode($textestado)), 0, 0, 'C', 0, '', 1, 0);
			$pdf->Ln(5);
			$j++;
		}
		$pdf->Ln(1);
		$pdf->Cell(0, 0, '', 1, 1, 'C');
		$pdf->SetFont($fuente, 'B', 8);
		$pdf->CellFit($ancho_linea + 30, $tamanio_linea + 1, strtoupper(utf8_decode('TOTALES')), 0, 0, 'C', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, utf8_decode(number_format($sumamonto, 2)), 0, 0, 'R', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, utf8_decode(number_format($sumaabono, 2)), 0, 0, 'R', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, utf8_decode(number_format($sumasaldo, 2)), 0, 0, 'R', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, utf8_decode(number_format($sumacuota, 2)), 0, 0, 'R', 0, '', 1, 0);

		ob_start();
		$pdf->Output();
		$pdfData = ob_get_contents();
		ob_end_clean();

		$opResult = array(
			'status' => 1,
			'mensaje' => 'Reporte generado correctamente',
			'namefile' => "Factura_no_" . date('d-m-Y'),
			'data' => "data:application/vnd.ms-word;base64," . base64_encode($pdfData)
		);
		echo json_encode($opResult);
		// echo '<pre>';
		// echo print_r($dataen);
		// echo '</pre>';
	}

	public function reportepago()
	{
		if (!$_POST) {
			return redirect()->to(base_url('pagos'));
			exit;
		}
		$id = $_POST['id'];
		$fuente = "Courier";
		$tamanio_linea = 4; //altura de la linea/celda
		$ancho_linea = 30; //anchura de la linea/celda

		//TRAYENDO LOS DATOS

		//Consultando al cliente y su informacion
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_detalle det');

		$builder->select('pg.id, det.id id_detalle, ser.id id_servicio, cl.id id_cliente, cl.nombre, cl.nomcom, cl.nit, cl.telefono, cl.fecha, cl.direccion, dep.nombre departamento, ft.transaccion, pg.fecha fecha_pago, pg.numdoc, ser.nombre servicio, pg.monto');
		$builder->join('tb_factura ft', 'det.id_factura = ft.id');
		$builder->join('tb_servicios ser', 'det.id_servicio = ser.id');
		$builder->join('tb_clientes cl', 'ft.id_cliente = cl.id');
		$builder->join('tb_departamento dep', 'cl.id_departamento=dep.id');
		$builder->join('tb_pago pg', 'pg.id_detalle=det.id');
		$builder->where('ft.estado', '1');
		$builder->where('det.estado', '1');
		$builder->where('pg.estado', '1');
		$builder->where('pg.id', $id);
		$data['data'] = $builder->get()->getResultArray();

		$pdf = new PDF1();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		//ACA DE SE VAN A CARGAR LOS DATOS
		$pdf->SetFont($fuente, 'B', 12);
		//IMPRESION DEL ENCABEZADO
		//COMPROBANTE
		$pdf->Cell(0, 3, 'BOLETA DE PAGO', 0, 1, 'C');
		$pdf->Ln(8);
		//CODIGO CLIENTE Y NOMBRE DE CLIENTE Y NO FACTURA:
		//Codigo cliente
		$auxtext="";
		$pdf->SetFont($fuente, '', 10);
		$pdf->CellFit($ancho_linea, $tamanio_linea + 1, strtoupper(utf8_decode('COD. CLIENTE:')), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 15, $tamanio_linea + 1, strtoupper(utf8_decode($data['data'][0]['id_cliente'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 10, $tamanio_linea + 1, strtoupper(utf8_decode('CLIENTE:')), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea + 40, $tamanio_linea + 1, strtoupper(utf8_decode($data['data'][0]['nombre'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea, $tamanio_linea + 1, strtoupper(utf8_decode('FACTURA No.:')), 0, 0, 'R', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, strtoupper(utf8_decode($data['data'][0]['transaccion'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->Ln(7);
		$pdf->CellFit($ancho_linea - 18, $tamanio_linea + 1, strtoupper(utf8_decode('NIT:')), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea - 4, $tamanio_linea + 1, strtoupper(utf8_decode($data['data'][0]['nit'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea + 3, $tamanio_linea + 1, strtoupper(utf8_decode('NOMBRE COMERCIAL:')), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea + 34, $tamanio_linea + 1, strtoupper(utf8_decode($data['data'][0]['nomcom'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea, $tamanio_linea + 1, strtoupper(utf8_decode('FECHA INICIO:')), 0, 0, 'R', 0, '', 1, 0);
		($data['data'][0]['fecha']==null || $data['data'][0]['fecha']=="")?($auxtext=" "):($auxtext=$data['data'][0]['fecha']);
		$pdf->CellFit($ancho_linea - 5, $tamanio_linea + 1, strtoupper(utf8_decode($auxtext)), 0, 0, 'L', 0, '', 1, 0);
		$pdf->Ln(7);
		$pdf->CellFit($ancho_linea - 7, $tamanio_linea + 1, strtoupper(utf8_decode('DIRECCIÓN:')), 0, 0, 'L', 0, '', 1, 0);
		($data['data'][0]['direccion']==null || $data['data'][0]['direccion']=="")?($auxtext=" "):($auxtext=$data['data'][0]['direccion']);
		$pdf->CellFit($ancho_linea + 137, $tamanio_linea + 1, strtoupper(utf8_decode($auxtext)), 0, 0, 'L', 0, '', 1, 0);
		$pdf->Ln(7);
		$pdf->CellFit($ancho_linea, $tamanio_linea + 1, strtoupper(utf8_decode('DEPARTAMENTO:')), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea + 50, $tamanio_linea + 1, strtoupper(utf8_decode($data['data'][0]['departamento'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea, $tamanio_linea + 1, strtoupper(utf8_decode('TELEFONO:')), 0, 0, 'R', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea + 20, $tamanio_linea + 1, strtoupper(utf8_decode($data['data'][0]['telefono'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->Ln(6);
		$pdf->Cell(0, 0, '', 1, 1, 'C');
		$pdf->Ln(5);
		$pdf->SetFont($fuente, 'B', 12);
		//IMPRESION DEL ENCABEZADO
		//COMPROBANTE
		$pdf->Cell(0, 3, 'DETALLE DE PAGO', 0, 1, 'C');
		$pdf->Ln(7);
		$pdf->SetFont($fuente, '', 10);
		//IMPRESION DE LOS DATOS DE LA BOLETA DE PAGO
		//fecha
		$pdf->CellFit($ancho_linea+5, $tamanio_linea + 1, strtoupper(utf8_decode('FECHA DE PAGO: ')), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea +125, $tamanio_linea + 1, strtoupper(utf8_decode($data['data'][0]['fecha_pago'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->Ln(5);
		//numero de boleta
		$pdf->CellFit($ancho_linea+9, $tamanio_linea + 1, strtoupper(utf8_decode('NÚMERO DE BOLETA: ')), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea +121, $tamanio_linea + 1, strtoupper(utf8_decode($data['data'][0]['numdoc'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->Ln(5);
		//servicio
		$pdf->CellFit($ancho_linea+12, $tamanio_linea + 1, strtoupper(utf8_decode('SERVICIO/PRODUCTO: ')), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea +118, $tamanio_linea + 1, strtoupper(utf8_decode($data['data'][0]['servicio'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->Ln(5);
		//monto
		$pdf->CellFit($ancho_linea+8, $tamanio_linea + 1, strtoupper(utf8_decode('MONTO PAGADO: ')), 0, 0, 'L', 0, '', 1, 0);
		$pdf->CellFit($ancho_linea +122, $tamanio_linea + 1, strtoupper(utf8_decode($data['data'][0]['monto'])), 0, 0, 'L', 0, '', 1, 0);
		$pdf->Ln(5);

		ob_start();
		$pdf->Output();
		$pdfData = ob_get_contents();
		ob_end_clean();

		$opResult = array(
			'status' => 1,
			'mensaje' => 'Reporte generado correctamente',
			'namefile' => "Pago_".$data['data'][0]['numdoc']."_" . date('d-m-Y'),
			'data' => "data:application/vnd.ms-word;base64," . base64_encode($pdfData)
		);
		echo json_encode($opResult);

		// echo '<pre>';
		// echo print_r($data);
		// echo '</pre>';
	}

	public function reportespagos()
	{
		if (!$_POST) {
			return redirect()->to(base_url('reportes'));
			exit;
		}
		$tipo = $_POST['reportepago'];
		$hoy = date("Y-m-d H:i:s");
		$hoy_archivo = date("d-m-Y");

		$fuente_encabezado = "Arial";
		$fuente = "Courier";
		$tamanioFecha = 9; //tamaño de letra de la fecha y usuario
		$tamanioEncabezado = 14; //tamaño de letra del encabezado
		$tamanioTabla = 11; //tamaño de letra de la fecha y usuario


		$spreadsheet = new Spreadsheet();
		$spreadsheet
			->getProperties()
			->setCreator("SOTECPRO")
			->setLastModifiedBy('SOTECPRO')
			->setTitle('Reporte')
			->setSubject('Listado de pagos')
			->setDescription('Reporte generado por sotecclisers')
			->setKeywords('PHPSpreadsheet')
			->setCategory('Excel');

		# Como ya hay una hoja por defecto, la obtenemos, no la creamos
		$hojaReporte = $spreadsheet->getActiveSheet();
		$hojaReporte->setTitle("Reporte de servicios");

		//insertarmos la fecha y usuario
		$hojaReporte->setCellValue("A1", $hoy);
		$hojaReporte->setCellValue("A3", 'PAGOS EN SOTECPRO');
		$textoreporte = "Listado de todos los pagos";
		$textoarchivo = "Reporte de pagos - ";
		if ($tipo == '2') {
			$textoreporte = "Listado de pagos activos";
			$textoarchivo = "Reporte de pagos activos - ";
		}
		if ($tipo == '3') {
			$textoreporte = "Listado de pagos eliminados";
			$textoarchivo = "Reporte de pagos eliminados - ";
		}
		$hojaReporte->setCellValue("A4", $textoreporte);
		//hacer pequeño las letras de la fecha, definir arial como tipo de letra
		$hojaReporte->getStyle("A1:K1")->getFont()->setSize($tamanioFecha)->setName($fuente_encabezado);
		$hojaReporte->getStyle("A3:K3")->getFont()->setSize($tamanioFecha)->setName($fuente_encabezado);
		//centrar el texto de la fecha
		$hojaReporte->getStyle("A1:K1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$hojaReporte->getStyle("A3:K3")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		//hacer grande las letras del encabezado
		$hojaReporte->getStyle("A3:K3")->getFont()->setSize($tamanioEncabezado)->setName($fuente_encabezado);
		$hojaReporte->getStyle("A4:K4")->getFont()->setSize($tamanioEncabezado)->setName($fuente_encabezado);
		$hojaReporte->getStyle("A6:K6")->getFont()->setSize($tamanioEncabezado)->setName($fuente_encabezado);
		//centrar el texto del encabezado
		$hojaReporte->getStyle("A4:K4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$hojaReporte->getStyle("A6:K6")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		//hacer pequeño las letras del encabezado de titulo
		$hojaReporte->getStyle("A4:K4")->getFont()->setSize($tamanioTabla)->setName($fuente);
		$hojaReporte->getStyle("A6:K6")->getFont()->setSize($tamanioTabla)->setName($fuente);
		//centrar los encabezado de la tabla
		$hojaReporte->getStyle("A4:K4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$hojaReporte->getStyle("A6:K6")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		$hojaReporte->mergeCells('A1:K1');
		$hojaReporte->mergeCells('A3:K3');
		$hojaReporte->mergeCells('A4:K4');

		# Escribir encabezado de la tabla
		$encabezado_tabla = ["No.", "CLIENTE", "NOMBRE COMERCIAL", "FECHA PAGO", "NÚMERO DE BOLETA", "SERVICIO/PRODUCTO","MONTO", "ESTADO","CREATED_AT", "UPDATED_AT", "DELETED_AT"];


		# El último argumento es por defecto A1 pero lo pongo para que se explique mejor
		$hojaReporte->fromArray($encabezado_tabla, null, 'A6')->getStyle('A6:K6')->getFont()->setName($fuente);
		//ingreso de los datos de tabla
		$contador = 7;
		//REALIZAR LA CONSULTA
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_detalle det');
		$builder->select('pg.id, cl.nombre, cl.nomcom, ft.transaccion, pg.fecha fecha_pago, pg.numdoc, ser.nombre servicio, pg.monto, pg.created_at, pg.updated_at, pg.deleted_at');
		$builder->join('tb_factura ft', 'det.id_factura = ft.id');
		$builder->join('tb_servicios ser', 'det.id_servicio = ser.id');
		$builder->join('tb_clientes cl', 'ft.id_cliente = cl.id');
		$builder->join('tb_departamento dep', 'cl.id_departamento=dep.id');
		$builder->join('tb_pago pg', 'pg.id_detalle=det.id');
		$builder->where('ft.estado', '1');
		$builder->where('det.estado', '1');
		if ($tipo == '2') {
			$builder->where('pg.estado', '1');
		}
		if ($tipo == '3') {
			$builder->where('pg.estado', '0');
		}
		$builder->orderBy('pg.fecha', 'DESC');
		$datos = $builder->get()->getResultArray();
		$numero = 1;
		$texto_estado="";
		foreach ($datos as $dato) {
			$hojaReporte->getStyle("G" . $contador)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_GT_SIMPLE);

			$hojaReporte->setCellValueByColumnAndRow(1, $contador, $numero);
			$hojaReporte->setCellValueByColumnAndRow(2, $contador, $dato['nombre']);
			$hojaReporte->setCellValueByColumnAndRow(3, $contador, $dato['nomcom']);
			$hojaReporte->setCellValueByColumnAndRow(4, $contador, $dato['fecha_pago']);
			$hojaReporte->setCellValueByColumnAndRow(5, $contador, $dato['numdoc']);
			$hojaReporte->setCellValueByColumnAndRow(6, $contador, $dato['servicio']);
			$hojaReporte->setCellValueByColumnAndRow(7, $contador, $dato['monto']);
			$dato['monto']==1 ? $texto_estado="ACTIVO" : $texto_estado="ELIMINADO";
			$hojaReporte->setCellValueByColumnAndRow(8, $contador, $texto_estado);
			$hojaReporte->setCellValueByColumnAndRow(9, $contador, $dato['created_at']);
			$hojaReporte->setCellValueByColumnAndRow(10, $contador, $dato['updated_at']);
			$hojaReporte->setCellValueByColumnAndRow(11, $contador, $dato['deleted_at']);
			$contador++;
			$numero++;
		};
		//COLOCAR TOTAL
		//suma de totales
		$sumamonto = array_sum(array_column($datos, 'monto'));
		//imprimir total
		$hojaReporte->getStyle('A' . $contador . ':K' . $contador)->getFont()->setSize($tamanioTabla)->setName($fuente);
		$hojaReporte->getStyle('A' . $contador . ':K' . $contador)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

		$hojaReporte->getStyle("G" . $contador)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_GT_SIMPLE);
		$hojaReporte->setCellValueByColumnAndRow(1, $contador, "TOTAL MONTO");
		$hojaReporte->setCellValueByColumnAndRow(7, $contador, $sumamonto);
		//merge
		$hojaReporte->mergeCells('A' . $contador . ':F' . $contador);

		//redimensionar celdas
		$hojaReporte->getColumnDimension('A')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('B')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('C')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('D')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('E')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('F')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('G')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('H')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('I')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('J')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('J')->setAutoSize(TRUE);
		$hojaReporte->getColumnDimension('G')->setAutoSize(TRUE);

		//opcion de envio de datos---------------------------------------------------------
		ob_start();
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save("php://output");
		$xlsData = ob_get_contents();
		ob_end_clean();
		//envio de repuesta a ajax para descargarlos
		$opResult = array(
			'status' => 1,
			'namefile' => $textoarchivo . $hoy_archivo,
			'mensaje' => $textoarchivo . ' generado correctamente',
			'data' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
		);
		echo json_encode($opResult);
		exit;
		//-----------------------------------------------------------------------------------

		echo json_encode('holis');
	}
}

class PDF extends FPDF
{
	// atributos de la clase
	public $titulo;

	public function __construct($titulo)
	{
		parent::__construct();
		$this->titulo = $titulo;
		$this->DefOrientation = 'L';
	}

	public function Header()
	{
		$fuente = "Courier";
		$tamanio_linea = 4; //altura de la linea/celda
		$ancho_linea = 30; //anchura de la linea/celda

		$institucion = "Soluciones Tecnológicas Profesionales";
		$abreviatura = "SOTECPRO";
		$direccion = "16 Av. 1-09 zona 1. Cobán, Alta Verapaz, Guatemala";
		$email = "sotecpro1@gmail.com";
		$telefono = "0000-0000";
		$nit = "00-00-00-000";
		$pathlogo1 = base_url('img/logosoctecpro.png');

		// ACA ES DONDE EMPIEZA LO DEL FORMATO DE REPORTE---------------------------------------------------
		$hoy = date("Y-m-d H:i:s");
		//fecha y usuario que genero el reporte
		$this->SetFont('Arial', '', 7);
		$this->Cell(0, 2, $hoy, 0, 1, 'R');
		$this->Ln(2);
		// Logo de la agencia
		$this->Image($pathlogo1, 10, 10, 25);

		//tipo de letra para el encabezado
		$this->SetFont('Arial', '', 8);
		// Título
		$this->Cell(0, 3, utf8_decode($institucion), 0, 1, 'C');
		$this->Cell(0, 3, $abreviatura, 0, 1, 'C');
		$this->Cell(0, 3, utf8_decode($direccion), 0, 1, 'C');
		$this->Cell(0, 3, 'Email: ' . $email, 0, 1, 'C');
		$this->Cell(0, 3, 'Tel: ' . $telefono, 0, 1, 'C');
		$this->Cell(0, 3, 'NIT: ' . $nit, 0, 1, 'C');
		// Salto de línea
		$this->Ln(3);

		$this->SetFont($fuente, '', 10);
		//SECCION DE DATOS DEL CLIENTE
		//TITULO DE REPORTE
		$this->SetFillColor(255, 255, 255);
		$this->MultiCell(0, 5,  strtoupper(utf8_decode($this->titulo)), 0, 'C', true);
		$this->Ln(5);
		//Fuente
		$this->SetFont($fuente, 'B', 8);
		//encabezado de tabla
		$this->CellFit($ancho_linea - 15, $tamanio_linea + 1, utf8_decode("No"), 1, 0, 'C', 0, '', 1, 0);
		$this->CellFit($ancho_linea + 37, $tamanio_linea + 1, utf8_decode("NOMBRE CLIENTE"), 1, 0, 'C', 0, '', 1, 0);
		$this->CellFit($ancho_linea - 3, $tamanio_linea + 1, utf8_decode("FECHA OPERACIÓN"), 1, 0, 'C', 0, '', 1, 0);
		$this->CellFit($ancho_linea - 5, $tamanio_linea + 1, utf8_decode("TRANSACCIÓN"), 1, 0, 'C', 0, '', 1, 0);
		$this->CellFit($ancho_linea + 12, $tamanio_linea + 1, utf8_decode("MONTO"), 1, 0, 'C', 0, '', 1, 0);
		$this->CellFit($ancho_linea + 20, $tamanio_linea + 1, utf8_decode("SERVICIO/PRODUCTO"), 1, 0, 'C', 0, '', 1, 0);
		$this->CellFit($ancho_linea + 21, $tamanio_linea + 1, utf8_decode("RAZÓN"), 1, 0, 'C', 0, '', 1, 0);
		$this->Ln(7);
	}
	function Footer()
	{
		$pathlogo = base_url('img/logosoctecpro.png');
		// Posición: a 1 cm del final
		$this->SetY(-15);
		// Logo
		$this->Image($pathlogo, 190, 279, 15);
		// Arial italic 8
		$this->SetFont('Arial', 'I', 8);
		// Número de página
		$this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
	}
}

class PDF1 extends FPDF
{
	public function __construct()
	{
		parent::__construct();
		$this->DefOrientation = 'P';
	}

	public function Header()
	{
		$fuente = "Courier";
		$tamanio_linea = 4; //altura de la linea/celda
		$ancho_linea = 30; //anchura de la linea/celda

		$institucion = "Soluciones Tecnológicas Profesionales";
		$abreviatura = "SOTECPRO";
		$direccion = "16 Av. 1-09 zona 1. Cobán, Alta Verapaz, Guatemala";
		$email = "sotecpro1@gmail.com";
		$telefono = "0000-0000";
		$nit = "00-00-00-000";
		$pathlogo1 = base_url('img/logosoctecpro.png');

		// ACA ES DONDE EMPIEZA LO DEL FORMATO DE REPORTE---------------------------------------------------
		$hoy = date("Y-m-d H:i:s");
		//fecha y usuario que genero el reporte
		$this->SetFont('Arial', '', 7);
		$this->Cell(0, 2, $hoy, 0, 1, 'R');
		$this->Ln(2);
		// Logo de la agencia
		$this->Image($pathlogo1, 10, 10, 25);

		//tipo de letra para el encabezado
		$this->SetFont('Arial', '', 8);
		// Título
		$this->Cell(0, 3, utf8_decode($institucion), 0, 1, 'C');
		$this->Cell(0, 3, $abreviatura, 0, 1, 'C');
		$this->Cell(0, 3, utf8_decode($direccion), 0, 1, 'C');
		$this->Cell(0, 3, 'Email: ' . $email, 0, 1, 'C');
		$this->Cell(0, 3, 'Tel: ' . $telefono, 0, 1, 'C');
		$this->Cell(0, 3, 'NIT: ' . $nit, 0, 1, 'C');
		// Salto de línea
		$this->Ln(3);
	}
	function Footer()
	{
		$pathlogo = base_url('img/logosoctecpro.png');
		// Posición: a 1 cm del final
		$this->SetY(-15);
		// Logo
		$this->Image($pathlogo, 190, 279, 15);
		// Arial italic 8
		$this->SetFont('Arial', 'I', 8);
		// Número de página
		$this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
	}
}
