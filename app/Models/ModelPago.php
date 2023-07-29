<?php
namespace App\Models;
use CodeIgniter\Model;

class ModelPago extends Model
{
    protected $table      = 'tb_pago';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_detalle','fecha', 'numdoc','monto','estado'];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    //funcion que retorna todos los registros
    public function getPagos()
    {
        return $this->findAll();
    }

    //funcion para guardar un cliente
    public function add($datos)
    {
        return $this->save($datos);
    }

    //modelo para edit
    public function getPago($id)
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
