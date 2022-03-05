<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Status_Rute extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('StatusRute_model');
	}

	public function index()
	{
		$listStatusRute = $this->StatusRute_model->get_all();
		$data = [
			"title" => "Table Status Rute",
			"active" => "status_rute",
			"listStatusRute" => $listStatusRute,
		];
		loadViews($this, 'status_rute/index', $data);
	}

	public function tambah()
	{
		$data = [
			"title" => "Tambah Status Rute",
			"active" => "status_rute",
		];
		loadViews($this, 'status_rute/tambah', $data);
	}

	public function all()
	{
		header('Content-Type: application/json');
		echo json_encode($this->StatusRute_model->get_all());
	}

	public function get_by_rute_id($id)
	{
		header('Content-Type: application/json');
		echo json_encode($this->StatusRute_model->get_by_rute_id($id));
	}

	public function add()
	{
		header('Content-Type: application/json');
		$stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
		$listStatusRute = json_decode($stream_clean);
		$this->StatusRute_model->create_status_rute(array_map(function ($statusRute) {
			return [
				"hari" => $statusRute->hari,
				"jam" => $statusRute->jam,
				"rute_id" => $statusRute->rute_id,
				"status" => $statusRute->status,
			];
		}, $listStatusRute));
		echo json_encode(['status' => true, "message" => "Berhasil memasukkan status rute"]);
	}

	public function update()
	{
		header('Content-Type: application/json');
		$stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
		$listStatusRute = json_decode($stream_clean);
		foreach ($listStatusRute as $statusRute) {
			$where = [
				"hari" => $statusRute->hari,
				"jam" => $statusRute->jam,
				"rute_id" => $statusRute->rute_id,
			];
			$data = [
				"status" => $statusRute->status,
			];
			$this->StatusRute_model->update_status_rute($data, $where);
		}
		echo json_encode(['status' => true, "message" => "Berhasil mengubah status rute"]);
	}

	public function delete($id)
	{
		header('Content-Type: application/json');
		$this->StatusRute_model->delete_status_rute(["id" => $id]);
		echo json_encode(['status' => true, "message" => "Berhasil menghapus status rute"]);
	}
}
