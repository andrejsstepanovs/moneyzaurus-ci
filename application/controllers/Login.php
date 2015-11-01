<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
	/** @var Moneyzaurus */
	public $moneyzaurus;

	/** @var CI_Input */
	public $input;

	/** @var CI_Session */
	public $session;

	/** @var User */
	public $user;

	public function post()
	{
		$data = $this->input->post(['email', 'password', 'remember']);
		$response = $this->moneyzaurus->authenticateLogin($data['email'], $data['password']);

		if ($response['code'] == 200) {
			if (!$response['data']['success']) {
				$this->session->set_flashdata('message', 'Login attempt failed');
				redirect('');
			}

			//$response['data']['data']['expires_timestamp'];
			$message = sprintf('Hi %s', $response['data']['data']['email']);
			$this->session->set_flashdata('message', $message);

			$this->user->saveToken($response['data']['data']);

			redirect('/transaction');
		}

		$this->session->set_flashdata('message', 'Something failed. "' . $response['code'] . '"');
		redirect('');
	}
}
