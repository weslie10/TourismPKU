<?php
class TitikRute_model extends CI_Model
{
    private $table = "titik_rute";

    function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    function create_titik_rute($dataTitikRute)
    {
        return $this->db->insert($this->table, $dataTitikRute);
    }

    function update_titik_rute($dataTitikRute, $where)
    {
        return $this->db->update($this->table, $dataTitikRute, $where);
    }

    function delete_titik_rute($where)
    {
        return $this->db->delete($this->table, $where);
    }
}
