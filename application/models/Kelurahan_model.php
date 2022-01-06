<?php
class Kelurahan_model extends CI_Model
{
    private $table = "kelurahan";

    function get_all()
    {
        $this->db->select('kelurahan.*, k.nama as nama_kecamatan');
        $this->db->join('kecamatan as k', "k.id = kelurahan.kecamatan_id");
        return $this->db->get($this->table)->result();
    }

    function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    function create_kelurahan($dataKelurahan)
    {
        return $this->db->insert($this->table, $dataKelurahan);
    }

    function update_kelurahan($dataKelurahan, $where)
    {
        return $this->db->update($this->table, $dataKelurahan, $where);
    }

    function delete_kelurahan($where)
    {
        return $this->db->delete($this->table, $where);
    }
}
