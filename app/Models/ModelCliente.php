<?php
namespace App\Models;
use CodeIgniter\Model;

class ModelCliente extends Model
{
    protected $table      = 'tb_clientes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_departamento','nombre', 'nomcom','nit','direccion','telefono','repnom', 'reptel','email','contnom','conttel','fecha','observaciones','estado'];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    //funcion que retorna todos los registros
    public function getClientes()
    {
        return $this->findAll();
    }

    

    //funcion para guardar un cliente
    public function add($datos)
    {
        return $this->save($datos);
    }

    //modelo para edit
    public function getCliente($id)
    {
        return $this->where('id',$id)->first($id);
    }

    public function actualizarDatos($id, $data)
    {
        return $this->update($id,$data);
    }

    public function eliminarDato($id, $data)
    {
        return $this->update($id,$data);
    }
}
?>
