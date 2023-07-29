<?php
namespace App\Models;
use CodeIgniter\Model;

class ModelDepartamento extends Model
{
    protected $table      = 'tb_departamento';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre'];

    //funcion que retorna todos los registros
    public function getDepartamentos()
    {
        return $this->findAll();
    }
}
