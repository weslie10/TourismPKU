<?php
class Wisata_model extends CI_Model
{
    private $table = "wisata";

    function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    function create_wisata($dataWisata)
    {
        return $this->db->insert($this->table, $dataWisata);
    }

    function update_wisata($dataWisata, $where)
    {
        return $this->db->update($this->table, $dataWisata, $where);
    }

    function delete_wisata($where)
    {
        return $this->db->delete($this->table, $where);
    }
}
