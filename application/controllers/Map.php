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

		// for ($i = 0; $i < count($this->paths); $i++) {
		// 	if ($i != 0) {
		// 		echo ",";
		// 	}
		// 	echo $this->paths[$i];
		// }
	}

	private function bellmanFord($graph, $src, $dest)
	{
		// $V = $graph->v;
		// $E = $graph->e;
		$lastId = $this->TitikRute_model->get_last_id()->id;

		$parent = new SplFixedArray($lastId + 1);

		for ($i = 0; $i < $lastId + 1; $i++) {
			$dist[$i] = PHP_INT_MAX;
			$parent[$i] = -1;
		}
		$dist[$src] = 0;

		$updatedVertices = [];
		array_push($updatedVertices, $src);

		// $count = 0;
		while (count($updatedVertices) != 0) {
			// $count++;
			$u = array_shift($updatedVertices);

			if ($u == $dest) {
				break;
			}

			$listEdge = $graph[$u];

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
		// echo $count;

		//75
		// for ($i = 1; $i <= $V - 1; $i++) {
		// 	$repeat = false;
		// 	for ($j = 0; $j < $E; $j++) {
		// 		$u = $graph->edge[$j]->src;
		// 		$v = $graph->edge[$j]->dest;
		// 		$weight = $graph->edge[$j]->weight;
		// 		if ($dist[$u] != PHP_INT_MAX && $dist[$u] + $weight < $dist[$v]) {
		// 			$dist[$v] = $dist[$u] + $weight;
		// 			$parent[$v] = $u;
		// 			$repeat = true;
		// 		}
		// 	}
		// 	if (!$repeat) {
		// 		break;
		// 	}
		// }

		$listPath = array();

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

	public function rute($latPosisi, $longPosisi, $id)
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
					"src" => 0,
					"dest" => 0,
					"path" => [],
				)
			);
		} else {
			$graph = [];
			for ($i = 0; $i < count($listRute); $i++) {
				array_push($graph, []);
			}

			for ($i = 0; $i <  count($listRute); $i++) {
				$edge = array(
					"dest" => $listRute[$i]->titik_akhir,
					"weight" => $listRute[$i]->jarak,
				);
				array_push($graph[$listRute[$i]->titik_awal], $edge);
			}

			$hasil = $this->bellmanFord($graph, $titikTerdekat->id, $tujuanTerdekat->id);
			// json_encode($hasil);
			foreach ($hasil as $data) {
				if ($data->dest == $tujuanTerdekat->id) {
					$filter = $data;
				}
			}
			for ($i = 0; $i < count($filter->path); $i++) {
				$titik = $this->TitikRute_model->get_by_id($filter->path[$i]);
				$filter->path[$i] = array(
					"id" => $filter->path[$i],
					"lat" => $titik->lat_coord,
					"long" => $titik->long_coord
				);
			}
			echo json_encode($filter);
		}
	}
}

// 208, 8, 6, 48, 5, 15, 229, 230, 43, 42, 17, 18, 37, 38, 116, 117, 115, 226, 227, 1253, 923, 924
