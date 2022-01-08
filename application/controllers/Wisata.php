<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wisata extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Wisata_model');
		$this->load->model('Kategori_model');
	}

	public function index()
	{
		$listWisata = $this->Wisata_model->get_all();
		$data = [
			"title" => "Table Wisata",
			"active" => "wisata",
			"listWisata" => $listWisata,
		];
		loadViews($this, 'wisata/index', $data);
	}

	public function tambah()
	{
		$listKategori = $this->Kategori_model->get_all();
		$data = [
			"title" => "Tambah Data Wisata",
			"active" => "wisata",
			"listKategori" => $listKategori,
		];
		loadViews($this, 'wisata/tambah', $data);
	}

	public function ubah($id)
	{
		$listKategori = $this->Kategori_model->get_all();
		$wisata = $this->Wisata_model->get_by_id($id);
		$data = [
			"title" => "Ubah Data Wisata",
			"active" => "wisata",
			"wisata" => $wisata,
			"listKategori" => $listKategori,
		];
		loadViews($this, 'wisata/ubah', $data);
	}

	public function hapus($id)
	{
		$wisata = $this->Wisata_model->get_by_id($id);
		unlink($wisata->gambar);
		$this->Wisata_model->delete_wisata(["id" => $id]);
		redirect('wisata');
	}

	public function add()
	{
		$upload = $this->do_upload("gambar");
		if ($upload["status"]) {
			$nama = $this->input->post('nama');
			$alamat = $this->input->post('alamat');
			$jam_buka = $this->input->post('jam_buka');
			$no_telp = $this->input->post('no_telp');
			$kategori = $this->input->post('kategori');
			$lat = $this->input->post('lat');
			$long = $this->input->post('long');

			$data = [
				"nama" => $nama,
				"gambar" => $upload["pic"],
				"alamat" => $alamat,
				"jam_buka" => $jam_buka,
				"no_telp" => $no_telp,
				"kategori" => $kategori,
				"lat_coord" => $lat,
				"long_coord" => $long,
			];

			$create = $this->Wisata_model->create_wisata($data);
			if ($create) {
				redirect('wisata');
			} else {
				echo "<script>alert('Gagal');history.go(-1);</script>";
			}
		} else {
			echo "<script>alert('" . $upload["error"]["error"] . "');</script>";
		}
	}

	public function change($id)
	{
		$nama = $this->input->post('nama');
		$alamat = $this->input->post('alamat');
		$jam_buka = $this->input->post('jam_buka');
		$no_telp = $this->input->post('no_telp');
		$kategori = $this->input->post('kategori');
		$lat = $this->input->post('lat');
		$long = $this->input->post('long');

		$data = [
			"nama" => $nama,
			"alamat" => $alamat,
			"jam_buka" => $jam_buka,
			"no_telp" => $no_telp,
			"kategori" => $kategori,
			"lat_coord" => $lat,
			"long_coord" => $long,
		];

		$where = [
			"id" => $id,
		];
		$update = $this->Wisata_model->update_wisata($data, $where);
		if ($update) {
			redirect('wisata');
		} else {
			echo "<script>alert('Gagal');history.go(-1);</script>";
		}
	}

	public function change_picture($id)
	{
		$wisata = $this->Wisata_model->get_by_id($id);
		$upload = $this->do_upload("gambar");
		if ($upload["status"]) {
			unlink($wisata->gambar);
			$data['gambar'] = $upload["pic"];
		}

		$where = [
			"id" => $id,
		];

		$update = $this->Wisata_model->update_wisata($data, $where);
		if ($update) {
			redirect('wisata');
		} else {
			echo "<script>alert('Gagal');history.go(-1);</script>";
		}
	}

	public function do_upload($type)
	{
		$new_name = time() . str_replace(' ', '_', $_FILES[$type]['name']);

		$config['upload_path']          = './uploads/';
		$config['allowed_types']        = 'jpg|jpeg|png|PNG';
		$config['max_size']             = 2048;
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
