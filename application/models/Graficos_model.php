<?php
class Graficos_model extends CI_Model {

    protected $table = 'progresso';
    protected $primaryKey = 'id_progresso';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    /*graficos para criar e listar*/
    /*
        * total desbravadores
        * media de progresso 
        * classes ativas
        * unidade de destaque

        * evoluçõa geral(media da evolução de todos os desbravadores)
        * menbros por classe
        * destaque do mês
        * desbravadores atrasados
    
    */
    public function get_total_dbv(){
        $this->db->select('select count(*) as qtd');
         $this->db->from('desbravadores');
            $query = $this->db->get();
                $result = $query->result();
                    return $result;

    }
    public function medir_progresso(){
        $dados_retorno =[];
        //total questoes 
         $this->db->select('count(*) as qtd');
         $this->db->from('itens_classe');
         $this->db->where('classe_id',1);
            $query_total_questoes = $this->db->get();
                $dados_retorno['totalQuestoes'] = $query_total_questoes->result();
        //total preenchidos  (somente da classe 1)
                 $this->db->select('count(*) as qtd');
                $this->db->from('progresso');
                $this->db->where('id_classe',1);
                    $query_total_questoes_respondidas = $this->db->get();
                        $dados_retorno['totalQuestoesRespondidas'] = $query_total_questoes_respondidas->result();
        //porcentagem concluido

        return $dados_retorno;
    }
    public function classes_ativas(){
    
        $this->db->select('c.nome as nome_classe, count(*) as qtd');
        $this->db->from('desbravadores d');            
        $this->db->join('classe c','d.id_classe = c.id');
        $this->db->group_by('c.nome');
            return $this->db->get()->result();
    }


    public function unidade_destaque(){
        //MOKADO
        return [['unidade'=>'Atalaia','porcentagem'=>'82']];
    }
    public function evolucao_geral(){
        //MOKADO
        return [
                ['valor'=>'40','mes'=>'jan'],
                ['valor'=>'35','mes'=>'fev'],
                ['valor'=>'49','mes'=>'marc'],
                ['valor'=>'67','mes'=>'abr'],
                ['valor'=>'73','mes'=>'mai'],
                ['valor'=>'54','mes'=>'jun'],
                ['valor'=>'57','mes'=>'jul'],
                ['valor'=>'73','mes'=>'ago'],
                ['valor'=>'79','mes'=>'set'],
                ['valor'=>'82','mes'=>'out'],
                ['valor'=>'87','mes'=>'nov'],
                ['valor'=>'91','mes'=>'dez']
               ];
    }

    public function destaque_mes(){
        //MOKADO
        return [
            ['desbravador'=>'Ana','porcentagem'=>'85'],
            ['desbravador'=>'João','porcentagem'=>'74'],
            ['desbravador'=>'Paulo','porcentagem'=>'95']
        ];
    }
    public function atrasados_mes(){
        //MOKADO
        return [
            ['desbravador'=>'Jhon','porcentagem'=>'35'],
            ['desbravador'=>'Poul','porcentagem'=>'24'],
            ['desbravador'=>'Peter','porcentagem'=>'45']
        ];
    }

    public function get_all(): array {
        

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