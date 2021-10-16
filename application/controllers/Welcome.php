<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	const SESSION_KEY = 'login_status';

	public function index()
	{
		if (!$this->session->has_userdata(self::SESSION_KEY)) $this->load->view('login');
		else $this->show_home();
	}

	public function login()
	{
		if ($this->session->has_userdata(self::SESSION_KEY)) redirect(base_url());
		else {
			$username = $this->input->post("username");
			$password = $this->input->post("password");

			$this->load->model('User');

			$result = $this->User->get_user_info($username);

			if (count($result) > 0) {
				$password = md5($password);
				if ($password == $result[0]['password']) {
					$this->session->set_userdata([self::SESSION_KEY => true]);
					$this->session->set_userdata(["username" => $username]);
					redirect(base_url());
				} else {
					$this->load->view('login');
				}
			} else {
				$this->load->view('login');
			}
		}
	}

	public function show_home()
	{
		$this->load->model('User');
		$result = $this->User->get_profile_user($this->session->userdata("username"));

		$data = [
			'content' => 'dashboard',
			'nama' => $result[0]['nama']
		];

		if ($this->session->userdata(self::SESSION_KEY) === true) $this->load->view('layout', $data);
		else redirect(base_url());
	}

	public function show_blank_page()
	{
		$this->load->model('User');
		$result = $this->User->get_profile_user($this->session->userdata("username"));

		$data = [
			'content' => 'blank_page',
			'nama' => $result[0]['nama']
		];

		if ($this->session->userdata(self::SESSION_KEY) === true) $this->load->view('layout', $data);
		else redirect(base_url());
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url());
	}
}
