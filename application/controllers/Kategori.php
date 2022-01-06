<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kategori extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Kategori_model');
	}

	public function index()
	{
		$listKategori = $this->Kategori_model->get_all();
		$data = [
			"title" => "Table Kategori",
			"active" => "kategori",
			"listKategori" => $listKategori,
		];
		loadViews($this, 'kategori/index', $data);
	}

	public function tambah()
	{
		$data = [
			"title" => "Tambah Data Kategori",
			"active" => "kategori",
		];
		loadViews($this, 'kategori/tambah', $data);
	}

	public function ubah($id)
	{
		$kategori = $this->Kategori_model->get_by_id($id);
		$data = [
			"title" => "Ubah Data Kategori",
			"active" => "kategori",
			"kategori" => $kategori,
		];
		loadViews($this, 'kategori/ubah', $data);
	}

	public function hapus($id)
	{
		$this->Kategori_model->delete_kategori(["id" => $id]);
		redirect('kategori');
	}

	public function add()
	{
		$nama = $this->input->post('nama');

		$data = [
			"nama" => $nama,
		];

		$create = $this->Kategori_model->create_kategori($data);
		if ($create) {
			redirect('kategori');
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

		$update = $this->Kategori_model->update_kategori($data, $where);
		if ($update) {
			redirect('kategori');
		} else {
			echo "<script>alert('Gagal');history.go(-1);</script>";
		}
	}

	public function all()
	{
		echo json_encode($this->Kategori_model->get_all());
	}
}
