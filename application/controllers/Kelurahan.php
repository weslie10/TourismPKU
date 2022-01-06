<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelurahan extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Kelurahan_model');
		$this->load->model('Kecamatan_model');
	}

	public function index()
	{
		$listKelurahan = $this->Kelurahan_model->get_all();
		$data = [
			"title" => "Table Kelurahan",
			"active" => "kelurahan",
			"listKelurahan" => $listKelurahan,
		];
		loadViews($this, 'kelurahan/index', $data);
	}

	public function tambah()
	{
		$listKecamatan = $this->Kecamatan_model->get_all();
		$data = [
			"title" => "Tambah Data Kelurahan",
			"active" => "kelurahan",
			"listKecamatan" => $listKecamatan,
		];
		loadViews($this, 'kelurahan/tambah', $data);
	}

	public function ubah($id)
	{
		$listKecamatan = $this->Kecamatan_model->get_all();
		$kelurahan = $this->Kelurahan_model->get_by_id($id);
		$data = [
			"title" => "Ubah Data Kelurahan",
			"active" => "kelurahan",
			"kelurahan" => $kelurahan,
			"listKecamatan" => $listKecamatan,
		];
		loadViews($this, 'kelurahan/ubah', $data);
	}

	public function hapus($id)
	{
		$this->Kelurahan_model->delete_kelurahan(["id" => $id]);
		redirect('kelurahan');
	}

	public function add()
	{
		$nama = $this->input->post('nama');
		$kecamatan_id = $this->input->post('kecamatan_id');

		$data = [
			"nama" => $nama,
			"kecamatan_id" => $kecamatan_id,
		];

		$create = $this->Kelurahan_model->create_kelurahan($data);
		if ($create) {
			redirect('kelurahan');
		} else {
			echo "<script>alert('Gagal');history.go(-1);</script>";
		}
	}

	public function change($id)
	{
		$nama = $this->input->post('nama');
		$kecamatan_id = $this->input->post('kecamatan_id');

		$data = [
			"nama" => $nama,
			"kecamatan_id" => $kecamatan_id,
		];

		$where = [
			"id" => $id,
		];

		$update = $this->Kelurahan_model->update_kelurahan($data, $where);
		if ($update) {
			redirect('kelurahan');
		} else {
			echo "<script>alert('Gagal');history.go(-1);</script>";
		}
	}
}
