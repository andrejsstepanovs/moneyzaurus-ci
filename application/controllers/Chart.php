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

		if ($response['code'] == 200 && $response['data']['success']) {
			$this->load->view(
				'page/pie',
				[
					'data' => $response['data']['data'],
					'from' => $from,
					'till' => $till,
				]
			);
		}

		$this->load->view('layout/footer');
	}

	public function index()
	{
		$this->load->view('layout/header');

		$error = $this->session->flashdata('message');
		$this->load->view('element/message', ['success' => $error]);

		$filter   = $this->input->get(['from', 'till', 'groups']);
		$from     = $filter['from'] ?: date('Y-m-01', strtotime('-1 month'));
		$till     = $filter['till'] ?: date('Y-m-t');

		$responseData = [];
		$step   = 500;
		$offset = 0;
		do {
			$response = $this->moneyzaurus->transactionsList($offset, $step, $from, $till, null, null, null);
			if ($response['code'] == 200 && $response['data']['success']) {
				$count = $response['data']['count'];
				$responseData = array_merge($responseData, $response['data']['data']);
				$offset += $step;
			} else {
				break;
			}
		} while ($count >= $step);

		$filterGroups = $filter['groups'] ?: [];
		$data = $this->prepareChartData($responseData, $filterGroups, $from, $till);
		$this->load->view('page/chart', ['data' => $data, 'from' => $from, 'till' => $till]);


		$this->load->view('layout/footer');
	}

	private function prepareChartData(array $data, array $filterGroups, $from, $till)
	{
		$return = ['groups' => ['__total__'], 'selected' => [], 'data' => []];
		foreach ($data as $row) {
			if (array_search($row['groupName'], $return['groups']) === false) {
				$return['groups'][] = $row['groupName'];
			}
		}

		$return['selected'] = empty($filterGroups) ? ['__total__'] : $filterGroups;
		$return['data']     = $this->groupByMonth($data, $return['selected'], $from, $till);

		return $return;
	}

	public function groupByMonth(array $data, array $filterGroups, $from, $till)
	{
		$zeroValues    = array_combine(array_values($filterGroups), array_fill(0, count($filterGroups), 0));
		$return        = [];
		$tillTimestamp = strtotime($till);
		$time          = strtotime($from);
		do {
			$step          = date('Y-m', $time);
			$return[$step] = $zeroValues;
			$time = strtotime('+1 month', $time);
		} while ($time <= $tillTimestamp);

		$total = in_array('__total__', $filterGroups) !== false;
		foreach ($data as $row) {
			$step  = date('Y-m', strtotime($row['date']));
			$group = $row['groupName'];
			if (array_key_exists($group, $return[$step])) {
				$return[$step][$group] += $row['amount'] / 100;
			}
			if ($total) {
				$return[$step]['__total__'] += $row['amount'] / 100;
			}
		}

		return $return;
	}
}
