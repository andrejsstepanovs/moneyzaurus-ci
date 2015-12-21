<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller
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

		$offset = 0;
		$limit  = 100;
		$filter = $this->input->get('item', 'group', 'price', 'from', 'till');

		$response = $this->moneyzaurus->transactionsList(
			$offset,
			$limit,
			$filter['from'],
			$filter['till'],
			$filter['item'],
			$filter['group'],
			$filter['price']
		);

		if ($response['code'] == 200) {
			if ($response['data']['success']) {
				$this->load->view(
					'page/data',
					[
						'count'   => $response['data']['count'],
						'data'   => $response['data']['data'],
						'filter' => $filter,
					]
				);
			}
		}

		$this->load->view('layout/footer');
	}
}
