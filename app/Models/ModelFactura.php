<?php
namespace App\Models;
use CodeIgniter\Model;

class ModelFactura extends Model
{
    protected $table      = 'tb_factura';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_cliente','transaccion', 'estado'];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    //funcion que retorna todos los registros
    public function getFacturas()
    {
        return $this->findAll();
    }

    //funcion para guardar un cliente
    public function add($datos)
    {
        return $this->save($datos);
    }

    public function ultimo(){
        return $this->insert_id();
    }

    //modelo para edit
    public function getFactura($id)
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
