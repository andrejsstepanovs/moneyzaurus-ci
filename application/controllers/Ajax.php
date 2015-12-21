<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller
{
	/** @var Moneyzaurus */
	public $moneyzaurus;

	/** @var CI_Input */
	public $input;

	/** @var CI_Session */
	public $session;

	/** @var CI_Output */
	public $output;

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

	public function items()
	{
		$response = $this->moneyzaurus->distinctItems('2000-01-01', 999);
		$this->processResponseData($response);
	}

	public function groups()
	{
		$response = $this->moneyzaurus->distinctGroups('2000-01-01', 999);
		$this->processResponseData($response);
	}

	public function predictGroups()
	{
		$item     = $this->input->get('item');
		$response = $this->moneyzaurus->predictGroup($item);
		$this->processResponseData($response);
	}

	public function predictPrice()
	{
		$item     = $this->input->get('item');
		$group    = $this->input->get('group');
		$response = $this->moneyzaurus->predictPrice($item, $group);

		$this->processResponseData($response);
	}

	private function processResponseData(array $response)
	{
		if (!$this->input->is_ajax_request()) {
			redirect('/');
		}

		$this->output->set_content_type('application/json');

		if ($response['code'] == 200) {
			if ($response['data']['success']) {
				$this->output->set_output(json_encode($response['data']['data']));
			}
		}
	}
}
