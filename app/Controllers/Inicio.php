<?php

namespace App\Controllers;

date_default_timezone_set('America/Guatemala');

use App\Models\ModelCliente;
use App\Models\ModelDepartamento;
use App\Models\ModelInicio;
use App\Models\ModelFactura;
use App\Models\ModelServicio;
use CodeIgniter\Model;


class Inicio extends BaseController
{
	public function index()
	{
		//segundo array
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_detalle det');

		$builder->select('det.id_factura, cl.nombre, cl.nomcom, ft.transaccion, cl.telefono, dep.nombre departamento');
		$builder->join('tb_factura ft', 'det.id_factura = ft.id');
		$builder->join('tb_servicios ser', 'det.id_servicio = ser.id');
		$builder->join('tb_clientes cl', 'ft.id_cliente = cl.id');
		$builder->join('tb_departamento dep', 'cl.id_departamento=dep.id');
		$builder->where('ft.estado', '1');
		$builder->groupBy('det.id_factura');

		$datos['datos'] = $builder->get()->getResultArray();

		//Carga la vista y recibe datos
		echo view('/layout/header');
		echo view('/inicio/list', $datos);
		echo view('/layout/footer');
	}
	public function add()
	{
		//Se hace la consulta a para llenar el modal
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_clientes cl');
		$builder->select('cl.id, cl.nombre, cl.nomcom, cl.telefono, dep.nombre departamento');
		$builder->join('tb_departamento dep', 'cl.id_departamento = dep.id');
		$builder->where('cl.estado', '1');
		$datos['datos'] = $builder->get()->getResultArray();
		//consulta para cargar el select
		$db2      = \Config\Database::connect();
		$builder2 = $db2->table('tb_servicios');
		$builder2->select('*');
		$builder2->where('estado', '1');
		$datos2['datos2'] = $builder2->get()->getResultArray();

		echo view('/layout/header');
		echo view('/inicio/add', $datos2);
		echo view('/layout/footer');
		echo view('inicio/modalcli', $datos);
	}

	public function add_create()
	{
		//validacion de datos
		$validaciones = $this->validate(['id_cliente' => 'required', 'transaccion' => 'required']);

		if ($_POST && $validaciones) {
			// echo "<pre>";
			// print_r($_POST);
			// echo "</pre>";
			//datos del encabezado de factura
			$datos = [
				'id_cliente' => $_POST['id_cliente'],
				'transaccion' => $_POST['transaccion'],
				'estado' => '1'
			];

			//Se realiza la inserccion en la bd de factura
			$model = new ModelFactura();
			//se ejecuta el metodo de guardado
			$model->add($datos);
			$ult = $model->getInsertID();

			//insercion de los detalles de la factura
			$listDetalle[] = [];
			$aux_contador = 0;
			$iterador = 0;
			foreach ($_POST as $clave => $valor) {
				$aux_contador++;
				$listDetalle[$iterador] = $valor;
				$iterador++;
			}
			$contador = ($aux_contador - 3) / 9;
			$listDetalle2[] = [];
			$iterador = 3;
			for ($i = 0; $i < $contador; $i++) {

				for ($j = 0; $j < 9; $j++) {
					$listDetalle2[$i][$j] = $listDetalle[$iterador];
					$iterador++;
				}
			}

			for ($i = 0; $i < $contador; $i++) {
				$datos = [
					'id_factura' => $ult,
					'id_servicio' => $listDetalle2[$i][0],
					'fechai' => $listDetalle2[$i][2],
					'fechaf' => $listDetalle2[$i][3],
					'monto' => $listDetalle2[$i][4],
					'abono' => $listDetalle2[$i][5],
					'cuota' => $listDetalle2[$i][7],
					'observaciones' => $listDetalle2[$i][8],
					'estadod' => '1',
					'estado' => '1'
				];
				$model = new ModelInicio();
				//se ejecuta el metodo de guardado
				$model->add($datos);
			}

			//retorna a la vista principal
			session()->setFlashdata('mensaje', 'Registro guardado exitosamente');
			return redirect()->to(base_url('inicio'));
		} else {
			//capturando errores
			$error = $this->validator->listErrors();
			session()->setFlashdata('mensaje', $error);
			return redirect()->to(base_url('inicio/add'));
		}
	}

	public function edit($id)
	{
		//Se hace la consulta traer datos del cliente
		// $db      = \Config\Database::connect();
		// $builder = $db->table('tb_detalle det');
		// $builder->select('cl.id id_cliente, cl.nombre, cl.nomcom, cl.telefono, dep.nombre departamento, ft.transaccion, ft.id id_factura');
		// $builder->join('tb_factura ft', 'det.id_factura = ft.id');
		// $builder->join('tb_servicios ser', 'det.id_servicio = ser.id');
		// $builder->join('tb_clientes cl', 'ft.id_cliente = cl.id');
		// $builder->join('tb_departamento dep', 'cl.id_departamento=dep.id');
		// $builder->where('cl.estado', '1');
		// $builder->where('ft.estado', '1');
		// $builder->where('det.estado', '1');
		// $builder->where('ser.estado', '1');
		// $builder->where('ft.id', $id);
		// $builder->groupBy('cl.id');
		// $datos['cliente'] = $builder->get()->getResultArray();
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_detalle det');
		$builder->select('cl.id id_cliente, cl.nombre, cl.nomcom, cl.telefono, dep.nombre departamento, ft.transaccion, ft.id id_factura');
		$builder->join('tb_factura ft', 'det.id_factura = ft.id');
		$builder->join('tb_servicios ser', 'det.id_servicio = ser.id');
		$builder->join('tb_clientes cl', 'ft.id_cliente = cl.id');
		$builder->join('tb_departamento dep', 'cl.id_departamento=dep.id');
		$builder->where('cl.estado', '1');
		$builder->where('ft.estado', '1');
		$builder->where('ser.estado', '1');
		$builder->where('ft.id', $id);
		$builder->groupBy('cl.id');
		$datos['cliente'] = $builder->get()->getResultArray();

		//se hace la consulta para traer los servicios
		//consulta para cargar el select
		$db3      = \Config\Database::connect();
		$builder3 = $db3->table('tb_servicios');
		$builder3->select('*');
		$builder3->where('estado', '1');
		$datos1['servicios'] = $builder3->get()->getResultArray();

		//se hace la consulta para traer los detalles
		$db2      = \Config\Database::connect();
		$builder2 = $db2->table('tb_detalle det');
		$builder2->select('det.id, det.id_servicio, ser.nombre servicio, det.fechai, det.fechaf, det.monto, det.abono, det.cuota, det.observaciones');
		$builder2->join('tb_factura ft', 'det.id_factura = ft.id');
		$builder2->join('tb_servicios ser', 'det.id_servicio = ser.id');
		$builder2->join('tb_clientes cl', 'ft.id_cliente = cl.id');
		$builder2->join('tb_departamento dep', 'cl.id_departamento=dep.id');
		$builder2->where('cl.estado', '1');
		$builder2->where('ft.estado', '1');
		$builder2->where('det.estado', '1');
		$builder2->where('ser.estado', '1');
		$builder2->where('ft.id', $id);
		$datos2['detalles'] = $builder2->get()->getResultArray();

		$data['data'] = array_merge($datos, $datos1, $datos2);

		echo view('/layout/header');
		echo view('/inicio/edit', $data);
		echo view('/layout/footer');
	}

	public function actualizar()
	{
		//validacion de datos
		$validaciones = $this->validate(['id_cliente' => 'required', 'id_factura' => 'required', 'id_detalle' => 'required']);
		if ($validaciones) {
			//validacion de select
			if ($_POST['servicio'] < 1) {
				session()->setFlashdata('mensaje', 'Debe seleccionar un tipo de servicio');
				return redirect()->to(base_url('inicio/edit/' . $_POST['id_factura']));
				exit;
			}

			// validar fecha
			if ($_POST['fechai'] > $_POST['fechaf']) {
				session()->setFlashdata('mensaje', 'La fecha final no puede ser mayor que la inicial');
				return redirect()->to(base_url('inicio/edit/' . $_POST['id_factura']));
				exit;
			}

			//validaciones
			$datos = [
				'id_factura' => $_POST['id_factura'],
				'id_servicio' => $_POST['servicio'],
				'fechai' => $_POST['fechai'],
				'fechaf' => $_POST['fechaf'],
				'monto' => $_POST['monto'],
				'abono' => $_POST['abono'],
				'cuota' => $_POST['cuota'],
				'observaciones' => $_POST['observaciones'],
				'estadod' => '1',
				'estado' => '1'
			];


			//se instancia el modelo
			$model = new ModelInicio();
			//se ejecuta el metodo de guardado
			$id = $_POST['id_detalle'];
			//aqui es donde hay error
			$model->actualizarDatos($id, $datos);

			//retorna a la vista principal
			session()->setFlashdata('mensaje', 'Registro actualizado exitosamente');
			return redirect()->to(base_url('inicio/edit/' . $_POST['id_factura']));
		} else {
			//capturando errores
			$error = $this->validator->listErrors();
			session()->setFlashdata('error', $error);
			return redirect()->to(base_url('inicio/edit/' . $_POST['id_factura']));
		}
	}

	public function actadd()
	{
		if ($_POST) {
			//condicion para agregar
			if ($_POST['id_detalle'] == "") {
				$datos = [
					'id_factura' => $_POST['id_factura'],
					'id_servicio' => $_POST['servicio'],
					'fechai' => $_POST['fechai'],
					'fechaf' => $_POST['fechaf'],
					'monto' => $_POST['monto'],
					'abono' => $_POST['abono'],
					'cuota' => $_POST['cuota'],
					'observaciones' => $_POST['observaciones'],
					'estadod' => '1',
					'estado' => '1'
				];
				$model = new ModelInicio();
				//se ejecuta el metodo de guardado
				$aux = $model->add($datos);

				if ($aux) {
					session()->setFlashdata('mensaje', 'Detalle de factura registrado exitosamente');
					return redirect()->to(base_url('inicio/edit/' . $_POST['id_factura']));
				} else {
					session()->setFlashdata('error', 'Detalle de factura no registrado exitosamente');
					return redirect()->to(base_url('inicio/edit/' . $_POST['id_factura']));
				}
			} else {
				return $this->actualizar();
			}
		} else {
			session()->setFlashdata('mensaje_error', 'Algo salio mal');
			return redirect()->to(base_url('inicio'));
		}
	}

	public function delete($id2)
	{
		//Se hace la consulta si ya existe un pago basado en el detalle de la factura del encabezado
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_detalle det');
		$builder->select('COUNT(*) total');
		$builder->join('tb_pago pg', 'det.id = pg.id_detalle');
		$builder->where('det.estado', '1');
		$builder->where('pg.estado', '1');
		$builder->where('det.id_factura', $id2);
		$data = $builder->get()->getResultArray();

		if ($data[0]['total'] == 0) {

			//eliminar los detalles en base a la factura eliminada
			//buscar detalles de factura
			$db2      = \Config\Database::connect();
			$builder2 = $db2->table('tb_detalle det');
			$builder2->select('det.id id_detalle');
			$builder2->join('tb_factura ft', 'det.id_factura = ft.id');
			$builder2->where('det.estado', '1');
			$builder2->where('ft.id', $id2);
			$data2 = $builder2->get()->getResultArray();

			for ($i = 0; $i < count($data2); $i++) {
				$model3 = new ModelInicio();
				$aux1 = $model3->delete($data2[$i]['id_detalle']);
				$datos = [
					'estado' => '0',
				];
				$model3 = new ModelInicio();
				$aux1 = $model3->eliminarDato($data2[$i]['id_detalle'],$datos);
			}

			$model = new ModelFactura();
			$aux1 = $model->delete($id2);
			$datos = [
				'estado' => '0',
			];
			$id = $id2;
			$model = new ModelFactura();
			$aux2 = $model->eliminarDato($id, $datos);

			if ($aux1 && $aux2) {
				//retorna a la vista principal
				session()->setFlashdata('mensaje', 'Registro eliminado exitosamente');
				return redirect()->to(base_url('inicio'));
			} else {
				//retorna a la vista principal
				session()->setFlashdata('mensaje_error', 'Registro no eliminado exitosamente');
				return redirect()->to(base_url('inicio'));
			}
		} else {
			session()->setFlashdata('mensaje_error', 'No se puede eliminar, debido a que se han realizado ' . $data[0]['total'] . ' pago(s) de la factura');
			return redirect()->to(base_url('inicio'));
		}
	}
	//metodo para ver detalle de cliente
	public function view($id)
	{
		//Se hace la consulta traer datos del cliente
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_detalle det');
		$builder->select('cl.id id_cliente, cl.nombre, cl.nomcom, cl.telefono, dep.nombre departamento, ft.transaccion, ft.id id_factura');
		$builder->join('tb_factura ft', 'det.id_factura = ft.id');
		$builder->join('tb_servicios ser', 'det.id_servicio = ser.id');
		$builder->join('tb_clientes cl', 'ft.id_cliente = cl.id');
		$builder->join('tb_departamento dep', 'cl.id_departamento=dep.id');
		$builder->where('cl.estado', '1');
		$builder->where('ft.estado', '1');
		$builder->where('det.estado', '1');
		$builder->where('ser.estado', '1');
		$builder->where('ft.id', $id);
		$builder->groupBy('cl.id');
		$datos1['cliente'] = $builder->get()->getResultArray();

		//se hace la consulta para traer los detalles
		$db2      = \Config\Database::connect();
		$builder2 = $db2->table('tb_detalle det');
		$builder2->select('det.id, det.id_servicio, ser.nombre servicio, det.fechai, det.fechaf, det.monto, det.abono, det.cuota, det.observaciones, det.estadod, abonoenpagos(det.id) abnenpagos');
		$builder2->join('tb_factura ft', 'det.id_factura = ft.id');
		$builder2->join('tb_servicios ser', 'det.id_servicio = ser.id');
		$builder2->join('tb_clientes cl', 'ft.id_cliente = cl.id');
		$builder2->join('tb_departamento dep', 'cl.id_departamento=dep.id');
		$builder2->where('cl.estado', '1');
		$builder2->where('ft.estado', '1');
		$builder2->where('det.estado', '1');
		$builder2->where('ser.estado', '1');
		$builder2->where('det.estado', '1');
		$builder2->where('ft.id', $id);
		$datos2['detalles'] = $builder2->get()->getResultArray();

		$datos['datos'] = array_merge($datos1, $datos2);

		//consultar los pagos realizados
		// $query = $db->query('SELECT abonoenpagos("' . $id_detalle[0]['id_detalle'] . '") abnenpagos');
		// $abnenpagos = $query->getResultArray();

		echo view('/layout/header');
		echo view('/inicio/view', $datos);
		echo view('/layout/footer');
	}

	public function dser($id2)
	{
		//Se hace la consulta si ya existe un pago basado en el detalle de la factura del encabezado
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_detalle det');
		$builder->select('COUNT(*) total, id_factura');
		$builder->join('tb_pago pg', 'det.id = pg.id_detalle');
		$builder->where('det.estado', '1');
		$builder->where('pg.estado', '1');
		$builder->where('det.id', $id2);
		$data = $builder->get()->getResultArray();

		if ($data[0]['total'] == 0) {

			$model = new ModelInicio();
			$aux1 = $model->delete($id2);
			$datos = [
				'estado' => '0',
			];
			$id = $id2;
			$model = new ModelInicio();
			$aux2 = $model->eliminarDato($id, $datos);

			if ($aux1 && $aux2) {
				//retorna a la vista principal
				session()->setFlashdata('mensaje', 'Registro eliminado exitosamente');
				return redirect()->to(base_url('inicio/edit/' . $data[0]['id_factura']));
			} else {
				//retorna a la vista principal
				session()->setFlashdata('error', 'Registro no eliminado exitosamente');
				return redirect()->to(base_url('inicio/edit/' . $data[0]['id_factura']));
			}
		} else {
			session()->setFlashdata('error', 'No se puede eliminar, debido a que se ha hecho un pago basado en el registro, verifique los pagos');
			return redirect()->to(base_url('inicio/edit/' . $data[0]['id_factura']));
		}
	}
}
