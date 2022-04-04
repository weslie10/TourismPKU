<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gambar extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Gambar_model');
		$this->load->model('Wisata_model');
	}

	public function list($id)
	{
		$listGambar = $this->Gambar_model->get_all($id);
		$data = [
			"title" => "Table Gambar",
			"active" => "wisata",
			"wisata_id" => $id,
			"listGambar" => $listGambar,
		];
		loadViews($this, 'gambar/index', $data);
	}

	public function all($id)
	{
		header('Content-Type: application/json');
		$listGambar = $this->Gambar_model->get_all($id);
		if (count($listGambar) > 0) {
			echo json_encode(["status" => "success", "data" => $listGambar]);
		} else {
			echo json_encode(["status" => "empty", "data" => $listGambar]);
		}
	}

	public function tambah($id)
	{
		$listWisata = $this->Wisata_model->get_all();
		$data = [
			"title" => "Table Gambar",
			"active" => "wisata",
			"wisata_id" => $id,
			"listWisata" => $listWisata,
		];
		loadViews($this, 'gambar/tambah', $data);
	}

	public function add($id)
	{
		$data = array();
		if (!empty($_FILES)) {
			$count = count($_FILES['files']['name']);
			for ($i = 0; $i < $count; $i++) {
				$_FILES["file"]['name'] = $_FILES['files']['name'][$i][$i];
				$_FILES["file"]['type'] = $_FILES['files']['type'][$i][$i];
				$_FILES["file"]['tmp_name'] = $_FILES['files']['tmp_name'][$i][$i];
				$_FILES["file"]['error'] = $_FILES['files']['error'][$i][$i];
				$_FILES["file"]['size'] = $_FILES['files']['size'][$i][$i];

				$upload = $this->do_upload("file", $id);
				if ($upload["status"]) {
					$data = [
						"url" => $upload["pic"],
						"wisata_id" => $id,
					];
					$this->Gambar_model->create_gambar($data);
				}
			}
		}
	}

	public function pilih($wisata, $id)
	{
		$this->Wisata_model->pilih_background(["gambar" => $id], ["id" => $wisata]);
		redirect('gambar/list/' . $wisata);
	}

	public function hapus($wisataId, $id)
	{
		$gambar = $this->Gambar_model->get_by_id($id);
		$wisata = $this->Wisata_model->get_by_id($wisataId);
		if ($wisata->gambar == $id) {
			$this->Wisata_model->update_wisata(["gambar" => 0], ["id" => $wisataId]);
		}
		unlink($gambar->url);
		$this->Gambar_model->delete_gambar(["id" => $id]);
		redirect('gambar/list/' . $wisataId);
	}

	public function do_upload($type, $id)
	{
		$gambar = $this->Gambar_model->get_last_id()->id;
		if ($gambar == NULL) {
			$gambar = 0;
		}
		$gambar++;
		$wisata = $this->Wisata_model->get_by_id($id);
		$ext = explode('.', $_FILES[$type]['name']);
		$ext = $ext[count($ext) - 1];
		$new_name = $wisata->nama . "-" . $gambar . "." . $ext;
		$new_name = str_replace(" ", "_", $new_name);
		$new_name = str_replace("'", "", $new_name);
		// $new_name = time() . str_replace(' ', '_', $_FILES[$type]['name']);

		$config['upload_path']          = 'uploads/';
		$config['allowed_types']        = 'jpg|jpeg|png';
		$config['max_size']             = 2048;
		$config['file_name']            = $new_name;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if (!$this->upload->do_upload($type)) {
			$error = array('error' => $this->upload->display_errors());
			var_dump($error);
			return array("status" => false, "error" => $error);
		} else {
			echo "berhasil";
			return array("status" => true, "pic" => $config['upload_path'] . $new_name);
		}
	}
}
