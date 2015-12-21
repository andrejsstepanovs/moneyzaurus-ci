<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	/** @var Moneyzaurus */
	public $moneyzaurus;

	/** @var User */
	public $user;

	/** @var CI_Session */
	public $session;

	public function index()
	{
		$this->load->view('layout/header');

		$token = $this->user->findToken();
		if (!empty($token)) {
			$this->moneyzaurus->setToken($token);
			$data = $this->moneyzaurus->userData();
			if ($data['code'] == 200) {
				if (!empty($data['data']['data']['id'])) {
					$this->load->helper('url');
					redirect('/transaction');
				}
			}
		}

		$error   = $this->session->flashdata('message');
		$success = $this->session->flashdata('success');
		$this->load->view('element/message', ['errors' => $error, 'success' => $success]);
		$this->load->view('page/welcome');
		$this->load->view('layout/footer');
	}
}
