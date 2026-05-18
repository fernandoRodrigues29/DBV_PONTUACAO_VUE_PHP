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
        /*
            select distinct dbs.id_desbravador,dbs.nome_completo,clss.nome,clss.id as id_classe  
            from progresso as p  
            INNER JOIN itens_classe as ic ON p.id_item = ic.id
            INNER JOIN desbravadores as dbs ON p.id_dbv = dbs.id_desbravador
            INNER JOIN classe as clss ON p.id_classe = clss.id
        */

        $this->db->distinct();
        $this->db->select('d.id_desbravador,d.nome_completo as nome_desbravador,c.nome as classe,c.id as id_classe');
        // $this->db->select('d.id_desbravador,d.nome_completo as nome_desbravador,c.nome as classe,ic.item');
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
    //listar desbravador unidade
    public function get_itens_marcados_por_id($id): array{
            $this->db->distinct();
            $this->db->select('ic.item, p.id_item, dbs.nome_completo, dbs.id_desbravador');
            $this->db->from('progresso p');
            $this->db->join('itens_classe ic', 'p.id_item = ic.id');
            $this->db->join('desbravadores dbs', 'p.id_dbv = dbs.id_desbravador');
            $this->db->where('dbs.id_desbravador', $id);
                $query = $this->db->get();
                    $result = $query->result();
                        return $result;

    }

    public function insert(array $data): ?int 
    {
        
        $lista_itens_marcados = $data['itens_marcados'] ?? [];
        unset($data['itens_marcados']);

        if(empty($lista_itens_marcados) || !is_array($lista_itens_marcados)){
            log_message('error','Nenhum item marcado foi enviado');
                return null;
        }

        $insert_data = [];

        foreach ($lista_itens_marcados as $linha) {
            $insert_data[] = [
                'id_classe'=>$data['id_classe'],
                'id_dbv'=>$data['id_dbv'],
                'id_item'=>$linha
            ];
        }

        $sucesso = $this->db->insert_batch($this->table, $insert_data);

            if(!$sucesso){
                log_message('error',json_encode($this->db->error()));
                    return null;
            }
        
        return $this->db->insert_id();
    }

    public function update(int $id, array $data): bool 
    {
            echo "teste atualizacao";
            exit;

            //  $sucesso = $this->db
        //  ->where($this->primaryKey, $id)
        //  ->update($this->table, $data);

        //  if(!$sucesso){
        //     log_message('error',json_encode($this->db->error()));
        //  }

        //  return $sucesso;   
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