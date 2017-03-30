<?php
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

// First route that user visits on consumer app
Route::get('/issueToken', function () {
    // Build the query parameter string to pass auth information to our request
    $query = http_build_query([
        'client_id' => 3,
        'redirect_uri' => 'http://wework.evolutionaryascension.com/callbackRoute',
        'response_type' => 'code',
        'scope' => 'notes'
    ]);

    // Redirect the user to the OAuth authorization page
    return redirect('http://wework.evolutionaryascension.com/oauth/authorize?' . $query);
});

// Route that user is forwarded back to after approving on server
Route::get('callbackRoute', function (Request $request) {
    $http = new GuzzleHttp\Client;

    $response = $http->post('http://wework.evolutionaryascension.com/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => 3, // from admin panel above
            'client_secret' => 'zpAcUnRBzKiC5kJJrdupN53GGZJ09pntFy69tPe1', // from admin panel above
            'redirect_uri' => 'http://wework.evolutionaryascension.com/callbackRoute',
            'code' => $request->code // Get code from the callback
        ]
    ]);

    // echo the access token; normally we would save this in the DB
    return json_decode((string) $response->getBody(), true)['access_token'];
});

// Route that lists the user's repositories
Route::get('/showRepos', 'GithubController@listRepos');

// Route that lists the user's repositories
Route::get('/showRepoIssues/{owner}/{repo}', 'GithubController@listRepoIssues');
