<?php
namespace App\Models;
use CodeIgniter\Model;

class ModelInicio extends Model
{
    protected $table      = 'tb_detalle';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_factura','id_servicio', 'fechai','fechaf','monto','abono','cuota', 'observaciones','estadod','estado'];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    //funcion que retorna todos los registros
    public function getInicios()
    {
        return $this->findAll();
    }

    

    //funcion para guardar un cliente
    public function add($datos)
    {
        return $this->save($datos);
    }

    //modelo para edit
    public function getInicio($id)
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
