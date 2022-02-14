<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wisata extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Wisata_model');
		$this->load->model('Gambar_model');
		$this->load->model('Kategori_model');
		$this->load->model('Kecamatan_model');
		$this->load->model('Kelurahan_model');
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

	public function all()
	{
		header('Content-Type: application/json');
		$listWisata = $this->Wisata_model->get_all();
		if (count($listWisata) > 0) {
			echo json_encode(["status" => "success", "data" => $listWisata]);
		} else {
			echo json_encode(["status" => "empty", "data" => $listWisata]);
		}
	}

	public function rekomendasi($lat, $long)
	{
		header('Content-Type: application/json');
		$listWisata = $this->Wisata_model->get_all();
		foreach ($listWisata as $wisata) {
			$wisata->jarak = getDist($wisata->lat_coord, $wisata->long_coord, $lat, $long);
		}
		for ($i = 0; $i < count($listWisata); $i++) {
			for ($j = 0; $j < count($listWisata) - 1; $j++) {
				if ($listWisata[$j]->jarak > $listWisata[$j + 1]->jarak) {
					$temp = $listWisata[$j];
					$listWisata[$j] = $listWisata[$j + 1];
					$listWisata[$j + 1] = $temp;
				}
			}
		}
		if (count($listWisata) > 0) {
			echo json_encode(["status" => "success", "data" => $listWisata]);
		} else {
			echo json_encode(["status" => "empty", "data" => $listWisata]);
		}
	}

	public function data_by_id($id)
	{
		header('Content-Type: application/json');
		$wisata = $this->Wisata_model->get_by_id($id);
		$gambar = $this->Gambar_model->get_all($wisata->id);
		if ($wisata != null) {
			$wisata->listGambar = $gambar;
			echo json_encode(["status" => "success", "data" => $wisata]);
		} else {
			echo json_encode(["status" => "empty", "data" => $wisata]);
		}
	}

	public function tambah()
	{
		$listKategori = $this->Kategori_model->get_all();
		$listKecamatan = $this->Kecamatan_model->get_all();
		$listKelurahan = $this->Kelurahan_model->get_all();
		$data = [
			"title" => "Tambah Data Wisata",
			"active" => "wisata",
			"listKategori" => $listKategori,
			"listKecamatan" => $listKecamatan,
			"listKelurahan" => $listKelurahan,
		];
		loadViews($this, 'wisata/tambah', $data);
	}

	public function ubah($id)
	{
		$listKategori = $this->Kategori_model->get_all();
		$listKecamatan = $this->Kecamatan_model->get_all();
		$listKelurahan = $this->Kelurahan_model->get_all();
		$wisata = $this->Wisata_model->get_by_id($id);
		$data = [
			"title" => "Ubah Data Wisata",
			"active" => "wisata",
			"wisata" => $wisata,
			"listKategori" => $listKategori,
			"listKecamatan" => $listKecamatan,
			"listKelurahan" => $listKelurahan,
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
		$nama = $this->input->post('nama');
		$alamat = $this->input->post('alamat');
		$kecamatan = $this->input->post('kecamatan');
		$kelurahan = $this->input->post('kelurahan');
		$jam_buka = $this->input->post('jam_buka');
		$no_telp = $this->input->post('no_telp');
		$kategori = $this->input->post('kategori');
		$rating = $this->input->post('rating');
		$lat = $this->input->post('lat');
		$long = $this->input->post('long');

		$alamat = $alamat . ", Kecamatan " . $kecamatan . ", Kelurahan " . $kelurahan;

		$data = [
			"nama" => $nama,
			"alamat" => $alamat,
			"jam_buka" => $jam_buka,
			"no_telp" => $no_telp,
			"kategori" => $kategori,
			"rating" => $rating,
			"lat_coord" => $lat,
			"long_coord" => $long,
		];

		$create = $this->Wisata_model->create_wisata($data);
		if ($create) {
			redirect('wisata');
		} else {
			echo "<script>alert('Gagal');history.go(-1);</script>";
		}
	}

	public function change($id)
	{
		$nama = $this->input->post('nama');
		$alamat = $this->input->post('alamat');
		$kecamatan = $this->input->post('kecamatan');
		$kelurahan = $this->input->post('kelurahan');
		$jam_buka = $this->input->post('jam_buka');
		$no_telp = $this->input->post('no_telp');
		$kategori = $this->input->post('kategori');
		$rating = $this->input->post('rating');
		$lat = $this->input->post('lat');
		$long = $this->input->post('long');

		$alamat = $alamat . ", Kecamatan " . $kecamatan . ", Kelurahan " . $kelurahan;

		$data = [
			"nama" => $nama,
			"alamat" => $alamat,
			"jam_buka" => $jam_buka,
			"no_telp" => $no_telp,
			"kategori" => $kategori,
			"rating" => $rating,
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
}
