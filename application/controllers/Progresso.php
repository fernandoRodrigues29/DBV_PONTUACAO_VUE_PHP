<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Progresso extends CI_Controller{

 public function __construct() {
        parent::__construct();
        $this->load->model('Progresso_model');
        $this->load->model('Itens_classe_model');
        $this->load->library('form_validation');
    }

    public function index() {
        
        $data['pontuacao'] = $this->Progresso_model->get_all();
        $data['title'] = 'Gerenciar Progresso';
        $this->load->view('progresso/index', $data);

    //    $this->listar_json();
    }
    
    public function listar_json(){
        /*trocar*/
         $arr = $this->Progresso_model->get_all();
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success'=>true,
                'data'=>$arr
            ]));
    }

    public function listar_itens_classe_json(){
        /*trocar*/
        $idget=0;
        if(isset($_GET['id'])){
            $idget = intval($_GET['id']);
        }

         $arr = $this->Progresso_model->get_by_id($idget);
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success'=>true,
                'data'=>$arr
            ]));
    }
    public function listar_itens_marcados_por_dbv_json(){
        $id=0;
        if(isset($_GET['id'])){
            $id = intval($_GET['id']);
        }

         $arr = $this->Progresso_model->get_itens_marcados_por_id($id);
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success'=>true,
                'data'=>$arr
            ]));
    }

    public function inserir(){
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
      
        try {
            
            if($this->input->method() !== 'post'){
                throw new Exception('Método não permitido',405);
            }  

                $json = $this->input->raw_input_stream;
                $dados = json_decode($json, true);
           
            //validar JSON
            
            // echo "<pre>";
            // print_r($dados);
            // echo "</pre>";
            // exit;
            
            if(json_last_error() !== JSON_ERROR_NONE){
                throw new Exception('JSON inválido enviado pelo cliente', 400);
            }
            //validar campos obrigatórios
            if(!isset($dados['desbravador']) && !isset($dados['classe_id'])){
                throw new Exception('Campos obrigatórios ausentes',422);
            }
            
            //validação
              $this->form_validation->set_data($dados);  
              $this->form_validation->set_rules('desbravador', 'id do Desbravador', 'required|trim|min_length[1]|max_length[100]');
              $this->form_validation->set_rules('classe_id', 'id da classe', 'required|trim|min_length[1]|max_length[100]');
              $this->form_validation->set_rules('itens_marcados[]', 'itens marcados', 'required');
                
              if(!$this->form_validation->run()){
                   log_message('error', '[progresso][inserir] error ao validar');
                  throw new Exception($this->form_validation->error_string(' | '), 422);  
              }
                
            //sanitização
            
            $id_classe = isset($dados['classe_id']) ? intval($dados['classe_id']) : null;
            $id_desbravador = isset($dados['desbravador']) ? intval($dados['desbravador']) : null;
            $itens_marcados = isset($dados['itens_marcados']) ? $dados['itens_marcados'] : null;
            
            $data = [
                'id_progresso' => NULL,
                'id_classe'    => $id_classe,
                'id_dbv'    => $id_desbravador,
                'itens_marcados'    => $itens_marcados,
            ];
            
            //enviar para a model
            $this->Progresso_model->insert($data);
            //output resultado
            $msg='Dados processados com sucesso';
            $this->enviarMsgSucesso($msg);

        //resultado de erro
        } catch (Exception $e) {
            log_message('error', '[unidade][inserir] error de função');
                http_response_code($e->getCode() ?: 500);
                    $this->getMsgError($e); 
        }
    }
      public function atualizar(){
        
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');

        try {
            
         
                if($this->input->method() !== 'put'){
                    throw new Exception('Método não permitido',405);
                }  
                    $json = $this->input->raw_input_stream;
                    $dados = json_decode($json, true);
                    
                 
                    if(json_last_error() !== JSON_ERROR_NONE){
                        throw new Exception('JSON inválido enviado pelo cliente', 400);
                    }
                    
                    if(!$dados['classe_id']){
                        throw new Exception('`id da classe` e necessario preencher:', 400);
                    }
                    
                    $id_classe = intval($dados['classe_id']);
                    $id_desbravador = intval($dados['desbravador']);
                    
                    $data['id_dbv']  = $id_desbravador;
                    $data['classe_id'] = $id_classe;
                    
                    if(empty($data)){
                        throw new Exception('Nenhum campo para atualizar',422);
                    }

                    //validação
                    $this->form_validation->set_data($dados);  
                    $this->form_validation->set_rules('desbravador', 'id do Desbravador', 'required|trim|min_length[1]|max_length[100]');
                    $this->form_validation->set_rules('classe_id', 'id da classe', 'required|trim|min_length[1]|max_length[100]');
                    $this->form_validation->set_rules('itens_marcados[]', 'itens marcados', 'required');
                
                    if(!$this->form_validation->run()){
                        log_message('error', '[progresso][inserir] error ao validar');
                        throw new Exception($this->form_validation->error_string(' | '), 422);  
                    }

                    //deletar    
                        
                        $this->db->where('id_classe',$id_classe);
                        $this->db->where('id_dbv',$id_desbravador);
                        $this->db->delete('progresso');


                    //atualizar
                    // $retorno = $this->Progresso_model->update($id_cantinho, $data);
                    //     $resposta = ['sucesso'=>true,'mensagem'=>'Atualizado com sucesso'];
                    //         if(!$retorno){

                    //             $resposta = ['sucesso'=>false,'mensagem'=>'Erro ao Atualizar !'];
                    //         }

                    //             $this->output
                    //                 ->set_content_type('application/json')
                    //                 ->set_output(json_encode($resposta));

                    //atualizar(inserir)
                                $id_classe = isset($dados['classe_id']) ? intval($dados['classe_id']) : null;
                                $id_desbravador = isset($dados['desbravador']) ? intval($dados['desbravador']) : null;
                                $itens_marcados = isset($dados['itens_marcados']) ? $dados['itens_marcados'] : null;
                                
                                $data = [
                                    'id_progresso' => NULL,
                                    'id_classe'    => $id_classe,
                                    'id_dbv'    => $id_desbravador,
                                    'itens_marcados'    => $itens_marcados,
                                ];
                                
                                //enviar para a model
                                $this->Progresso_model->insert($data);
                                //output resultado
                                $msg='Dados processados com sucesso';
                                $this->enviarMsgSucesso($msg);
        } catch (Exception $e) {
            log_message('error', '[unidade][atualizar] error de função');
            http_response_code($e->getCode() ?: 500);
            $this->getMsgError($e);
        }
 
    }
    public function deletar(){

        try {
                if($this->input->method() !== 'delete'){
                    throw new Exception('Método não permitido',405);
                } 
                    
                    $json = $this->input->raw_input_stream;
                    $dados = json_decode($json, true);
                      
                        if(json_last_error() !== JSON_ERROR_NONE){
                            throw new Exception('JSON inválido enviado pelo cliente', 400);
                        }
                        
                   if(!$dados['id_classe']){
                        throw new Exception('`id da classe` e necessario preencher:', 400);
                    }
                   if(!$dados['id_desbravador']){
                        throw new Exception('`id do desbravador` e necessario preencher:', 400);
                    }
                    
                    $id_classe = intval($dados['id_classe']);
                    $id_desbravador = intval($dados['id_desbravador']);
                    
                    $data['id_dbv']  = $id_desbravador;
                    $data['classe_id'] = $id_classe;
                        
                        $this->db->where('id_classe',$id_classe);
                        $this->db->where('id_dbv',$id_desbravador);
                        $retorno=$this->db->delete('progresso');

                        
                            $resposta = ['sucesso'=>true,'mensagem'=>'excluido com sucesso'];
                            
                                if(!$retorno){
                                    $resposta = ['sucesso'=>false,'mensagem'=>'Erro ao excluir !'];
                                }

                                    $this->output
                                        ->set_content_type('application/json')
                                        ->set_output(json_encode($resposta));            
        
        } catch (Exception $e) {
            log_message('error', '[cantinho][deletar] error de função');
            http_response_code($e->getCode() ?: 500);
              $this->getMsgError($e);
        }

    }

    
    private function sanitizeString(string $input): string{
        // return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
        return $input;    
    }
    
    private function enviarMsgSucesso($msg){
        $this->output
                ->set_status_header(201)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                        'sucesso'=>true,
                        'mensagem'=>$msg,
                        // 'mensagem'=>'Dados processados com sucesso',
                       ]));
    }
    
    private function getMsgError($e){
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'sucesso'=>false,
                    'mensagem'=>$e->getMessage()
                ]));
    }
}
