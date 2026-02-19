<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Desbravadores extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Desbravador_model');
        $this->load->model('Unidade_model');
        $this->load->library('form_validation');
    }

    public function index(){
        $data['titulo'] = "telinha Vue Desbravadores";
        $this->load->view('desbravadores/index',$data);
    }

    public function api_dados(){
        $arr = $this->Desbravador_model->get_all();
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode([
            'success' => true,
                'data' =>  $arr
        ]));
    }

    public function inserir(){
        
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        
                // if($this->config->item('csrf_protection') === TRUE){
                //     $csrf_name = $this->security->get_csrf_token_name();
                //     $csrf_value = $this->security->get_csrf_hash();
                //     // Para JSON puro → cliente precisa enviar no body
                //     // Exemplo esperado: { ..., "csrf_test_name": "abc123..." }
                // }
  
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
            if(!isset($dados['nome_completo']) || !isset($dados['id_unidade'])){
                throw new Exception('Campos obrigatórios ausentes',422);
            }
            //validação

              $this->form_validation->set_rules('nome_completo', 'Nome_completo', 'require|trim|min_length[2]|max_length[100]');
              $this->form_validation->set_rules('id_unidade', 'Unidade', 'require|is_natural_no_zero');
              $this->form_validation->set_rules('cargo', 'Cargo', 'trim|max_length[60]');

              if(!$this->form_validation->run()){
                  throw new Exception($this->form_validation->error_string(' | '), 422);  
              }

            //sanitização
            $cargo  = $this->sanitizeString($dados['cargo'] ?? '');
            $id_unidade = intval($dados['id_unidade']);
            $nome_completo = $this->sanitizeString($dados['nome_completo']);
            
            if(strlen(trim($nome_completo)) < 2){
                throw new Exception("Campo 'nome' deve ter pelo menos 2 caracteres", 422);
            }

            if(strlen(trim($nome_completo)) > 100){
                throw new Exception("Campo 'nome' não pode exceder 100 caracteres", 422);
            }

             if($id_unidade <= 0){
                throw new Exception("ID da unidade inválido", 422);
             }

            $data = [
                'nome_completo' => $nome_completo,
                'id_unidade'    => $id_unidade,
                'cargo'         => $cargo ?: 'Membro'
            ];
            //enviar para a model
            $this->Desbravador_model->insert($data);
            //output resultado
            $msg='Dados processados com sucesso';
            $this->enviarMsgSucesso($msg);

        //resultado de erro
        } catch (Exception $e) {
                http_response_code($e->getCode() ?: 500);
                    $this->getMsgError($e); 
        }
    }

    public function atualizar(){

        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');

        try {
            
                if($this->input->method() !== 'post'){
                    throw new Exception('Método não permitido',405);
                }  
                    $json = $this->input->raw_input_stream;
                    $dados = json_decode($json, true);
                    
                    if(json_last_error() !== JSON_ERROR_NONE){
                        throw new Exception('JSON inválido enviado pelo cliente', 400);
                    }
                    
                    if(!$dados['id_desbravador']){
                        throw new Exception('`id_desbravador` e necessario preencher:', 400);
                    }

                    $cargo  = $this->sanitizeString($dados['cargo'] ?? '');
                    $id_unidade = intval($dados['id_unidade']);
                    $id_desbravador = intval($dados['id_desbravador']);
                    $nome_completo = $this->sanitizeString($dados['nome_completo']);

                    if(strlen(trim($nome_completo)) < 2){
                        throw new Exception("Campo 'nome' deve ter pelo menos 2 caracteres", 422);
                    }

                    if(strlen(trim($nome_completo)) > 100){
                        throw new Exception("Campo 'nome' não pode exceder 100 caracteres", 422);
                    }

                    if($id_unidade <= 0){
                        throw new Exception("ID da unidade inválido", 422);
                    }

                    $data = [
                        'nome_completo' => $nome_completo,
                        'id_unidade'    => $id_unidade,
                        'cargo'         => $cargo ?: 'Membro'
                    ];
                    

                    $retorno = $this->Desbravador_model->update($id_desbravador, $data);

                    $resposta = ['sucesso'=>true,'mensagem'=>'Atualizado com sucesso'];
                            if(!$retorno){
                                $resposta = ['sucesso'=>false,'mensagem'=>'Erro ao Atualizar !'];
                            }

                                $this->output
                                    ->set_content_type('application/json')
                                    ->set_output(json_encode($resposta));

        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            $this->getMsgError($e);
        }
 
    }

    public function deletar(){

        try {
                if($this->input->method() !== 'post'){
                    throw new Exception('Método não permitido',405);
                } 
                    
                    $json = $this->input->raw_input_stream;
                    $dados = json_decode($json, true);
                      
                        if(json_last_error() !== JSON_ERROR_NONE){
                            throw new Exception('JSON inválido enviado pelo cliente', 400);
                        }
                        
                        if(!$dados['id_desbravador']){
                            throw new Exception('`id_desbravador` e necessario preencher:', 400);
                        }
                        
                        $id_desbravador = intval($dados['id_desbravador']);
                        $retorno = $this->Desbravador_model->delete($id_desbravador);

                            $resposta = ['sucesso'=>true,'mensagem'=>'excluido com sucesso'];
                            
                                if(!$retorno){
                                    var_dump($this->input->method());
                                    $resposta = ['sucesso'=>false,'mensagem'=>'Erro ao excluir !'];
                                }

                                    $this->output
                                        ->set_content_type('application/json')
                                        ->set_output(json_encode($resposta));            
        
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
              $this->getMsgError($e);
        }

    }

    public function listar_json(){
         $arr = $this->Desbravador_model->get_all();
       
       try{
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success'=>true,
                'data'=>$arr
            ]));
       }catch(Exception $e){
                http_response_code($e->getCode() ?: 500);
                $this->getMsgError($e);
       }
       

    }

    private function sanitizeString(string $input){
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    private function getMsgError($e){
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'sucesso'=>false,
                    'mensagem'=>$e->getMessage()
                ]));
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
   
private function jsonResponse($success, $message='', $data=null, $status = 200){
    $response = [
        'success' => $success,
        'message' => $message
    ];
    
    if($data !== null) $response['data'] = $data;

    $this->output
    ->set_status_header($status)
    ->set_content_type('application/json')
    ->set_output(json_encode($response));
}

}
