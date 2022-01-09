<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Titik_Rute extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('TitikRute_model');
	}

	public function index()
	{
		$listTitikRute = $this->TitikRute_model->get_all();
		$data = [
			"title" => "Table Titik Rute",
			"active" => "titik_rute",
			"listTitikRute" => $listTitikRute,
		];
		loadViews($this, 'titik_Rute/index', $data);
	}

	public function tambah()
	{
		$data = [
			"title" => "Tambah Titik Rute",
			"active" => "titik_rute",
		];
		loadViews($this, 'titik_Rute/tambah', $data);
	}

	public function all()
	{
		header('Content-Type: application/json');
		echo json_encode($this->TitikRute_model->get_all());
	}

	public function add()
	{
		header('Content-Type: application/json');
		$stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
		$listTitik = json_decode($stream_clean);
		foreach ($listTitik as $titik) {
			$data = [
				"lat_coord" => $titik->lat,
				"long_coord" => $titik->long,
			];
			$this->TitikRute_model->create_titik_rute($data);
		}
		echo json_encode(['status' => true, "message" => "Berhasil memasukkan titik rute"]);
	}

	public function delete($id)
	{
		header('Content-Type: application/json');
		$this->TitikRute_model->delete_titik_rute(["id" => $id]);
		echo json_encode(['status' => true, "message" => "Berhasil menghapus titik rute"]);
	}
}
