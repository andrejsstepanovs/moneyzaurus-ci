<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chart extends CI_Controller
{
	/** @var Moneyzaurus */
	public $moneyzaurus;

	/** @var CI_Input */
	public $input;

	/** @var CI_Session */
	public $session;

	public function pie()
	{
		$this->load->view('layout/header');

		$error = $this->session->flashdata('message');
		$this->load->view('element/message', ['success' => $error]);

		$default = [
			'currency' => 'EUR',
			'from'     => date('Y-m-01'),
			'till'     => date('Y-m-t')
		];
		$filter = $this->input->get(['currency', 'from', 'till']);

		$currency = $filter['currency'] ?: $default['currency'];
		$from     = $filter['from'] ?: $default['from'];
		$till     = $filter['till'] ?: $default['till'];
		$response = $this->moneyzaurus->chartPie($currency, $from, $till);

		if ($response['code'] == 200) {
			if ($response['data']['success']) {
				$this->load->view(
					'page/pie',
					[
						'data' => $response['data']['data'],
						'from' => $from,
						'till' => $till,
					]
				);
			}
		}

		$this->load->view('layout/footer');
	}
}
