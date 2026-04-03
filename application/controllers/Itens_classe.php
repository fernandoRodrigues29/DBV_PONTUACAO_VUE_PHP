<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Itens_classe extends CI_Controller{

 public function __construct() {
        parent::__construct();
        $this->load->model('Itens_classe_model');
        $this->load->library('form_validation');
    }

    public function index() {
        
       $this->listar_json();
    }
    
    public function listar_json(){
         $arr = $this->Itens_classe_model->get_all();
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
            if(!isset($dados['desbravador']) || !isset($dados['id_unidade'])){
                throw new Exception('Campos obrigatórios ausentes',422);
            }
                $campos = ['hino','uniforme','atividades','presenca'];
                foreach ($campos as $campo) {
                    if(isset($dados[$campo])){
                        $dados[$campo] = $dados[$campo] ? 's' : 'n';
                    }
                }



            //validação
              $this->form_validation->set_data($dados);  
              $this->form_validation->set_rules('desbravador', 'desbravador', 'required|trim|min_length[1]|max_length[100]');
              $this->form_validation->set_rules('id_unidade', 'id_unidade', 'required|trim|min_length[1]|max_length[100]');
              $this->form_validation->set_rules('presenca', 'presenca', 'trim|min_length[1]|max_length[100]');
              $this->form_validation->set_rules('hino', 'hino', 'trim|min_length[1]|max_length[100]');
              $this->form_validation->set_rules('uniforme', 'uniforme', 'trim|min_length[1]|max_length[100]');
              $this->form_validation->set_rules('atividades', 'atividades', 'trim|min_length[1]|max_length[100]');
                
              if(!$this->form_validation->run()){
                   log_message('error', '[cantinho][inserir] error ao validar');
                  throw new Exception($this->form_validation->error_string(' | '), 422);  
              }
                
            //sanitização
            $desbravador  = $this->sanitizeString($dados['desbravador'] ?? '');
            $id_unidade = $this->sanitizeString($dados['id_unidade']);
            


            $presenca = isset($presenca) ? $this->sanitizeString($dados['presenca']) : null;
            $hino = isset($hino) ? $this->sanitizeString($dados['hino']) : null;
            $uniforme = isset($uniforme) ? $this->sanitizeString($dados['uniforme']) : null;
            $atividades = isset($atividades) ? $this->sanitizeString($dados['atividades']) : null;
           
            $data = [
                'id_dbv' => $desbravador,
                'id_unit'    => $id_unidade,
                'presenca'    => $presenca,
                'uniforme'    => $uniforme,
                'atividades'    => $atividades,
                'hino'    => $hino
            ];

            //enviar para a model
            $this->Itens_classe_model->insert($data);
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
                    
                    if(!$dados['id_cantinho']){
                        throw new Exception('`id_cantinho` e necessario preencher:', 400);
                    }
                    
                    if(!$dados['id_unidade']){
                        throw new Exception('`id_unidade` e necessario preencher:', 400);
                    }

                    if(!$dados['desbravador']){
                        throw new Exception('`desbravador` e necessario preencher:', 400);
                    }

                    $id_unidade = intval($dados['id_unidade']);
                    $id_cantinho = intval($dados['id_cantinho']);
                    
                    $data['id_dbv']  = $dados['desbravador'];
                    $data['id_unit'] = $id_unidade;
                  

                    if(isset($dados['atividades'])){
                        $data['atividades'] =  $this->sanitizeString($dados['atividades'] ?? '');
                    }

                    if(isset($dados['presenca'])){
                        $data['presenca'] =  $this->sanitizeString($dados['presenca'] ?? '');
                    }

                    if(isset($dados['uniforme'])){
                        $data['uniforme'] =  $this->sanitizeString($dados['uniforme'] ?? '');
                    }

                    if(isset($dados['hino'])){
                        $data['hino'] =  $this->sanitizeString($dados['hino'] ?? '');
                    }
                    
                    
                    if(empty($data)){
                        throw new Exception('Nenhum campo para atualizar',422);
                    }

                    $this->form_validation->set_data($dados);
                    $this->form_validation->set_rules('id_cantinho', 'id', 'required');
                    $this->form_validation->set_rules('id_unidade', 'id_cantinho', 'required');
                    $this->form_validation->set_rules('desbravador', 'id_desbravador', 'required');
                    $this->form_validation->set_rules('atividades', 'atividades', 'trim|min_length[1]|max_length[1]');
                    $this->form_validation->set_rules('presenca', 'presenca', 'trim|min_length[1]|max_length[1]');
                    $this->form_validation->set_rules('uniforme', 'uniforme', 'trim|min_length[1]|max_length[1]');
                    $this->form_validation->set_rules('hino', 'hino', 'trim|min_length[1]|max_length[1]');
              
                        if(!$this->form_validation->run()){
                            throw new Exception($this->form_validation->error_string(' | '), 422);  
                        }


                    $retorno = $this->Itens_classe_model->update($id_cantinho, $data);
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
                        
                        if(!$dados['id']){
                            throw new Exception('`id` e necessario preencher:', 400);
                        }
                        
                        $id = intval($dados['id']);
                        $retorno = $this->Itens_classe_model->delete($id);

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
