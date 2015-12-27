<?php

class Prediction
{
	/** @var array */
	private $transactions;

	public function __construct()
	{
		$this->instance =& get_instance();
		$this->client = $this->instance->client;
	}

	public function setTransactions(array $transactions)
	{
		$this->transactions = $transactions;
		return $this;
	}

    public function getData($money, $date)
    {
        $data = [];
        $data['money']                 = $money;
        $data['date']                  = $date;
        $data['money_in_months']       = $this->getMoneyInMonths();
        $data['money_in_full_months']  = $this->getMoneyInFullMonths($data);
        $data['money_in_salary']       = $this->predictMoneyInSalary($data);
        $data['money_still_to_spend']  = $this->moneyNotSpentThisMonth($data);
        $data['money']                 = $this->predictMoneyYouHave($data);
        $data['date']                  = $data['date'] ?: $this->predictDate();
        $data['days_till_next_salary'] = ceil((strtotime($data['date']) - time()) / 86400);
        $data['possible_in_day']       = $this->getPossibleInDay($data);

        return $data;
    }

    private function predictMoneyYouHave(array $data)
    {
        if (empty($data['money'])) {
            $value = $this->money($data['money_still_to_spend']);
        } else {
            $value = $data['money'];
        }

        return $value;
    }

    private function moneyNotSpentThisMonth(array $data)
    {
        $lastMonth = array_pop($data['money_in_months']);
        return $data['money_in_salary'] - $lastMonth;
    }

    private function getPossibleInDay(array $data)
    {
        return round($data['money'] / $data['days_till_next_salary']);
    }

    private function predictDate()
    {
        return date('Y-m-01', strtotime('+1 month'));
    }

    private function getMoneyInFullMonths(array $data)
    {
        $moneyInMonths = $data['money_in_months'];
        return array_slice($moneyInMonths, 0, count($moneyInMonths) - 1, true);
    }

    private function getMoneyInMonths()
    {
        $moneyInMonth = [];
        foreach ($this->transactions as $row) {
            $month = date('Y-m-01 01:00:00', strtotime($row['date']));
            $month = strtotime($month);
            if (!array_key_exists($month, $moneyInMonth)) {
                $moneyInMonth[$month] = 0;
            }
            $moneyInMonth[$month] += $row['amount'];
        }
        ksort($moneyInMonth);

        return $moneyInMonth;
    }

    private function predictMoneyInSalary($data)
    {
        $average = array_sum($data['money_in_full_months']) / count($data['money_in_full_months']);

        return $this->money($average, 5000);
    }

    private function money($value, $round = 0)
    {
        if ($round) {
            $value = ceil($value / $round) * $round;
        }

        return $value;
    }
}
