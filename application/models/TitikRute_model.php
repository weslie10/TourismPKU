<?php
class TitikRute_model extends CI_Model
{
    private $table = "titik_rute";

    function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    function get_last_id()
    {
        $this->db->select_max('id');
        return $this->db->get($this->table)->row();
    }

    function get_nearest_point($lat, $long, $radius)
    {
        $latBound1 = $lat - ($radius / 2);
        $latBound2 = $lat + ($radius / 2);
        $longBound1 = $long - ($radius / 2);
        $longBound2 = $long + ($radius / 2);
        $this->db->where("lat_coord BETWEEN " . $latBound1 . " AND " . $latBound2);
        $this->db->where("long_coord BETWEEN " . $longBound1 . " AND " . $longBound2);
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
