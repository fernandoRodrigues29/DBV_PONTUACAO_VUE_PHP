<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unidades extends CI_Controller{

 public function __construct() {
        parent::__construct();
        $this->load->model('Unidade_model');
        $this->load->library('form_validation');
    }

    public function index() {
            $data['unidades'] = $this->Unidade_model->get_all();
            $data['title'] = 'Gerenciar Unidades';
                $this->load->view('unidades/index', $data);
    }
    
    public function listar_json(){
         $arr = $this->Unidade_model->get_all();
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
            if(json_last_error() !== JSON_ERROR_NONE){
                throw new Exception('JSON inválido enviado pelo cliente', 400);
            }
            //validar campos obrigatórios
            if(!isset($dados['nome_unidade']) || !isset($dados['classe_base'])){
                throw new Exception('Campos obrigatórios ausentes',422);
            }
            // var_dump($dados);
            //validação
              $this->form_validation->set_data($dados);  
              $this->form_validation->set_rules('nome_unidade', 'nome_unidade', 'required|trim|min_length[2]|max_length[100]');
              $this->form_validation->set_rules('classe_base', 'classe_base', 'required|trim|min_length[2]|max_length[100]');
                
              if(!$this->form_validation->run()){
                   log_message('error', '[unidade][inserir] error ao validar');
                  throw new Exception($this->form_validation->error_string(' | '), 422);  
              }
                
            //sanitização
            $nome_unidade  = $this->sanitizeString($dados['nome_unidade'] ?? '');
            $classe_base = $this->sanitizeString($dados['classe_base']);
            
           
            $data = [
                'nome_unidade' => $nome_unidade,
                'classe_base'    => $classe_base,
            ];

            //enviar para a model
            $this->Unidade_model->insert($data);
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
                    
                    if(!$dados['id_unidade']){
                        throw new Exception('`id_unidade` e necessario preencher:', 400);
                    }



                    $id_unidade = intval($dados['id_unidade']);
                    
                    if(isset($dados['nome_unidade'])){
                        $data['nome_unidade'] =  $this->sanitizeString($dados['nome_unidade'] ?? '');
                    }
                    
                    if(isset($dados['classe_base'])){
                        $data['classe_base'] =  $this->sanitizeString($dados['classe_base'] ?? '');
                    }
                    
                    if(empty($data)){
                        throw new Exception('Nenhum campo para atualizar',422);
                    }

                    $this->form_validation->set_data($dados);
                    $this->form_validation->set_rules('id_unidade', 'id', 'required');
                    $this->form_validation->set_rules('nome_unidade', 'Nome_unidade', 'trim|min_length[2]|max_length[100]');
                    $this->form_validation->set_rules('classe_base', 'Classe_base', 'trim|min_length[2]|max_length[100]');
              
                        if(!$this->form_validation->run()){
                            throw new Exception($this->form_validation->error_string(' | '), 422);  
                        }


                    $retorno = $this->Unidade_model->update($id_unidade, $data);
                        $resposta = ['sucesso'=>true,'mensagem'=>'Atualizado com sucesso'];
                            if(!$retorno){

                                $resposta = ['sucesso'=>false,'mensagem'=>'Erro ao Atualizar !'];
                            }

                                $this->output
                                    ->set_content_type('application/json')
                                    ->set_output(json_encode($resposta));

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
                        
                        if(!$dados['id_unidade']){
                            throw new Exception('`id_desbravador` e necessario preencher:', 400);
                        }
                        
                        $id_unidade = intval($dados['id_unidade']);
                        $retorno = $this->Unidade_model->delete($id_unidade);

                            $resposta = ['sucesso'=>true,'mensagem'=>'excluido com sucesso'];
                            
                                if(!$retorno){
                                    $resposta = ['sucesso'=>false,'mensagem'=>'Erro ao excluir !'];
                                }

                                    $this->output
                                        ->set_content_type('application/json')
                                        ->set_output(json_encode($resposta));            
        
        } catch (Exception $e) {
            log_message('error', '[unidade][deletar] error de funçaõ');
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
