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
		if (!empty($_FILES)) {
			$count = count($_FILES['files']['name']);
			for ($i = 0; $i < $count; $i++) {

				$_FILES['file']['name'] = $_FILES['files']['name'][$i][0];
				$_FILES['file']['type'] = $_FILES['files']['type'][$i][0];
				$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i][0];
				$_FILES['file']['error'] = $_FILES['files']['error'][$i][0];
				$_FILES['file']['size'] = $_FILES['files']['size'][$i][0];

				$upload = $this->do_upload("file");
				echo "<pre>";
				print_r($upload);
				echo "</pre><br>";
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

	public function do_upload($type)
	{
		$new_name = time() . str_replace(' ', '_', $_FILES[$type]['name']);

		$config['upload_path']          = 'uploads/';
		$config['allowed_types']        = 'jpg|jpeg|png';
		$config['max_size']             = 5120;
		$config['file_name']            = $new_name;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload($type)) {
			$error = array('error' => $this->upload->display_errors());
			return array("status" => false, "error" => $error);
		} else {
			return array("status" => true, "pic" => $config['upload_path'] . $new_name);
		}
	}
}
