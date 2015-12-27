<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manager extends CI_Controller
{
	/** @var Moneyzaurus */
	public $moneyzaurus;

	/** @var CI_Input */
	public $input;

	/** @var CI_Session */
	public $session;

	/** @var User */
	public $user;

    /** @var Prediction */
	public $prediction;

	public function index()
	{
        $this->load->library('prediction');

        $money  = $this->input->get('money');
        $date   = $this->input->get('date');
        $months = $this->input->get('months');
        $data   = $this->prediction
                       ->setTransactions($this->getAllTransactions($months ?: 3))
                       ->getData($money, $date);

        $this->load->view('layout/header');

        $error = $this->session->flashdata('message');
        $this->load->view('element/message', ['success' => $error]);
        $this->load->view('page/manager', $data);
        $this->load->view('layout/footer');
    }


    private function getAllTransactions($months)
    {
        $filter   = $this->input->get(['from', 'till', 'groups']);
        $from     = $filter['from'] ?: date('Y-m-01', strtotime('-' . (int)$months . ' month'));
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

        return $responseData;
    }
}
