<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rute extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Rute_model');
	}

	public function index()
	{
		$listRute = $this->Rute_model->get_all();
		$data = [
			"title" => "Table Rute",
			"active" => "rute",
			"listRute" => $listRute,
		];
		loadViews($this, 'rute/index', $data);
	}

	public function tambah()
	{
		$data = [
			"title" => "Tambah Rute",
			"active" => "rute",
		];
		loadViews($this, 'rute/tambah', $data);
	}

	public function all()
	{
		header('Content-Type: application/json');
		$listDuplicate = [];
		$listRute = $this->Rute_model->get_all();
		$flag = true;
		foreach ($listRute as $rute) {
			if ($rute->id_kedua != null) {
				if ($flag) {
					array_push($listDuplicate, $rute->id_kedua);
					$flag = false;
				} else 	$flag = true;
			}
		}
		foreach ($listDuplicate as $duplicate) {
			for ($i = 0; $i < count($listRute); $i++) {
				if ($listRute[$i]->id_kedua == $duplicate) {
					array_splice($listRute, $i, 1);
					break;
				}
			}
		}
		if (count($listRute) > 0) {
			echo json_encode(["status" => "success", "data" => $listRute]);
		} else {
			echo json_encode(["status" => "empty", "data" => $listRute]);
		}
	}

	public function add()
	{
		header('Content-Type: application/json');
		$stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
		$listRute = json_decode($stream_clean);
		foreach ($listRute as $rute) {
			$data = [
				"titik_awal" => $rute->titik_awal->id,
				"titik_akhir" => $rute->titik_akhir->id,
			];
			$this->Rute_model->create_rute($data);
		}
		echo json_encode(['status' => true, "message" => "Berhasil memasukkan rute"]);
	}

	public function delete($id)
	{
		header('Content-Type: application/json');
		$this->Rute_model->delete_rute(["id" => $id]);
		echo json_encode(['status' => true, "message" => "Berhasil menghapus rute"]);
	}
}
