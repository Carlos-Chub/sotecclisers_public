<?php

namespace App\Controllers;

use App\Models\ModelCliente;
use App\Models\ModelDepartamento;

date_default_timezone_set('America/Guatemala');


class Clientes extends BaseController
{
	public function index()
	{
		//primer array
		//llamar al modelo para enviar datos a la vista
		// $model = new ModelCliente();
		// $datos['select'] = $model->getClientes();
		//Carga la vista y recibe datos

		//segundo array
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_clientes cl');

		$builder->select('cl.id, cl.nombre, cl.nit, cl.telefono, cl.fecha, dep.nombre departamento');
		$builder->join('tb_departamento dep', 'cl.id_departamento = dep.id');
		$builder->where('cl.estado', '1');
		$datos['datos'] = $builder->get()->getResultArray();

		// $datoss['datos']=array_merge($datos,$datos2);


		//envio de datos a la vista
		echo view('/layout/header');
		echo view('/clientes/list', $datos);
		echo view('/layout/footer');
	}

	public function add()
	{
		$model = new ModelDepartamento();
		$datos = $model->getDepartamentos();

		echo view('/layout/header');
		echo view('/clientes/add', compact('datos'));
		echo view('/layout/footer');
	}

	public function add_create()
	{
		//validacion de datos
		$validaciones = $this->validate(['nombre' => 'required', 'nomcom' => 'required', 'nit' => 'required', 'departamento' => 'required']);

		if ($_POST && $validaciones) {
			//validacion de select
			if ($_POST['departamento'] < 1) {
				session()->setFlashdata('mensaje', 'Debe seleccionar un departamento');
				return redirect()->to(base_url('clientes/add'));
				exit;
			}
			//validar fecha
			if ($_POST['fecha'] > date('Y-m-d')) {
				session()->setFlashdata('mensaje', 'No puede digitar una fecha mayor al dia de hoy');
				return redirect()->to(base_url('clientes/add'));
				exit;
			}

			//se envian los parametros
			$datos = [
				'id_departamento' => $_POST['departamento'],
				'nombre' => $_POST['nombre'],
				'nomcom' => $_POST['nomcom'],
				'nit' => $_POST['nit'],
				'direccion' => $_POST['direccion'],
				'telefono' => $_POST['telefono'],
				'repnom' => $_POST['repnom'],
				'reptel' => $_POST['reptel'],
				'email' => $_POST['email'],
				'contnom' => $_POST['contnom'],
				'conttel' => $_POST['conttel'],
				'fecha' => $_POST['fecha'],
				'observaciones' => $_POST['observaciones'],
				'estado' => '1'
			];
			//se instancia el modelo
			$model = new ModelCliente();
			//se ejecuta el metodo de guardado
			$model->add($datos);

			//retorna a la vista principal
			session()->setFlashdata('mensaje', 'Registro guardado exitosamente');
			return redirect()->to(base_url('clientes'));
		} else {
			//capturando errores
			$error = $this->validator->listErrors();
			session()->setFlashdata('mensaje', $error);
			return redirect()->to(base_url('clientes/add'));
		}
	}

	public function edit($id)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_clientes cl');
		$builder->select('*');
		$builder->where('cl.id', $id);
		$data1['cliente'] = $builder->get()->getResultArray();

		$model2 = new ModelDepartamento();
		$data2['departamento'] = $model2->getDepartamentos();

		$data['data'] = array_merge($data1, $data2);

		echo view('/layout/header');
		echo view('/clientes/edit', $data);
		echo view('/layout/footer');
	}

	public function actualizar()
	{
		//validacion de datos
		$validaciones = $this->validate(['nombre' => 'required', 'nomcom' => 'required', 'nit' => 'required', 'departamento' => 'required']);

		if ($validaciones) {
			//validacion de select
			if ($_POST['departamento'] < 1) {
				session()->setFlashdata('mensaje', 'Debe seleccionar un departamento');
				return redirect()->to(base_url('clientes/add'));
				exit;
			}
			//validar fecha
			if ($_POST['fecha'] > date('Y-m-d')) {
				session()->setFlashdata('mensaje', 'No puede digitar una fecha mayor al dia de hoy');
				return redirect()->to(base_url('clientes/add'));
				exit;
			}

			//se envian los parametros
			$datos = [
				'id_departamento' => $_POST['departamento'],
				'nombre' => $_POST['nombre'],
				'nomcom' => $_POST['nomcom'],
				'nit' => $_POST['nit'],
				'direccion' => $_POST['direccion'],
				'telefono' => $_POST['telefono'],
				'repnom' => $_POST['repnom'],
				'reptel' => $_POST['reptel'],
				'email' => $_POST['email'],
				'contnom' => $_POST['contnom'],
				'conttel' => $_POST['conttel'],
				'fecha' => $_POST['fecha'],
				'observaciones' => $_POST['observaciones'],
				'estado' => '1'
			];
			//se instancia el modelo
			$model = new ModelCliente();
			//se ejecuta el metodo de guardado
			$id = $_POST['id'];
			//aqui es donde hay error
			$model->actualizarDatos($id, $datos);

			//retorna a la vista principal
			session()->setFlashdata('mensaje', 'Registro actualizado exitosamente');
			return redirect()->to(base_url('clientes'));
		} else {
			//capturando errores
			$error = $this->validator->listErrors();
			session()->setFlashdata('mensaje', $error);
			return redirect()->to(base_url('clientes/edit/' . $_POST['id']));
		}
	}

	public function delete($id2)
	{
		$model = new ModelCliente();
		$aux1 = $model->delete($id2);
		$datos = [
			'estado' => '0',
		];
		$id = $id2;
		$model = new ModelCliente();
		$aux2 = $model->eliminarDato($id, $datos);

		if ($aux1 && $aux2) {
			//retorna a la vista principal
			session()->setFlashdata('mensaje', 'Registro eliminado exitosamente');
			return redirect()->to(base_url('clientes'));
		} else {
			//retorna a la vista principal
			session()->setFlashdata('mensaje_error', 'Registro no eliminado exitosamente');
			return redirect()->to(base_url('clientes'));
		}
	}
	//metodo para ver detalle de cliente
	public function view($id)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_clientes cl');

		$builder->select('cl.*, dep.nombre departamento');
		$builder->join('tb_departamento dep', 'cl.id_departamento = dep.id');
		$builder->where('cl.estado', '1');
		$builder->where('cl.id', $id);

		$data['data'] = $builder->get()->getResultArray();

		echo view('/layout/header');
		echo view('/clientes/view', $data);
		echo view('/layout/footer');
	}
}
