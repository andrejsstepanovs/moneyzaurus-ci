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

		$id = $this->input->get('id');
		if (!empty($id)) {
			$response = $this->moneyzaurus->transactionsId($id);
			if ($response['code'] != 200 || !$response['data']['success']) {
				$error = $response['data']['message'];
			} else {
				$data = $response['data']['data'];
				$get = [
					'id'    => $data['id'],
					'item'  => $data['itemName'],
					'group' => $data['groupName'],
					'price' => $data['price'],
					'date'  => $data['date'],
				];
			}
		} else {
			$get = $this->input->get(['item', 'group', 'price', 'date']);
		}

		$this->load->view('element/message', ['error' => $error]);

		$data = [
			'id'    => $id,
			'item'  => '',
			'group' => '',
			'price' => '',
			'date'  => date('Y-m-d'),
		];

		if (empty($get['date'])) {
			unset($get['date']);
		}

		$success = $this->input->get('success');
		if (!$success) {
			$error = $this->session->flashdata('message');
			$this->load->view('element/message', ['errors' => $error]);
		} else {
			$successText = !empty($id) ? 'Updated' : 'Saved';
			$this->load->view('element/message', ['success' => $successText]);
		}

		$this->load->view('page/transaction', array_merge($data, $get));

		$this->load->view('layout/footer');
	}

	public function save()
	{
		$data = $this->input->post(['id', 'item', 'group', 'price', 'date']);
		if (!empty($data['id'])) {
			$response = $this->moneyzaurus->transactionsUpdate($data['id'], $data['item'], $data['group'], $data['price'], 'EUR', $data['date']);
		} else {
			$response = $this->moneyzaurus->transactionsAdd($data['item'], $data['group'], $data['price'], 'EUR', $data['date']);
		}

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
