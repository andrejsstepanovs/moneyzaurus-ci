<?php

class User
{
	const TOKEN_COOKIE_NAME = 'token';

	/** @var object */
	private $instance;

	public function __construct()
	{
		$this->instance =& get_instance();
		$this->client = $this->instance->client;
	}

	public function findToken()
	{
		$this->instance->load->library('session');
		$this->instance->load->helper('cookie');

		$userData    = $this->instance->session->userdata();
		$cookieToken = get_cookie('token');

		if (!empty($userData) && !empty($userData['token'])) {
			$token = $userData['token'];
			if (!empty($cookieToken) && $cookieToken != $token) {
				throw new \RuntimeException('Token dose not match.');
			}

			return $token;
		}

		return $cookieToken;
	}

	public function saveToken(array $userData)
	{
		$this->instance->load->library('session');
		$this->instance->load->helper('cookie');

		set_cookie(self::TOKEN_COOKIE_NAME, $userData['token'], 31536000);
		$this->instance->session->set_userdata($userData);

		return $this;
	}
}
