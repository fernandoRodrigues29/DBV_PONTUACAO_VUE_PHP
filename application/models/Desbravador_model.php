<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Desbravador_model extends CI_Model {
public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Listar todos com nome da unidade
    public function get_all()
    {
        $this->db->select('d.*, u.nome_unidade, u.classe_base');
        $this->db->from('desbravadores d');
        $this->db->join('unidades u', 'u.id_unidade = d.id_unidade', 'left');
        $this->db->order_by('u.nome_unidade', 'ASC');
        $this->db->order_by('d.nome_completo', 'ASC');
        return $this->db->get()->result();
    }

    // Buscar um por ID
    public function get_by_id($id)
    {
        $this->db->select('d.*, u.nome_unidade,u.classe_base');
        $this->db->from('desbravadores d');
        $this->db->join('unidades u', 'u.id_unidade = d.id_unidade', 'left');
        $this->db->where('d.id_desbravador', $id);
        return $this->db->get()->row();
    }

    // Inserir
    public function insert($data)
    {
        $this->db->insert('desbravadores', $data);
        return $this->db->insert_id();
    }

    // Atualizar
    public function update($id, $data)
    {
       try {
            $this->db->where('id_desbravador', $id);
            return $this->db->update('desbravadores', $data);
       } catch (Exception $e) {
        var_dump($e->getMessage());
       }
      
    }

    // Deletar (com proteção: só se não tiver pontuação)
    public function delete($id)
    {
        // Verifica se tem pontuação lançada
        $this->db->where('id_desbravador', $id);
        $count = $this->db->count_all_results('pontuacao_diaria');

        if ($count > 0) {
            return false; // não permite excluir
        }

        $this->db->where('id_desbravador', $id);
        return $this->db->delete('desbravadores');
    }

    // Outros métodos semelhantes ao Unidade_model...
    // (get_by_id, insert, update, delete)
}