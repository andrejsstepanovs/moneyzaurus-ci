<?php

class Client
{
	const GET    = 'GET';
	const POST   = 'POST';
	const DELETE = 'DELETE';

	/** @var resource */
	private $curl;

	/** @var array */
	private $response = [
		'code'  => null,
	    'body'  => null,
	    'data'  => null,
	    'error' => null
	];

	/** @var array */
	private $params = [
		'base'   => null,
		'path'   => null,
		'method' => self::GET,
		'params'   => []
	];

	public function __construct()
	{
		$url = config_item('moneyzaurus');
		if (empty($url)) {
			throw new \RuntimeException('Base url to moneyzaurus server missing.');
		}

		$this->params['base'] = $url;
	}

	private function init()
	{
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curl, CURLOPT_VERBOSE, true);
		//curl_setopt($this->curl, CURLOPT_HEADER, true);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1');
		curl_setopt($this->curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($this->curl, CURLOPT_MAXREDIRS, 3);

		return $this;
	}

	private function prepare()
	{
		$baseUrl = rtrim($this->params['base'], '/') . '/';
		$path    = ltrim($this->params['path'], '/');
		switch ($this->params['method']) {
			case self::POST:
				curl_setopt($this->curl, CURLOPT_URL, $baseUrl . $path);
				curl_setopt($this->curl, CURLOPT_POST, true);
				curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($this->params['params']));
				break;
			case self::GET:
				$url = $baseUrl . $path . '?' . http_build_query($this->params['params']);
				curl_setopt($this->curl, CURLOPT_URL, $url);
				break;
			case self::DELETE:
				curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
				curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($this->params['params']));
				curl_setopt($this->curl, CURLOPT_URL, $baseUrl . $path);
				break;
			default:
				throw new \RuntimeException('Request method not implemented.');
				break;
		}

		return $this;
	}

	public function url($url)
	{
		$this->params['path'] = $url;
		return $this;
	}

	public function method($method)
	{
		$this->params['method'] = $method;
		return $this;
	}

	public function params(array $data)
	{
		$this->params['params'] = $data;
		return $this;
	}

	public function call()
	{
		$this->init()->prepare();

		$this->response['body']  = curl_exec($this->curl);
		$this->response['data']  = json_decode($this->response['body'], true);
		$this->response['code']  = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
		$this->response['error'] = curl_error($this->curl);

		curl_close($this->curl);

		return $this;
	}

	public function data()
	{
		return $this->response['data'];
	}

	public function code()
	{
		return $this->response['code'];
	}

	public function body()
	{
		return $this->response['body'];
	}

	public function error()
	{
		return $this->response['error'];
	}

	public function response()
	{
		return $this->response;
	}
}