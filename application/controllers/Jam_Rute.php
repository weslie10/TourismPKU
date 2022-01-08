<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jam_Rute extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('JamRute_model');
	}

	public function index()
	{
		$listJamRute = $this->JamRute_model->get_all();
		$data = [
			"title" => "Table Jam Rute",
			"active" => "jam_rute",
			"listJamRute" => $listJamRute,
		];
		loadViews($this, 'jam_rute/index', $data);
	}

	public function tambah()
	{
		$data = [
			"title" => "Tambah Jam Rute",
			"active" => "jam_rute",
		];
		loadViews($this, 'jam_rute/tambah', $data);
	}

	public function all()
	{
		header('Content-Type: application/json');
		echo json_encode($this->JamRute_model->get_all());
	}

	public function get_by_rute_id($id)
	{
		header('Content-Type: application/json');
		echo json_encode($this->JamRute_model->get_by_rute_id($id));
	}

	public function add()
	{
		header('Content-Type: application/json');
		$stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
		$listJamRute = json_decode($stream_clean);
		foreach ($listJamRute as $jamRute) {
			$data = [
				"jam" => $jamRute->jam,
				"rute_id" => $jamRute->rute_id,
				"status" => $jamRute->status,
			];
			$this->JamRute_model->create_jam_rute($data);
		}
		echo json_encode(['status' => true, "message" => "Berhasil memasukkan jam rute"]);
	}

	public function update()
	{
		header('Content-Type: application/json');
		$stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
		$listJamRute = json_decode($stream_clean);
		foreach ($listJamRute as $jamRute) {
			$where = [
				"jam" => $jamRute->jam,
				"rute_id" => $jamRute->rute_id,
			];
			$data = [
				"status" => $jamRute->status,
			];
			$this->JamRute_model->update_jam_rute($data, $where);
		}
		echo json_encode(['status' => true, "message" => "Berhasil mengubah jam rute"]);
	}

	public function delete($id)
	{
		header('Content-Type: application/json');
		$this->JamRute_model->delete_jam_rute(["id" => $id]);
		echo json_encode(['status' => true, "message" => "Berhasil menghapus jam rute"]);
	}
}
