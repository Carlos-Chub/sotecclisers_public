<?php

namespace App\Controllers;

use App\Models\ModelUsuario;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

date_default_timezone_set('America/Guatemala');

class Home extends BaseController
{
	public function index()
	{
		return view('login');
	}


	public function login()
	{
		if ($_POST) {
			//validar un usuario
			if ($_POST['usuario'] == "") {
				session()->setFlashdata('mensaje', 'Debe llenar el campo usuario');
				return redirect()->to(base_url('/'));
				exit;
			}
			if ($_POST['password'] == "") {
				session()->setFlashdata('mensaje', 'Debe llenar el campo password');
				return redirect()->to(base_url('/'));
				exit;
			}
			//se ejecuta el metodo de guardado
			$user = $_POST['usuario'];
			$pass = $_POST['password'];

			$db      = \Config\Database::connect();
			$builder = $db->table('tb_usuario');
			$builder->select('id, nombres, apellidos, user, pass, estado');
			$builder->where('user', $user);
			$data2 = $builder->get()->getResultArray();

			if ($data2) {
				$db      = \Config\Database::connect();
				$builder = $db->table('tb_usuario');
				$builder->select('id, nombres, apellidos, user, pass, estado');
				$builder->where('user', $user);
				$builder->where('pass', $pass);
				$data2 = $builder->get()->getResultArray();
				if ($data2) {
					if ($data2[0]['estado'] == 1) {
						//aqui se tiene que crear la sesion
						$data = [
							"id" => $data2[0]['id'],
							"nombre" => $data2[0]['nombres'],
							"apellido" => $data2[0]['apellidos'],
							"user" => $data2[0]['user']
						];

						$session = session();
						$session->set($data);

						return redirect()->to(base_url('/inicio'));
					} else if ($data2[0]['estado'] == 2) {
						session()->setFlashdata('mensaje', 'Usuario inactivo');
						return redirect()->to(base_url('/'));
						exit;
					} else {
						session()->setFlashdata('mensaje', 'Usuario no registrado');
						return redirect()->to(base_url('/'));
						exit;
					}
				} else {
					session()->setFlashdata('mensaje', 'Usuario o contraseña incorrecta');
					return redirect()->to(base_url('/'));
					exit;
				}
			} else {
				session()->setFlashdata('mensaje', 'Usuario no registrado');
				return redirect()->to(base_url('/'));
				exit;
			}
		}
	}

	public function salir()
	{
		$session = session();
		$session->destroy();
		return redirect()->to(base_url('/'));
		exit;
	}
	public function reporte()
	{
		// $spreadsheet = new Spreadsheet();
        // $sheet = $spreadsheet->getActiveSheet();
        // $sheet->setCellValue('A1', 'Hello World !');
        // $writer = new Xlsx($spreadsheet);
        // $writer->save('prueba.xlsx');
	}
}
