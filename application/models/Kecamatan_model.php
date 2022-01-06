<?php
class Kecamatan_model extends CI_Model
{
    private $table = "kecamatan";

    function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    function create_kecamatan($dataKecamatan)
    {
        return $this->db->insert($this->table, $dataKecamatan);
    }

    function update_kecamatan($dataKecamatan, $where)
    {
        return $this->db->update($this->table, $dataKecamatan, $where);
    }

    function delete_kecamatan($where)
    {
        return $this->db->delete($this->table, $where);
    }
}
