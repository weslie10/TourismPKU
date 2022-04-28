<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Result
{
	public $src, $dest, $path;
}

class Map extends CI_Controller
{
	public $paths = array();
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Rute_model');
		$this->load->model('TitikRute_model');
		$this->load->model('StatusRute_model');
		$this->load->model('Wisata_model');
	}

	public function index()
	{
		$data = [
			"title" => "Map",
			"active" => "map",
		];
		loadViews($this, 'map', $data);
	}

	private function printPath($parent, $vertex, $source)
	{
		while (true) {
			if ($vertex < 0) {
				break;
			}
			array_push($this->paths, $vertex);
			$vertex = $parent[$vertex];
		}
		$this->paths = array_reverse($this->paths);
	}

	private function bellmanFord($graph, $src, $dest)
	{
		$lastId = $this->TitikRute_model->get_last_id()->id;

		$parent = new SplFixedArray($lastId + 1);

		for ($i = 0; $i < $lastId + 1; $i++) {
			$dist[$i] = PHP_INT_MAX;
			$parent[$i] = -1;
		}
		$dist[$src] = 0;

		$updatedVertices = [];
		array_push($updatedVertices, $src);

		while (count($updatedVertices) != 0) {
			$u = array_shift($updatedVertices);

			if ($u == $dest) {
				break;
			}

			$listEdge = $graph[intval($u)];

			for ($j = 0; $j < count($listEdge); $j++) {
				$v = $listEdge[$j]['dest'];
				$weight = $listEdge[$j]['weight'];
				if ($dist[$u] != PHP_INT_MAX && $dist[$u] + $weight < $dist[$v]) {
					$dist[$v] = $dist[$u] + $weight;
					$parent[$v] = $u;
					array_push($updatedVertices, $listEdge[$j]['dest']);
				}
			}
		}

		$listPath = [];

		for ($i = 0; $i < $lastId + 1; $i++) {
			if ($i != $src && $dist[$i] < PHP_INT_MAX) {
				$result = new Result();
				$result->dest = $i;
				$result->src = intval($src);
				$this->paths = array();
				// echo "The distance of vertex " . $i . " from vertex " . $src . " is " . $dist[$i] . ". Its path is [";
				$this->printPath($parent, $i, $src);
				$result->path = $this->paths;
				// echo "]<br>";
				array_push($listPath, $result);
			}
		}

		return $listPath;
	}

	private function getNearestPoint($lat, $long)
	{
		$radius = 5;
		$getData = false;
		$listTerdekat = $this->TitikRute_model->get_nearest_point($lat, $long, convertDist($radius));
		if (count($listTerdekat) > 0) {
			$getData = true;
		}
		$min = PHP_FLOAT_MAX;
		if ($getData) {
			foreach ($listTerdekat as $titik) {
				$titik->jarak = getDist($titik->lat_coord, $titik->long_coord, $lat, $long);
				if ($titik->jarak < $min) {
					$titikTerdekat = $titik;
					$min = $titik->jarak;
				}
			}
		} else {
			$titikTerdekat = "gagal";
		}

		return $titikTerdekat;
	}

	public function rute($latPosisi, $longPosisi, $id, $day = null, $time = null, $traffic = "true")
	{
		// http://localhost/TourismPKU/map/rute/0.534155/101.451561/7

		header('Content-Type: application/json');

		// $listTitikRute = $this->TitikRute_model->get_all();
		$listRute = $this->Rute_model->get_all();

		foreach ($listRute as $rute) {
			$rute->jarak = getDist($rute->lat_awal, $rute->long_awal, $rute->lat_akhir, $rute->long_akhir);
		}

		$tujuan = $this->Wisata_model->get_by_id($id);

		$tujuanTerdekat = $this->getNearestPoint($tujuan->lat_coord, $tujuan->long_coord);
		$titikTerdekat = $this->getNearestPoint($latPosisi, $longPosisi);

		if ($tujuanTerdekat == "gagal" || $titikTerdekat == "gagal") {
			echo json_encode(
				array(
					array(
						"src" => 0,
						"dest" => 0,
						"path" => [],
					)
				)
			);
		} else {
			$count = 0;
			$listTraffic = ["macet", "ramai", ""];

			$allPath = [];
			while (true) {
				$this->paths = array();
				$graph = [];
				for ($i = 0; $i < count($listRute); $i++) {
					array_push($graph, []);
				}

				for ($i = 0; $i <  count($listRute); $i++) {
					$edge = array(
						"dest" => intval($listRute[$i]->titik_akhir),
						"weight" => intval($listRute[$i]->jarak),
					);
					array_push($graph[$listRute[$i]->titik_awal], $edge);
				}

				$hasil = $this->bellmanFord($graph, $titikTerdekat->id, $tujuanTerdekat->id);
				foreach ($hasil as $data) {
					if ($data->dest == $tujuanTerdekat->id) {
						$filter = $data;
					}
				}

				for ($i = 0; $i < count($filter->path); $i++) {
					$titik = $this->TitikRute_model->get_by_id($filter->path[$i]);

					date_default_timezone_set("Asia/Jakarta");
					if ($day == null) {
						setlocale(LC_ALL, 'IND');
						$day = strtolower(strftime("%A"));
					}
					if ($time == null) {
						$date = date("H:i:s");
						$dateArr = explode(":", $date);
					} else {
						$dateArr = explode(":", $time);
					}
					$hour = intval($dateArr[0]);

					$waktu = $this->StatusRute_model->get_data_by_time(
						intval($filter->path[$i]),
						$hour,
						$hour + 1,
						$day
					);
					$status = "sepi";
					if (count($waktu) == 2) {
						$statusToNumber = ["sepi" => 0, "ramai" => 1, "macet" => 2];
						$numberToStatus = ["sepi", "ramai", "macet"];
						$minute = intval($dateArr[1]);
						if ($minute >= 20 && $minute <= 40) {
							$calc = floor($statusToNumber[$waktu[0]->status] + $statusToNumber[$waktu[1]->status] / 2);
							if ($calc > 2) $calc = 2;
							$status = $numberToStatus[$calc];
						} else if ($minute < 20) {
							$status = $waktu[0]->status;
						} else {
							$status = $waktu[1]->status;
						}
					}

					$filter->path[$i] = array(
						"id" => $filter->path[$i],
						"lat" => $titik->lat_coord,
						"long" => $titik->long_coord,
						"status" => $status,
					);
				}
				$check = false;
				for ($i = 0; $i < count($filter->path); $i++) {
					if ($filter->path[$i]["status"] == $listTraffic[$count]) {
						$idPath = $filter->path[$i]["id"];
						for ($j = 0; $j < count($listRute); $j++) {
							if ($listRute[$j]->id == $idPath || $listRute[$j]->titik_awal == $idPath || $listRute[$j]->titik_akhir == $idPath) {
								array_splice($listRute, $j, 1);
							}
						}
						$check = true;
					}
				}
				if (!$check && $count == 0) {
					for ($i = 0; $i < count($filter->path); $i++) {
						if ($filter->path[$i]["status"] == $listTraffic[$count + 1]) {
							$idPath = $filter->path[$i]["id"];
							for ($j = 0; $j < count($listRute); $j++) {
								if ($listRute[$j]->id == $idPath || $listRute[$j]->titik_awal == $idPath || $listRute[$j]->titik_akhir == $idPath) {
									array_splice($listRute, $j, 1);
								}
							}
							$check = true;
						}
					}
				}
				array_push($allPath, $filter);
				if (!$check) break;
				$count++;
				if ($count == 3) break;
				if ($traffic != "true") break;
			}
			if ($traffic == "true") {
				echo json_encode($allPath);
			} else {
				echo json_encode($allPath[0]);
			}
		}
	}

	public function peta($latPosisi, $longPosisi, $id, $day = "senin", $time = "7:10")
	{
		header('Content-Type: application/json');

		$peta = [];

		$listWisata = $this->Wisata_model->get_all();

		$selectedWisata = $this->Wisata_model->get_by_id($id);
		$idx = array_search($selectedWisata, $listWisata);
		array_splice($listWisata, $idx, 1);

		$api_url = base_url("/map/rute/$latPosisi/$longPosisi/$selectedWisata->id/$day/$time/false");
		$json_data = file_get_contents($api_url);
		$response_data = json_decode($json_data);

		array_push($peta, $response_data);
		$latPosisi = $selectedWisata->lat_coord;
		$longPosisi = $selectedWisata->long_coord;

		while (count($listWisata) > 0) {
			$tempWisata = $listWisata;
			foreach ($tempWisata as $wisata) {
				$wisata->jarak = getDist($wisata->lat_coord, $wisata->long_coord, $latPosisi, $longPosisi);
			}
			for ($i = 0; $i < count($tempWisata); $i++) {
				for ($j = 0; $j < count($tempWisata) - 1; $j++) {
					if (intval($tempWisata[$j]->jarak) > intval($tempWisata[$j + 1]->jarak)) {
						$temp = $tempWisata[$j];
						$tempWisata[$j] = $tempWisata[$j + 1];
						$tempWisata[$j + 1] = $temp;
					}
				}
			}

			$selectedWisata = $tempWisata[0];
			$idx = array_search($selectedWisata, $listWisata);
			array_splice($listWisata, $idx, 1);

			$api_url = base_url("/map/rute/$latPosisi/$longPosisi/$selectedWisata->id/$day/$time/false");
			$json_data = file_get_contents($api_url);
			$response_data = json_decode($json_data);

			array_push($peta, $response_data);
			$latPosisi = $selectedWisata->lat_coord;
			$longPosisi = $selectedWisata->long_coord;
		}
		echo json_encode($peta);
	}
}

// 208, 8, 6, 48, 5, 15, 229, 230, 43, 42, 17, 18, 37, 38, 116, 117, 115, 226, 227, 1253, 923, 924
// 7,8,9,10,11,21,22,23,24,25,26,27,28,29,32,19,20,15,5,13,16,17,18,14,2,3,4,12,1,31