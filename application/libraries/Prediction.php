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
        $data['monthly_transactions']  = $this->monthlyTransactions($data);

        return $data;
    }

    private function predictMoneyYouHave(array $data)
    {
        if (empty($data['money'])) {
            $value = ceil($data['money_still_to_spend'] / 100);
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

    private function isAmountSimilar($amountOne, $amountTwo)
    {
        // if money is less than 10%
        $min = min($amountOne, $amountTwo);
        if (abs($amountOne - $amountTwo) < ($min / 100 * 10)) {
            return true;
        }

        return false;
    }

    private function monthlyTransactions(array $data)
    {
        $keys = array_keys($data['money_in_full_months']);
        $tillTimestamp = strtotime(date('Y-m-t 23:59:59', $keys[count($keys) - 1]));

        $response = ['rows' => []];

        foreach ($this->transactions as $transaction) {
            foreach ($this->transactions as $row) {
                if (
                    $row['id'] == $transaction['id']
                    || $row['groupName'] != $transaction['groupName']
                    || $row['itemName'] != $transaction['itemName']
                    || strtotime($row['date']) <= $tillTimestamp
                ) {
                    continue;
                }

                if ($this->isAmountSimilar($row['amount'], $transaction['amount'])) {
                    $key = $row['groupName'] . '_' . $row['itemName'];
                    @$response['rows'][$key][$transaction['id']] = $transaction;
                }
            }
        }

        $total  = 0;
        $months = count($data['money_in_months']) - 1;
        foreach ($response['rows'] as $key => $rows) {
            if (count($rows) != $months) {
                unset($response['rows'][$key]);
                continue;
            }

            $tmp = 0;
            foreach ($rows as $row) {
                $tmp += $row['amount'];
            }

            $response['rows'][$key] = [
                'money' => ceil($tmp / count($rows) / 100),
                'item'  => $row['itemName'],
                'group' => $row['groupName'],
            ];

            $total += $response['rows'][$key]['money'];
        }

        usort(
            $response['rows'],
            function($a, $b) {
                return $a['money'] > $b['money'] ? -1 : 1;
            }
        );

        $response['total'] = $total;
        $response['payed'] = 0;

        foreach ($this->transactions as $transaction) {
            if (strtotime($transaction['date']) <= $tillTimestamp) {
                continue;
            }

            foreach ($response['rows'] as $key => $row) {
                if ($row['group'] != $transaction['groupName'] || $row['item'] != $transaction['itemName']) {
                    continue;
                }

                if ($this->isAmountSimilar($row['money'] * 100, $transaction['amount'])) {
                    $response['payed'] += $row['money'];
                    $response['rows'][$key]['payed'] = true;
                }
            }
        }

        if ($response['payed'] > $response['total']) {
            $response['payed'] = $response['total'];
        }

        return $response;
    }
}
