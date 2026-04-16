<?php
class Progresso_model extends CI_Model {

    protected $table = 'progresso';
    protected $primaryKey = 'id_progresso';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    public function get_all(): array {
        $this->db->select('d.id_desbravador,d.nome_completo as nome_desbravador,c.nome as classe,ic.item');
        $this->db->from('progresso p');            
        $this->db->join('desbravadores d','d.id_desbravador = p.id_dbv','left');
        $this->db->join('classe c','c.id = p.id_classe','left');
        $this->db->join('itens_classe ic','ic.id = p.id_item','left');
        
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