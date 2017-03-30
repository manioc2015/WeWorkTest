<?php

namespace App\Repositories;

class GithubRepository extends CurlRepository {
	/**
	 * The Github access token
	 */
	private $accessToken;

	/**
	 * The Github base url
	 */
	const githubUrl = "https://api.github.com";

	/**
	 * The Github API version
	 */
	const accept = "application/vnd.github.v3+json";

	/**
	 * Supported Github endpoints
	 */
	public static $supportedEndpoints = [
		'list-user-repos' => ['method' => 'GET', 'endpoint' => '/user/repos/'],
		'list-repo-issues' => ['method' => 'GET', 'endpoint' => '/repos/:owner/:repo/issues/']
		];

	public function __construct() {
		$this->accessToken = env('GITHUB_ACCESS_TOKEN');
	}

	public function makeRequest($endpoint, $params = array()) {
		if (!isset(self::$supportedEndpoints[$endpoint])) {
			throw new \Exception(__('github.unsupported_endpoint', ['endpoint' => $endpoint]));
		}
		$this->setMethod(self::$supportedEndpoints[$endpoint]['method']);
		$this->setBaseUrl(self::githubUrl);
		$queryString = self::$supportedEndpoints[$endpoint]['endpoint'];
                foreach ($params as $k => $v) {
                    $queryString = str_replace(':'.$k, $v, $queryString);
                }
                $pos = strpos($queryString, ':');
                $end = strpos(substr($queryString, $pos), '/');
                if ($pos !== false) {
			throw new \Exception(__('github.missing_param', ['param' => substr($queryString, $pos+1, $end-$pos)]));
                }
		$this->setQueryString(substr($queryString, 0, -1));
                $headers = [
			'Authorization' => 'Bearer ' . $this->accessToken,
			'Accept' => self::accept,
			'User-Agent' => 'Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405'
			];
		$this->setHeaders($headers);
                return $this->sendRequest();
        }
}
