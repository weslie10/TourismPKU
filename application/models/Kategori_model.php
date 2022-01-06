<?php
class Kategori_model extends CI_Model
{
    private $table = "kategori";

    function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    function create_kategori($dataKategori)
    {
        return $this->db->insert($this->table, $dataKategori);
    }

    function update_kategori($dataKategori, $where)
    {
        return $this->db->update($this->table, $dataKategori, $where);
    }

    function delete_kategori($where)
    {
        return $this->db->delete($this->table, $where);
    }
}
