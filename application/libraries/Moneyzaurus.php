<?php

class Moneyzaurus
{
	const TOKEN_COOKIE_NAME = 'token';

	/** @var Client */
	private $client;

	/** @var string */
	private $token;

	/** @var object */
	private $instance;

	/** @var User */
	private $user;

	public function __construct()
	{
		$this->instance =& get_instance();
		$this->client = $this->instance->client;
		$this->instance->load->library('user');
		$this->user = $this->instance->user;
	}

	public function setToken($token)
	{
		if (is_array($token) && !empty($token['code']) && !empty($token['data']['data']['token'])) {
			if ($token['code'] == 200) {
				$this->token = $token['data']['data']['token'];
			}
		}

		$this->token = $token;

		return $this;
	}

	private function getToken()
	{
		if ($this->token === null) {
			$this->setToken($this->user->findToken());
		}

		return $this->token;
	}

	public function userRegister(
		$username,
		$password,
		$timezone = 'Europe/Berlin',
		$name     = '',
		$language = 'en_EN',
		$locale   = 'en_EN'
	) {
		$request = [
			'username'     => $username,
	        'password'     => $password,
	        'timezone'     => $timezone,
	        'display_name' => $name,
	        'language'     => $language,
	        'locale'       => $locale
		];

		return $this->client
					->url('user/register')
					->method(Client::POST)
					->params($request)
					->call()
					->response();
	}

	public function userUpdate($email, $name, $locale, $language, $timezone)
	{
		$request = [
			'email'    => $email,
	        'name'     => $name,
	        'locale'   => $locale,
	        'language' => $language,
	        'timezone' => $timezone,
		];

		return $this->client
					->url('user/update')
					->method(Client::POST)
					->params(array_merge($request, ['token' => $this->getToken()]))
					->call()
					->response();
	}

	public function userData()
	{
		return $this->client
			->url('user/data')
			->method(Client::GET)
			->params(['token' => $this->getToken()])
			->call()
			->response();
	}

	public function authenticateLogin($username, $password)
	{
		$request = [
			'username' => $username,
			'password' => $password
		];

		$return = $this->client
			->url('authenticate/login')
			->method(Client::POST)
			->params($request)
			->call()
			->response();

		$this->setToken($return);

		return $return;
	}

	public function authenticateLogout()
	{
		return $this->client
					->url('authenticate/logout')
					->method(Client::GET)
					->params(['token' => $this->getToken()])
					->call()
					->response();
	}

	public function authenticatePasswordRecovery($username)
	{
		return $this->client
					->url('authenticate/password-recovery')
					->method(Client::GET)
					->params(['username' => $username])
					->call()
					->response();
	}

	public function connectionList($parent)
	{
		$request = ['parent' => $parent, 'token' => $this->getToken()];

		return $this->client
					->url('connection/list')
					->method(Client::GET)
					->params($request)
					->call()
					->response();
	}

	public function connectionAdd($email)
	{
		$request = ['email' => $email, 'token' => $this->getToken()];

		return $this->client
					->url('connection/add')
					->method(Client::POST)
					->params($request)
					->call()
					->response();
	}

	public function connectionReject($id)
	{
		return $this->client
					->url('connection/reject/' . $id)
					->method(Client::POST)
					->params(['token' => $this->getToken()])
					->call()
					->response();
	}

	public function connectionAccept($id)
	{
		return $this->client
					->url('connection/accept/' . $id)
					->method(Client::POST)
					->params(['token' => $this->getToken()])
					->call()
					->response();
	}

	public function chartPie($currency, $from, $till)
	{
		$request = [
			'currency' => $currency,
			'from'     => $from,
			'till'     => $till,
			'token'    => $this->getToken()
		];

		return $this->client
			->url('chart/pie')
			->method(Client::GET)
			->params($request)
			->call()
			->response();
	}

	public function predictPrice($item, $group)
	{
		$request = [
			'item'  => $item,
			'group' => $group,
			'token' => $this->getToken()
		];

		return $this->client
			->url('predict/price')
			->method(Client::POST)
			->params($request)
			->call()
			->response();
	}

	public function predictGroup($item)
	{
		$request = [
			'item'  => $item,
			'token' => $this->getToken()
		];

		return $this->client
			->url('predict/group')
			->method(Client::POST)
			->params($request)
			->call()
			->response();
	}

	public function distinctItems($from, $count)
	{
		$request = [
			'from'  => $from,
			'count' => $count,
			'token' => $this->getToken()
		];

		return $this->client
			->url('distinct/items')
			->method(Client::GET)
			->params($request)
			->call()
			->response();
	}

	public function distinctGroups($from, $count)
	{
		$request = [
			'from'  => $from,
			'count' => $count,
			'token' => $this->getToken()
		];

		return $this->client
			->url('distinct/groups')
			->method(Client::GET)
			->params($request)
			->call()
			->response();
	}

	public function transactionsAdd($item, $group, $price, $currency, $date)
	{
		$request = [
			'item'     => $item,
			'group'    => $group,
			'price'    => $price,
			'currency' => $currency,
			'date'     => $date,
			'token'    => $this->getToken()
		];

		return $this->client
			->url('transactions/add')
			->method(Client::POST)
			->params($request)
			->call()
			->response();
	}

	public function transactionsList($offset, $limit, $from, $till, $item, $group, $price)
	{
		$request = [
			'offset' => $offset,
			'limit'  => $limit,
			'from'   => $from,
			'till'   => $till,
			'item'   => $item,
			'group'  => $group,
			'price'  => $price,
			'token'  => $this->getToken()
		];

		return $this->client
			->url('transactions/list')
			->method(Client::GET)
			->params($request)
			->call()
			->response();
	}

	public function transactionsUpdate($id, $item, $group, $price, $currency, $date)
	{
		$request = [
			'item'     => $item,
			'group'    => $group,
			'price'    => $price,
			'currency' => $currency,
			'date'     => $date,
			'token'    => $this->getToken()
		];

		return $this->client
			->url('transactions/update/' . $id)
			->method(Client::POST)
			->params($request)
			->call()
			->response();
	}

	public function transactionsRemove($id)
	{
		return $this->client
			->url('transactions/remove/' . $id)
			->method(Client::DELETE)
			->params(['token' => $this->getToken()])
			->call()
			->response();
	}

	public function transactionsId($id)
	{
		return $this->client
			->url('transactions/id/' . $id)
			->method(Client::GET)
			->params(['token' => $this->getToken()])
			->call()
			->response();
	}

	public function version()
	{
		return $this->client->url('/')->method(Client::GET)->params([])->call()->response();
	}
}
