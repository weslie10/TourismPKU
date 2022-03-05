<?php
class Rute_model extends CI_Model
{
    private $table = "rute";

    function get_all()
    {
        $this->db->select("rute.*, tr1.lat_coord as lat_awal, r.id as id_kedua, tr1.long_coord as long_awal, tr2.lat_coord as lat_akhir, tr2.long_coord as long_akhir");
        $this->db->join('rute as r', 'rute.titik_awal = r.titik_akhir and rute.titik_akhir = r.titik_awal', 'left');
        $this->db->join('titik_rute as tr1', 'tr1.id = rute.titik_awal');
        $this->db->join('titik_rute as tr2', 'tr2.id = rute.titik_akhir');
        return $this->db->get($this->table)->result();
    }

    function get_last_id()
    {
        $this->db->select_max('id');
        return $this->db->get($this->table)->row();
    }

    function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    function create_rute($dataRute)
    {
        return $this->db->insert_batch($this->table, $dataRute);
    }

    function update_rute($dataRute, $where)
    {
        return $this->db->update($this->table, $dataRute, $where);
    }

    function delete_titik($id)
    {
        $this->db->where("titik_awal = $id or titik_akhir = $id");
        return $this->db->delete($this->table);
    }

    function delete_rute($where)
    {
        return $this->db->delete($this->table, $where);
    }
}
