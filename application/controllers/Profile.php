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

	/** @var User */
	public $user;

	public function __construct()
	{
		parent::__construct();

		if (!$this->user->findToken()) {
			sleep(5);
			redirect('');
		}
	}

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

		$parent   = false;
		$response = $this->moneyzaurus->connectionList($parent);
		if ($response['code'] == 200) {
			$viewData['connections_child'] = $response['data']['data'];
		}

		$parent   = true;
		$response = $this->moneyzaurus->connectionList($parent);
		if ($response['code'] == 200) {
			$viewData['connections_parent'] = $response['data']['data'];
		}

		$response = $this->moneyzaurus->version();
		if ($response['code'] == 200) {
			$viewData['version'] = $response['data']['version'];
		}

		$this->load->view('page/profile', $viewData);
		$this->load->view('page/connections', $viewData);
		$this->load->view('page/version', $viewData);
		$this->load->view('page/logout', $viewData);

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


	public function invite()
	{
		$email    = $this->input->post('email');
		$response = $this->moneyzaurus->connectionAdd($email);

		if ($response['code'] == 200) {
			if (!$response['data']['success']) {
				$this->session->set_flashdata('message', $response['data']['message']);
			} else {
				$this->session->set_flashdata('message', 'User ' . $email . ' is invited to connect accounts');
			}
		}

		redirect('/profile');
	}

	public function acceptConnection()
	{
		$id       = $this->input->post('id');
		$response = $this->moneyzaurus->connectionAccept($id);

		if ($response['code'] == 200) {
			if (!$response['data']['success']) {
				$this->session->set_flashdata('message', $response['data']['message']);
			} else {
				$this->session->set_flashdata('message', 'Connection accepted');
			}
		}

		redirect('/profile');
	}

	public function declineConnection()
	{
		$id       = $this->input->post('id');
		$response = $this->moneyzaurus->connectionReject($id);

		if ($response['code'] == 200) {
			if (!$response['data']['success']) {
				$this->session->set_flashdata('message', $response['data']['message']);
			} else {
				$this->session->set_flashdata('message', 'Connection rejected');
			}
		}

		redirect('/profile');
	}

}
