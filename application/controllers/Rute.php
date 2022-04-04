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
		$data = [
			"title" => "Table Rute",
			"active" => "rute",
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
		$listRute = $this->Rute_model->get_all();

		for ($i = 0; $i < count($listRute) - 1; $i++) {
			$check = $listRute[$i]->titik_awal == $listRute[$i + 1]->titik_akhir && $listRute[$i]->titik_akhir == $listRute[$i + 1]->titik_awal;
			if ($check) {
				$listRute[$i]->id_kedua = $listRute[$i + 1]->id;
				array_splice($listRute, $i + 1, 1);
				$i--;
			} else {
				if (!isset($listRute[$i]->id_kedua)) {
					$listRute[$i]->id_kedua = null;
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
		$this->Rute_model->create_rute(array_map(function ($rute) {
			return [
				"titik_awal" => $rute->titik_awal->id,
				"titik_akhir" => $rute->titik_akhir->id,
			];
		}, $listRute));
		echo json_encode(['status' => true, "message" => "Berhasil memasukkan rute"]);
	}

	public function delete($id)
	{
		header('Content-Type: application/json');
		$this->Rute_model->delete_rute(["id" => $id]);
		echo json_encode(['status' => true, "message" => "Berhasil menghapus rute"]);
	}
}
