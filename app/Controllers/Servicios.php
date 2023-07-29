<?php

namespace App\Controllers;
use App\Models\ModelServicio;

date_default_timezone_set('America/Guatemala');

class Servicios extends BaseController
{
	public function index()
	{
        //se llama el modelo
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_servicios');
		$builder->select('*');
		$builder->where('estado', '1');
		$datos['datos'] = $builder->get()->getResultArray();

		//envio de datos a la vista
		echo view('/layout/header');
		echo view('/servicios/list', $datos);
		echo view('/layout/footer');
	}

	public function add()
	{
		$model = new ModelServicio();
		$datos = $model->getServicios();

		echo view('/layout/header');
		echo view('/servicios/add', compact('datos'));
		echo view('/layout/footer');
	}

	public function add_create()
	{
		//validacion de datos
		$validaciones = $this->validate(['nombre' => 'required']);

		if ($_POST && $validaciones) {
			//se envian los parametros
			$datos = [
				'nombre' => $_POST['nombre'],
				'descripcion' => $_POST['descripcion'],
				'estado' => '1'
			];
			//se instancia el modelo
			$model = new ModelServicio();
			//se ejecuta el metodo de guardado
			$model->add($datos);

			//retorna a la vista principal
			session()->setFlashdata('mensaje', 'Registro guardado exitosamente');
			return redirect()->to(base_url('servicios'));
		} else {
			//capturando errores
			$error = $this->validator->listErrors();
			session()->setFlashdata('mensaje', $error);
			return redirect()->to(base_url('servicios/add'));
		}
	}

	public function edit($id)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('tb_servicios ser');
		$builder->select('*');
		$builder->where('ser.id', $id);

		$data['data'] = $builder->get()->getResultArray();

		echo view('/layout/header');
		echo view('/servicios/edit', $data);
		echo view('/layout/footer');
	}

	public function actualizar()
	{
		//validacion de datos
		$validaciones = $this->validate(['nombre' => 'required']);


		if ($validaciones) {
			//se envian los parametros
			$datos = [
				'nombre' => $_POST['nombre'],
				'descripcion' => $_POST['descripcion']
			];

			//se instancia el modelo
			$model = new ModelServicio();
			//se ejecuta el metodo de guardado
			$id = $_POST['id'];
			//aqui es donde hay error
			$model->actualizarDatos($id, $datos);

			//retorna a la vista principal
			session()->setFlashdata('mensaje', 'Registro actualizado exitosamente');
			return redirect()->to(base_url('servicios'));
		} else {
			//capturando errores
			$error = $this->validator->listErrors();
			session()->setFlashdata('mensaje', $error);
			return redirect()->to(base_url('servicios/edit/' . $_POST['id']));
		}
	}

	public function delete($id2)
	{
		$model = new ModelServicio();
		$aux1 = $model->delete($id2);
		$datos = [
			'estado' => '0',
		];
		$id = $id2;
		$model = new ModelServicio();
		$aux2 = $model->eliminarDato($id, $datos);

		if ($aux1 && $aux2) {
			//retorna a la vista principal
			session()->setFlashdata('mensaje', 'Registro eliminado exitosamente');
			return redirect()->to(base_url('servicios'));
		} else {
			//retorna a la vista principal
			session()->setFlashdata('mensaje_error', 'Registro no eliminado exitosamente');
			return redirect()->to(base_url('servicios'));
		}
	}
}
?>
