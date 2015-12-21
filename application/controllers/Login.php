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
		if ($this->loginCustomer($data['email'], $data['password'])) {
			redirect('/transaction');
		}
		redirect('');
	}

	public function register()
	{
		$data = $this->input->post(['email', 'password']);

		$response = $this->moneyzaurus->userRegister($data['email'], $data['password']);

		if ($response['code'] == 200) {
			if (!$response['data']['success']) {
				$this->session->set_flashdata('message', $response['data']['message']);
				redirect('');
			}

			$message = sprintf('Hi %s', $response['data']['data']['email']);
			$this->session->set_flashdata('message', $message);

			if ($this->loginCustomer($data['email'], $data['password'])) {
				redirect('/transaction');
			}
		}

		redirect('');
	}

	protected function loginCustomer($email, $password)
	{
		$response = $this->moneyzaurus->authenticateLogin($email, $password);

		if ($response['code'] == 200) {
			if (!$response['data']['success']) {
				$this->session->set_flashdata('message', 'Login attempt failed');
				return false;
			}

			//$response['data']['data']['expires_timestamp'];
			$message = sprintf('Hi %s', $response['data']['data']['email']);
			$this->session->set_flashdata('message', $message);

			$this->user->saveToken($response['data']['data']);

			return true;

		} else {
			$this->session->set_flashdata('message', 'Something failed. "' . $response['code'] . '"');
		}

		return false;
	}
}
