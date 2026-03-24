<?php
class Cantinho_model extends CI_Model {

    protected $table = 'cantinho';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    public function get_all(): array {
        $this->db->select('
        c.id,d.id_desbravador,u.id_unidade,d.nome_completo,u.nome_unidade,c.presenca,c.uniforme,c.atividades,c.hino
        ');

        $this->db->from('cantinho c');

        $this->db->join(
            'unidades u',
            'u.id_unidade = c.id_unit',
            'left'
        );

        $this->db->join(
            'desbravadores d',
            'd.id_desbravador = c.id_dbv',
            'left'
        );

        return $this->db->get()->result();

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