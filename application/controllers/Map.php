<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Edge
{
	public $src, $dest, $weight;
}

class Graph
{
	// v-> Number of vertices, e-> Number of edges
	public $v, $e, $edge;
}

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

	private function bellmanFord($graph, $src)
	{
		$V = $graph->v;
		$E = $graph->e;
		$lastId = $this->TitikRute_model->get_last_id()->id;

		$dist = new SplFixedArray($lastId + 1);
		$parent = new SplFixedArray($lastId + 1);

		for ($i = 0; $i < $lastId + 1; $i++) {
			$dist[$i] = PHP_INT_MAX;
			$parent[$i] = -1;
		}
		$dist[$src] = 0;

		for ($i = 1; $i <= $V - 1; $i++) {
			for ($j = 0; $j < $E; $j++) {
				$u = $graph->edge[$j]->src;
				$v = $graph->edge[$j]->dest;
				$weight = $graph->edge[$j]->weight;
				if ($dist[$u] != PHP_INT_MAX && $dist[$u] + $weight < $dist[$v]) {
					$dist[$v] = $dist[$u] + $weight;
					$parent[$v] = $u;
				}
			}
		}

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
		$radius = 0;
		$fail = false;
		while (true) {
			$radius += 0.1;
			$listTerdekat = $this->TitikRute_model->get_nearest_point($lat, $long, convertDist($radius));
			if ($radius >= 5) {
				$fail = true;
				break;
			}
			if (count($listTerdekat) > 0) {
				break;
			}
		}
		$min = PHP_FLOAT_MAX;
		if (!$fail) {
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
		$listTitikRute = $this->TitikRute_model->get_all();
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
			$graph = new Graph();
			$graph->v = count($listTitikRute);
			$graph->e = count($listRute);
			$graph->edge = new SplFixedArray($graph->e);
			for ($i = 0; $i < $graph->e; $i++) {
				$graph->edge[$i] = new Edge();
				$graph->edge[$i]->src = $listRute[$i]->titik_awal;
				$graph->edge[$i]->dest = $listRute[$i]->titik_akhir;
				$graph->edge[$i]->weight = $listRute[$i]->jarak;
			}
			$hasil = $this->bellmanFord($graph, $titikTerdekat->id);
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
