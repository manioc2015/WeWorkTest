<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\GithubRepository;

class GithubController extends Controller
{
    private $githubRepo;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(GithubRepository $repo)
    {
        $this->middleware('auth');
        $this->githubRepo = $repo;
    }

    /**
     * Show the list of Github repos.
     *
     * @return \Illuminate\Http\Response
     */
    public function listRepos()
    {
        $response = $this->githubRepo->makeRequest('list-user-repos');
        if (isset($response['info']) && $response['info']['http_code'] == 200) {
            $data = json_decode($response['body'], false);
            return view('repos', ['repos' => $data]);
        }
    }

    /**
     * Show the list of Github repo issues.
     *
     * @return \Illuminate\Http\Response
     */
    public function listRepoIssues($owner, $repo)
    {
        $response = $this->githubRepo->makeRequest('list-repo-issues', ['owner' => $owner, 'repo' => $repo]);
        if (isset($response['info']) && $response['info']['http_code'] == 200) {
            $data = json_decode($response['body'], false);
            return view('issues', ['issues' => $data]);
        }
    }
}
