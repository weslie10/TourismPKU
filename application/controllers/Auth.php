<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
	}

	public function index()
	{
		$this->load->view('login');
	}

	public function login()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		if ($username == "admin" && $password == "admin") {
			$this->session->set_userdata('username', $username);
			redirect('wisata');
		} else {
			echo "<script>alert('Username atau password salah');history.go(-1);</script>";
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('username');
		redirect('welcome');
	}
}
