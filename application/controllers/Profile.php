<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller
{
	/** @var Moneyzaurus */
	public $moneyzaurus;

	/** @var CI_Input */
	public $input;

	/** @var CI_Session */
	public $session;

	public function index()
	{
		$this->load->view('layout/header');

		$error = $this->session->flashdata('message');
		$this->load->view('element/message', ['success' => $error]);

		$viewData = [];
		$response = $this->moneyzaurus->userData();
		if ($response['code'] == 200 && $response['data']['success']) {
			$viewData['data'] = $response['data']['data'];
		}

		$response = $this->moneyzaurus->version();
		if ($response['code'] == 200) {
			$viewData['version'] = $response['data']['version'];
		}

		$this->load->view('page/profile', $viewData);

		$this->load->view('layout/footer');
	}

	public function save()
	{
		$userData = $this->moneyzaurus->userData();
		$userData = $userData['data']['data'];

		$response = $this->moneyzaurus->userUpdate(
			$userData['email'],
			$this->input->post('name'),
			$userData['locale'],
			$userData['language'],
			$userData['timezone']
		);

		if ($response['code'] == 200) {
			if (!$response['data']['success']) {
				$this->session->set_flashdata('message', $response['data']['message']);
			} else {
				$this->session->set_flashdata('message', 'Saved');
			}
		}

		redirect('/profile');
	}

	public function logout()
	{
		$response = $this->moneyzaurus->authenticateLogout();
	}
}
