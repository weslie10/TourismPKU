<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kecamatan extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Kecamatan_model');
	}

	public function index()
	{
		$listKecamatan = $this->Kecamatan_model->get_all();
		$data = [
			"title" => "Table Kecamatan",
			"active" => "kecamatan",
			"listKecamatan" => $listKecamatan,
		];
		loadViews($this, 'kecamatan/index', $data);
	}

	public function tambah()
	{
		$data = [
			"title" => "Tambah Data Kecamatan",
			"active" => "kecamatan",
		];
		loadViews($this, 'kecamatan/tambah', $data);
	}

	public function ubah($id)
	{
		$kecamatan = $this->Kecamatan_model->get_by_id($id);
		$data = [
			"title" => "Ubah Data Kecamatan",
			"active" => "kecamatan",
			"kecamatan" => $kecamatan,
		];
		loadViews($this, 'kecamatan/ubah', $data);
	}

	public function hapus($id)
	{
		$this->Kecamatan_model->delete_kecamatan(["id" => $id]);
		redirect('kecamatan');
	}

	public function add()
	{
		$nama = $this->input->post('nama');

		$data = [
			"nama" => $nama,
		];

		$create = $this->Kecamatan_model->create_kecamatan($data);
		if ($create) {
			redirect('kecamatan');
		} else {
			echo "<script>alert('Gagal');history.go(-1);</script>";
		}
	}

	public function change($id)
	{
		$nama = $this->input->post('nama');

		$data = [
			"nama" => $nama,
		];

		$where = [
			"id" => $id,
		];

		$update = $this->Kecamatan_model->update_kecamatan($data, $where);
		if ($update) {
			redirect('kecamatan');
		} else {
			echo "<script>alert('Gagal');history.go(-1);</script>";
		}
	}
}
