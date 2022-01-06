<?php
class JamRute_model extends CI_Model
{
    private $table = "jam_rute";

    function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    function create_jam_rute($dataJamRute)
    {
        return $this->db->insert($this->table, $dataJamRute);
    }

    function update_jam_rute($dataJamRute, $where)
    {
        return $this->db->update($this->table, $dataJamRute, $where);
    }

    function delete_jam_rute($where)
    {
        return $this->db->delete($this->table, $where);
    }
}
