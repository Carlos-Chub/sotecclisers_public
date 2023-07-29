<?php

namespace App\Controllers;

use App\Models\ModelCliente;
use App\Models\ModelDepartamento;
use App\Models\ModelFactura;
use App\Models\ModelInicio;
use App\Models\ModelPago;

date_default_timezone_set('America/Guatemala');


class Pagos extends BaseController
{
	public function index()
	{
		//segundo array
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_detalle det');

		$builder->select('pg.id, pg.id_detalle, ft.transaccion, pg.numdoc, pg.monto, pg.fecha, cl.nombre, cl.nomcom,  ser.nombre servicio, dep.nombre departamento');
		$builder->join('tb_pago pg', 'det.id = pg.id_detalle');
		$builder->join('tb_factura ft', 'det.id_factura = ft.id');
		$builder->join('tb_servicios ser', 'det.id_servicio = ser.id');
		$builder->join('tb_clientes cl', 'ft.id_cliente = cl.id');
		$builder->join('tb_departamento dep', 'cl.id_departamento=dep.id');
		$builder->where('ft.estado', '1');
		$builder->where('det.estado', '1');
		$builder->where('pg.estado', '1');
		$builder->where('cl.estado', '1');
		$builder->orderBy('pg.fecha', 'DESC');
		$datos['datos'] = $builder->get()->getResultArray();

		//envio de datos a la vista
		echo view('/layout/header');
		echo view('/pagos/list', $datos);
		echo view('/layout/footer');
	}

	public function add()
	{
		//segundo array
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_detalle det');

		$builder->select('det.id_factura, ft.transaccion, cl.nombre, cl.nomcom,cl.nit,cl.telefono, dep.nombre departamento');
		$builder->join('tb_factura ft', 'det.id_factura = ft.id');
		$builder->join('tb_servicios ser', 'det.id_servicio = ser.id');
		$builder->join('tb_clientes cl', 'ft.id_cliente = cl.id');
		$builder->join('tb_departamento dep', 'cl.id_departamento=dep.id');
		$builder->where('ft.estado', '1');
		$builder->where('det.estado', '1');
		$builder->where('cl.estado', '1');
		$builder->groupBy('det.id_factura');
		$datos['datos'] = $builder->get()->getResultArray();


		echo view('/layout/header');
		echo view('/pagos/add');
		echo view('/layout/footer');
		echo view('/pagos/modalcli', $datos);
	}

	public function add_create()
	{
		// echo ('<pre>');
		// print_r($_POST);
		// echo ('</pre>');
		//validacion de datos
		$validaciones = $this->validate(['id_factura' => 'required', 'fechapago' => 'required', 'documento' => 'required', 'servicio' => 'required', 'monto' => 'required']);

		if ($_POST && $validaciones) {
			//validacion de select
			if ($_POST['servicio'] < 1 || $_POST['servicio'] == "") {
				session()->setFlashdata('mensaje_error', 'Debe seleccionar un servicio');
				return redirect()->to(base_url('pagos/add'));
				exit;
			}
			//buscar id detalle
			$db      = \Config\Database::connect();
			$query = $db->query('SELECT buscarservicio("' . $_POST['id_factura'] . '", "' . $_POST['servicio'] . '") id_detalle');
			$id_detalle = $query->getResultArray();
			if ($id_detalle[0]['id_detalle'] == "") {
				session()->setFlashdata('mensaje_error', 'No se puede ingresar el pago ya que no corresponde a ninguna factura');
				return redirect()->to(base_url('pagos/add'));
				exit;
			}

			//validar si se puede hacer o no el pago
			$query = $db->query('SELECT validarpago("' . $id_detalle[0]['id_detalle'] . '") validar');
			$pago = $query->getResultArray();
			if ($pago[0]['validar'] != 0) {
				//consultas
				$query = $db->query('SELECT montototal("' . $id_detalle[0]['id_detalle'] . '") montotal');
				$montotal = $query->getResultArray();
				$query = $db->query('SELECT abonoinicial("' . $id_detalle[0]['id_detalle'] . '") abninicial');
				$abninicial = $query->getResultArray();
				$query = $db->query('SELECT abonoenpagos("' . $id_detalle[0]['id_detalle'] . '") abnenpagos');
				$abnenpagos = $query->getResultArray();
				$query = $db->query('SELECT obtenercuota("' . $id_detalle[0]['id_detalle'] . '") cuota');
				$cuota = $query->getResultArray();
				//calcular lo que hace falta por pagar
				$resta = ($montotal[0]['montotal']) - (($abninicial[0]['abninicial']) + ($abnenpagos[0]['abnenpagos']));
				if ($_POST['monto'] <= $resta) {
					//preparar datos para insertar
					$datos = [
						'id_detalle' => $id_detalle[0]['id_detalle'],
						'fecha' => $_POST['fechapago'],
						'numdoc' => $_POST['documento'],
						'monto' => $_POST['monto'],
						'estado' => '1'
					];
					//se hace la insercion
					$model = new ModelPago();
					//se ejecuta el metodo de guardado
					$res = $model->add($datos);
					if ($res) {
						//cambiar el estado de la factura
						//consultas
						$query = $db->query('SELECT montototal("' . $id_detalle[0]['id_detalle'] . '") montotal');
						$montotal = $query->getResultArray();
						$query = $db->query('SELECT abonoinicial("' . $id_detalle[0]['id_detalle'] . '") abninicial');
						$abninicial = $query->getResultArray();
						$query = $db->query('SELECT abonoenpagos("' . $id_detalle[0]['id_detalle'] . '") abnenpagos');
						$abnenpagos = $query->getResultArray();
						$query = $db->query('SELECT obtenercuota("' . $id_detalle[0]['id_detalle'] . '") cuota');
						$cuota = $query->getResultArray();
						//calcular lo que hace falta por pagar
						$resta = ($montotal[0]['montotal']) - (($abninicial[0]['abninicial']) + ($abnenpagos[0]['abnenpagos']));
						if ($resta == 0) {
							//realizar la actualizacion
							$datos = [
								'estadod' => '0'
							];
							//se instancia el modelo
							$model = new ModelInicio();
							//se ejecuta el metodo de guardado
							$iddet = $id_detalle[0]['id_detalle'];
							//aqui es donde hay error
							$res = $model->actualizarDatos($iddet, $datos);
						}

						//mensaje de exito
						session()->setFlashdata('mensaje', 'Pago registrado exitosamente');
						return redirect()->to(base_url('pagos'));
					} else {
						session()->setFlashdata('mensaje_error', 'No se completo el pago, ocurrio un error al registrar el pago');
						return redirect()->to(base_url('pagos/add'));
					}
				} else {
					session()->setFlashdata('mensaje_error', 'No se puede completar el pago, porque intenta ingresar un pago de ' . $_POST['monto'] . ' quetzales cuando el saldo restante a pagar es de ' . $resta . ' quetzales. Tomar en cuenta que la cuota mensual a pagar es de ' . $cuota[0]['cuota'] . ' quetzales');
					return redirect()->to(base_url('pagos/add'));
					exit;
				}
			} else {
				$query = $db->query('SELECT montototal("' . $id_detalle[0]['id_detalle'] . '") montotal');
				$montotal = $query->getResultArray();
				$query = $db->query('SELECT abonoinicial("' . $id_detalle[0]['id_detalle'] . '") abninicial');
				$abninicial = $query->getResultArray();
				$query = $db->query('SELECT abonoenpagos("' . $id_detalle[0]['id_detalle'] . '") abnenpagos');
				$abnenpagos = $query->getResultArray();
				//lo que falta por pagar
				$resta = ($montotal[0]['montotal']) - (($abninicial[0]['abninicial']) + ($abnenpagos[0]['abnenpagos']));
				session()->setFlashdata('mensaje_error', 'No se puede realizar el pago, porque el monto total a pagar es de ' . $montotal[0]['montotal'] . ' quetzales, se hizo un abono inicial de ' . $abninicial[0]['abninicial'] . ' quetzales, y la suma de pagos en cuotas es de ' . $abnenpagos[0]['abnenpagos'] . ' quetzales, por lo que ya no hay saldo a pagar para este servicio');
				return redirect()->to(base_url('pagos/add'));
				exit;
			}
		} else {
			//capturando errores
			$error = $this->validator->listErrors();
			session()->setFlashdata('mensaje_error', $error);
			return redirect()->to(base_url('pagos/add'));
		}
	}

	public function edit($id)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_detalle det');

		$builder->select('pg.id, det.id id_detalle, ser.id id_servicio, cl.nombre, cl.nomcom, cl.nit, cl.telefono, dep.nombre departamento, ft.transaccion, pg.fecha, pg.numdoc, ser.nombre servicio, pg.monto');
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

		echo view('/layout/header');
		echo view('/pagos/edit', $data);
		echo view('/layout/footer');
	}

	public function actualizar()
	{
		echo ('<pre>');
		print_r($_POST);
		echo ('</pre>');

		$validaciones = $this->validate(['id_detalle' => 'required', 'id_pago' => 'required', 'fechapago' => 'required', 'documento' => 'required', 'monto' => 'required']);

		if ($validaciones) {
			//validar que el nuevo monto no supere el monto total
			$db      = \Config\Database::connect();
			$query = $db->query('SELECT montototal("' . $_POST['id_detalle'] . '") montotal');
			$montotal = $query->getResultArray();
			$query = $db->query('SELECT abonoinicial("' . $_POST['id_detalle'] . '") abninicial');
			$abninicial = $query->getResultArray();
			$query = $db->query('SELECT abonoenpagos("' . $_POST['id_detalle'] . '") abnenpagos');
			$abnenpagos = $query->getResultArray();
			$query = $db->query('SELECT obtenercuota("' . $_POST['id_detalle'] . '") cuota');
			$cuota = $query->getResultArray();
			//consulta el pago que ya estaba almacenado
			$query = $db->query('SELECT obtenerpaglast("' . $_POST['id_pago'] . '") lastpag');
			$lastpago = $query->getResultArray();
			//realizar la sumatoria
			$resta = ($montotal[0]['montotal']) - (($abninicial[0]['abninicial']) + ($abnenpagos[0]['abnenpagos']) - ($lastpago[0]['lastpag']) + ($_POST['monto']));
			$resta2 = ($montotal[0]['montotal']) - (($abninicial[0]['abninicial']) + ($abnenpagos[0]['abnenpagos']));
			//no permite actualizar un pago si ya ha sido completado todos los pagos al servicio
			if ($resta2 == 0) {
				session()->setFlashdata('mensaje_error', 'No se puede actualizar el registro, porque el monto total que es de ' . $montotal[0]['montotal'] . ' quetzales, ya ha sido pagado, por lo que el saldo por pagar es de 0.00 quetzales');
				return redirect()->to(base_url('pagos/edit/' . $_POST['id_pago']));
				exit;
			}

			if ($resta < 0) {
				session()->setFlashdata('mensaje_error', 'No se puede actualizar el registro, porque el nuevo monto de pago es de ' . $_POST['monto'] . ' quetzales, y el anterior es de ' . $lastpago[0]['lastpag'] . ' quetzales, lo cual el nuevo pago supera el monto total a pagar que es de ' . $montotal[0]['montotal'] . ' quetzales. El saldo pendiente por pagar es de ' . $resta2 . ' quetzales. Tomar en cuenta que la cuota mensual a pagar es de ' . $cuota[0]['cuota'] . ' quetzales');
				return redirect()->to(base_url('pagos/edit/' . $_POST['id_pago']));
				exit;
			}
			//realizar la actualizacion
			$datos = [
				'id_detalle' => $_POST['id_detalle'],
				'fecha' => $_POST['fechapago'],
				'numdoc' => $_POST['documento'],
				'monto' => $_POST['monto'],
				'estado' => '1'
			];
			//se instancia el modelo
			$model = new ModelPago();
			//se ejecuta el metodo de guardado
			$id = $_POST['id_pago'];
			//aqui es donde hay error
			$res = $model->actualizarDatos($id, $datos);

			if ($res) {
				//consultar nuevamente el saldo a pagar
				$db      = \Config\Database::connect();
				$query2 = $db->query('SELECT montototal("' . $_POST['id_detalle'] . '") montotal');
				$montotal = $query2->getResultArray();
				$query2 = $db->query('SELECT abonoinicial("' . $_POST['id_detalle'] . '") abninicial');
				$abninicial = $query2->getResultArray();
				$query2 = $db->query('SELECT abonoenpagos("' . $_POST['id_detalle'] . '") abnenpagos');
				$abnenpagos = $query2->getResultArray();
				$query2 = $db->query('SELECT obtenercuota("' . $_POST['id_detalle'] . '") cuota');
				$cuota = $query2->getResultArray();

				$saldo = ($montotal[0]['montotal']) - (($abninicial[0]['abninicial']) + ($abnenpagos[0]['abnenpagos']));

				//actualizar el estado en caso de que el saldo sea igual a 0 estado del servicio que quede en Inactivo de lo contrario activo
				//cambiar el estado de la factura
				//consultas
				$db      = \Config\Database::connect();
				$query = $db->query('SELECT montototal("' . $_POST['id_detalle'] . '") montotal');
				$montotal = $query->getResultArray();
				$query = $db->query('SELECT abonoinicial("' . $_POST['id_detalle'] . '") abninicial');
				$abninicial = $query->getResultArray();
				$query = $db->query('SELECT abonoenpagos("' . $_POST['id_detalle'] . '") abnenpagos');
				$abnenpagos = $query->getResultArray();
				$query = $db->query('SELECT obtenercuota("' . $_POST['id_detalle'] . '") cuota');
				//calcular lo que hace falta por pagar
				$resta = ($montotal[0]['montotal']) - (($abninicial[0]['abninicial']) + ($abnenpagos[0]['abnenpagos']));
				if ($resta == 0) {
					//realizar la actualizacion
					$datos = [
						'estadod' => '0'
					];
					//se instancia el modelo
					$model = new ModelInicio();
					//se ejecuta el metodo de guardado
					$iddet = $_POST['id_detalle'];
					//aqui es donde hay error
					$res = $model->actualizarDatos($iddet, $datos);
				} else {
					//realizar la actualizacion
					$datos = [
						'estadod' => '1'
					];
					//se instancia el modelo
					$model = new ModelInicio();
					//se ejecuta el metodo de guardado
					$iddet = $_POST['id_detalle'];
					//aqui es donde hay error
					$res = $model->actualizarDatos($iddet, $datos);
				}


				session()->setFlashdata('mensaje', 'Pago actualizado exitosamente, el saldo pendiente a pagar es de ' . $saldo . ' quetzales. Tomar en cuenta que la cuota mensual es de ' . $cuota[0]['cuota'] . ' quetzales');
				return redirect()->to(base_url('pagos'));
			} else {
				session()->setFlashdata('mensaje_error', 'No se actualizo el pago, ocurrio un error al registrar el pago');
				return redirect()->to(base_url('pagos/edit/' . $_POST['id_pago']));
			}
		} else {
			//capturando errores
			$error = $this->validator->listErrors();
			session()->setFlashdata('mensaje_error', $error);
			return redirect()->to(base_url('pagos/edit/' . $_POST['id_pago']));
		}
	}

	public function delete($id2)
	{
		$model = new ModelPago();
		$aux1 = $model->delete($id2);
		$datos = [
			'estado' => '0',
		];
		$id = $id2;
		$aux2 = $model->eliminarDato($id, $datos);
		//buscar el detalle de factura mediante el pago
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_pago pg');
		$builder->select('pg.id_detalle');
		$builder->where('pg.id', $id);
		$data['datael'] = $builder->get()->getResultArray();

		if ($aux1 && $aux2) {
			//consultas
			$db      = \Config\Database::connect();
			$query = $db->query('SELECT montototal("' . $data['datael'][0]['id_detalle'] . '") montotal');
			$montotal = $query->getResultArray();
			$query = $db->query('SELECT abonoinicial("' . $data['datael'][0]['id_detalle'] . '") abninicial');
			$abninicial = $query->getResultArray();
			$query = $db->query('SELECT abonoenpagos("' . $data['datael'][0]['id_detalle'] . '") abnenpagos');
			$abnenpagos = $query->getResultArray();
			$query = $db->query('SELECT obtenercuota("' . $data['datael'][0]['id_detalle'] . '") cuota');
			//calcular lo que hace falta por pagar
			$resta = ($montotal[0]['montotal']) - (($abninicial[0]['abninicial']) + ($abnenpagos[0]['abnenpagos']));
			if ($resta == 0) {
				//realizar la actualizacion
				$datos = [
					'estadod' => '0'
				];
				//se instancia el modelo
				$model = new ModelInicio();
				//se ejecuta el metodo de guardado
				$iddet = $data['datael'][0]['id_detalle'];
				//aqui es donde hay error
				$res = $model->actualizarDatos($iddet, $datos);
			} else {
				//realizar la actualizacion
				$datos = [
					'estadod' => '1'
				];
				//se instancia el modelo
				$model = new ModelInicio();
				//se ejecuta el metodo de guardado
				$iddet = $data['datael'][0]['id_detalle'];
				//aqui es donde hay error
				$res = $model->actualizarDatos($iddet, $datos);
			}

			//retorna a la vista principal
			session()->setFlashdata('mensaje', 'Registro eliminado exitosamente');
			return redirect()->to(base_url('pagos'));
		} else {
			//retorna a la vista principal
			session()->setFlashdata('mensaje_error', 'Registro no eliminado exitosamente');
			return redirect()->to(base_url('pagos'));
		}
	}
	//metodo para ver detalle de cliente
	public function view($id)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_detalle det');

		$builder->select('pg.id, det.id id_detalle, ser.id id_servicio, cl.nombre, cl.nomcom, cl.nit, cl.telefono, dep.nombre departamento, ft.transaccion, pg.fecha, pg.numdoc, ser.nombre servicio, pg.monto');
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

		echo view('/layout/header');
		echo view('/pagos/view', $data);
		echo view('/layout/footer');
	}
	//funcionn para captura servicio
	public function services()
	{
		$id_factura = $_POST['id_factura'];

		$db      = \Config\Database::connect();
		$builder = $db->table('tb_detalle det');

		$builder->select('ser.id, ser.nombre');
		$builder->join('tb_factura ft', 'det.id_factura = ft.id');
		$builder->join('tb_servicios ser', 'det.id_servicio = ser.id');
		$builder->join('tb_clientes cl', 'ft.id_cliente = cl.id');
		$builder->join('tb_departamento dep', 'cl.id_departamento=dep.id');
		$builder->where('ft.estado', '1');
		$builder->where('det.estado', '1');
		$builder->where('det.id_factura', $id_factura);
		$builder->groupBy('ser.id');
		$datos = $builder->get()->getResultArray();
		echo json_encode($datos);
	}
}
