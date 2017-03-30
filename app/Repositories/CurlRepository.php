<?php

namespace App\Repositories;

abstract class CurlRepository {
	/**
	 * ENCODING_* constants, used for specifying encoding options
	 */
	const ENCODING_QUERY = 0;
	const ENCODING_JSON = 1;
	const ENCODING_RAW = 2;

	/**
         * The cURL resource id
         */
	protected $ch;

	/**
	 * Allowed methods => allows postdata
	 *
	 * @var array
	 */
	protected $methods = array(
		'GET'     => false,
		'HEAD'    => false,
		'POST'    => true,
		'PUT'     => true,
		'PATCH'   => true,
		'DELETE'  => false,
		'OPTIONS' => false,
	);

	/**
         * The HTTP method
         */
	protected $method;

	/**
         * The form input data encoding
         */
	protected $encoding;

	/**
         * The form input data (if any)
         */
	protected $data;

	/**
         * The base URL
         */
	protected $baseUrl;

	/**
         * The query string
         */
	protected $queryString;

	/**
	 * The headers.
	 *
	 * @var array
	 */
	protected $headers = array();

	/**
	 * The curl options.
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * Set the default headers for every request.
	 *
	 * @param array $headers
	 */
	public function setHeaders(array $headers)
	{
		$this->headers = $headers;
	}

	/**
	 * Get the headers.
	 *
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * Set the curl options for every request.
	 *
	 * @param array $options
	 */
	public function setOptions(array $options)
	{
		$this->options = $options;
	}

	/**
	 * Get the options.
	 *
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Set the request method.
	 *
	 * @param string
	 */
	public function setMethod($method)
	{
		$this->method = $method;
	}

	/**
	 * Get the request method.
	 *
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * Set the form data encoding.
	 *
	 * @param string
	 */
	public function setEncoding($encoding)
	{
		$this->encoding = $encoding;
	}

	/**
	 * Get the encoding.
	 *
	 * @return CurlResource::ENCODING_*
	 */
	public function getEncoding()
	{
		return $this->encoding;
	}

	/**
	 * Set the request data input.
	 *
	 * @param array
	 */
	public function setData(array $data)
	{
		$this->data = $data;
	}

	/**
	 * Get the request data input.
	 *
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Set the cURL base URL.
	 *
	 * @param string
	 */
	public function setBaseUrl($url)
	{
		$this->baseUrl = $url;
	}

	/**
	 * Get the cURL base URL.
	 *
	 * @return string
	 */
	public function getBaseUrl()
	{
		return $this->baseUrl;
	}

	/**
	 * Get the query string.
	 *
	 * @return string
	 */
	public function getQueryString()
	{
		return $this->queryString;
	}

	/**
	 * Set the query string.
	 *
	 * @param string
	 */
	public function setQueryString($query)
	{
		$this->queryString = $query;
	}

        /**
         * Get URL
         */
        public function getUrl() {
		return $this->getBaseUrl() . $this->getQueryString();
        }

	/**
	 * Format the headers to an array of 'key: val' which can be passed to
	 * curl_setopt.
	 *
	 * @return array
	 */
	public function formatHeaders()
	{
		$headers = array();
		foreach ($this->headers as $key => $val) {
			if (is_string($key)) {
				$headers[] = $key . ': ' . $val;
			} else {
				$headers[] = $val;
			}
		}
		return $headers;
	}

	/**
	 * Prepare the curl resource for sending a request.
	 *
	 * @param  Request $request
	 *
	 * @return void
	 */

	/**
	 * Encode the POST data as a string.
	 *
	 * @return string
	 */
	public function encodeData()
	{
		switch ($this->encoding) {
			case static::ENCODING_JSON:
				return json_encode($this->data);
			case static::ENCODING_QUERY:
				return http_build_query($this->data);
			case static::ENCODING_RAW:
				return $this->data;
			default:
				throw new \Exception("Encoding [$encoding] not a known CurlResource::ENCODING_* constant");
		}
	}

	public function prepareRequest($data = array(), $encoding = self::ENCODING_JSON)
	{
		$this->setData($data);
                $this->setEncoding($encoding);
		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_HEADER, true);
		curl_setopt($this->ch, CURLOPT_URL, $this->getUrl());
		$options = $this->getOptions();
		if (!empty($options)) {
			curl_setopt_array($this->ch, $options);
		}
		$method = $this->getMethod();
		if ($method === 'POST') {
			curl_setopt($this->ch, CURLOPT_POST, 1);
		} elseif ($method !== 'GET') {
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $method);
		}
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->formatHeaders());
		if ($this->methods[$method] === true) {
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->encodeData());
		}
	}
	/**
	 * Send a request.
	 *
         * @param  array $data
         *
	 * @return array
	 *

	 */
	public function sendRequest($data = array())
	{
		$this->prepareRequest($data);
		$result = curl_exec($this->ch);
		if ($result === false) {
			$errno = curl_errno($this->ch);
			$errmsg = curl_error($this->ch);
			$msg = "cURL request failed with error [$errno]: $errmsg";
			curl_close($this->ch);
			throw new cURLException($request, $msg, $errno);
		}
		$response = $this->createResponse($result);
		curl_close($this->ch);
		return $response;
	}
	/**
	 * Extract the response info, header and body from a cURL response. Saves
	 * the data in variables stored on the object.
	 *
	 * @param  string $response
	 *
	 * @return Response
	 */
	protected function createResponse($response)
	{
		$info = curl_getinfo($this->ch);
		$headerSize = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
		$headers = substr($response, 0, $headerSize);
		$body = substr($response, $headerSize);
		return ['info' => $info, 'headers' => $headers, 'body' => $body];
	}
}
