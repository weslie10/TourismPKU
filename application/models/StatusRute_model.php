<?php
class StatusRute_model extends CI_Model
{
    private $table = "status_rute";

    function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    function get_by_rute_id($id)
    {
        $this->db->where('rute_id', $id);
        return $this->db->get($this->table)->result();
    }

    function create_status_rute($dataStatusRute)
    {
        return $this->db->insert_batch($this->table, $dataStatusRute);
    }

    function update_status_rute($dataStatusRute, $where)
    {
        return $this->db->update($this->table, $dataStatusRute, $where);
    }

    function delete_status_rute($where)
    {
        return $this->db->delete($this->table, $where);
    }
}
