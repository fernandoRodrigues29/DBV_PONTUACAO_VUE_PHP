<?php
class Unidade_model extends CI_Model {

    protected $table = 'unidades';
    protected $primaryKey = 'id_unidade';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all(): array {
        $query = $this->db->order_by('nome_unidade','ASC')
        ->get($this->table);
            return $query->result();
    }

    public function get_by_id(int $id): ?object {
        $query = $this->db
        ->where($this->primaryKey, $id)
        ->get($this->table);

        return $query->num_rows() ? $query->row() : null;
    }

    public function insert(array $data): ?int 
    {
        $sucesso = $this->db->insert($this->table, $data);
            if(!$sucesso){
                log_message('error',json_encode($this->db->error()));
                    return null;
            }
        
        return $this->db->insert_id();
    }

    public function update(int $id, array $data): bool 
    {
         $sucesso = $this->db
         ->where($this->primaryKey, $id)
         ->update($this->table, $data);

         if(!$sucesso){
            log_message('error',json_encode($this->db->error()));
         }

         return $sucesso;   
    }

    public function delete(int $id):bool 
    {
        $sucesso = $this->db
        ->where($this->primaryKey, $id)
        ->delete($this->table);

        if(!$sucesso){
            log_message('error',json_encode($this->db->error()));
        }
    
        return $sucesso;
    }
    
}