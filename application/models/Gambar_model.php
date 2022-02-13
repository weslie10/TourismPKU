<?php
class Gambar_model extends CI_Model
{
    private $table = "gambar";

    function get_all($id)
    {
        $this->db->select('gambar.*, w.nama as nama_wisata, w.gambar as background');
        $this->db->join('wisata w', 'w.id = gambar.wisata_id');
        $this->db->where('wisata_id', $id);
        return $this->db->get($this->table)->result();
    }

    function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    function get_last_id()
    {
        $this->db->select_max('id');
        return $this->db->get($this->table)->row();
    }

    function create_gambar($dataGambar)
    {
        return $this->db->insert($this->table, $dataGambar);
    }

    function update_gambar($dataGambar, $where)
    {
        return $this->db->update($this->table, $dataGambar, $where);
    }

    function delete_gambar($where)
    {
        return $this->db->delete($this->table, $where);
    }
}
