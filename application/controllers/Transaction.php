<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller
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
		$this->load->view('element/message', ['error' => $error]);

		$data = [
			'item'  => '',
			'group' => '',
			'price' => '',
			'date'  => date('Y-m-d'),
		];
		$get = $this->input->get(['item', 'group', 'price', 'date']);
		if (empty($get['date'])) {
			unset($get['date']);
		}

		$success = $this->input->get('success');
		if (!$success) {
			$error = $this->session->flashdata('message');
			$this->load->view('element/message', ['errors' => $error]);
		} else {
			$this->load->view('element/message', ['success' => 'Saved']);
		}

		$this->load->view('page/transaction', array_merge($data, $get));

		$this->load->view('layout/footer');
	}

	public function save()
	{
		$data = $this->input->post(['item', 'group', 'price', 'date']);

		$response = $this->moneyzaurus->transactionsAdd(
			$data['item'],
			$data['group'],
			$data['price'],
			'EUR',
			$data['date']
		);

		if ($response['code'] == 200) {
			if (!$response['data']['success']) {
				$this->session->set_flashdata('message', $response['data']['message']);
			} else {
				redirect('/transaction?success=' . $response['data']['data']['id']);
			}

			redirect('/transaction?' . http_build_query($data));
		}

		$this->session->set_flashdata('message', 'Error');
		redirect('/transaction?' . http_build_query($data));
	}
}
