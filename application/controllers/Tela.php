<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tela extends CI_Controller{
    public function index(){
        $data['titulo'] = "telinha Vue";
        $this->load->view('Tela',$data);
    }

    public function api_dados(){
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode([
            'success' => true,
                'data' => [
                    'nome' => 'João Silva',
                    'email' => 'joao@exemplo.com',
                    'status' => 'ativo'
                ]
        ]));
    }
}
