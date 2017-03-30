<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use DB;

class NotesTest extends TestCase
{
    //User Token:
    private static $headers = [
        'HTTP_Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjVjZjJmZTEzZGM4MDY4MjU2NTBhYmMyY2FmYWU2NjAwYjJiODJhMTJmZDRhNTMxZTJlN2I0YTk0NmU3MGQwZTc3MjEwZmQ3NGYzMDIwMDIyIn0.eyJhdWQiOiIzIiwianRpIjoiNWNmMmZlMTNkYzgwNjgyNTY1MGFiYzJjYWZhZTY2MDBiMmI4MmExMmZkNGE1MzFlMmU3YjRhOTQ2ZTcwZDBlNzcyMTBmZDc0ZjMwMjAwMjIiLCJpYXQiOjE0OTA4NTcyNDgsIm5iZiI6MTQ5MDg1NzI0OCwiZXhwIjoxNTIyMzkzMjQ4LCJzdWIiOiIxIiwic2NvcGVzIjpbIm5vdGVzIl19.nMsrKOrZThw8yokL2nem8GV2HiZdnaw5HZkl6hvjL4VcknimipfvkhssgGUCk_aqszfNQ7N9YFY5l4jQdAEJcDcCb4rs3YeRKwjl-ifwI4KtbZwndaeSeIgeA-OpPqja1Zx_cEko5M6MwHd-0alhBuShrbSueqVHykgp6IkPEuGiHYUJdVeEiJkIuDlPAjxs6M8uynJZILeMOBYdEFNz_OB7of8xa3J6xLo6ksFaWpw_AwP0_hsUxaztxfIQEKHxNZecDacpX8YwET7Ml1E9JiBGYZoHz_7qLsg7ub0ox4f5A1Xre8co12AAkAAPgO5f5tHZ780ZBqyRgP75n1dmDC6T0K41jWSCGW0XQBgCnQEe4vI__zJmb1-qi67ZBi5XkXVTQ5Qlp7bN-FbLjY62X2hOmJAYWUlloEspC-RE4jiBENIKjIiXDLJotU8L_yYf2DD2bs0AphUEdjkrTc1SAgV9RL98SgWVc9yHIWkqKyH9aJrP0Qcnzv_hfyakpgZqhZtzGzNFesn-QD6SvmEb9Q_i_0FdO-KQtmdjLtiNiyaZ1f5mSLt5mw8XwoiTq67Iuba0U0M28MwvQLxFkbVpQMGsimqiFNMuOwOL1Z-cNXyYd7HVvotXv23k7LMk1y-7lx-NxIIHmCetsM36MvZeRc06uaCkDAgCmWwwNJuUyb8'
    ];

    public function testDeleteSuccess()
    {
        DB::table('notes')->truncate();
        DB::table('notes')->insert(
            ['id' => 100,
             'user_id' => 1,
             'message' => 'test delete'
            ]);
        $response = $this->delete('/api/note/100', [], self::$headers);

        $this->assertEquals($response->content(), '{"success":true,"data":[]}');
    }

    public function testDeleteFailure()
    {
        DB::table('notes')->truncate();
        $response = $this->delete('/api/note/100', [], self::$headers);

        $this->assertEquals($response->content(), '{"success":false,"reason":"The note with id(100) does not exist."}');
    }

    public function testInsertSuccess()
    {
        DB::table('notes')->truncate();
        $data = [
             'message' => 'test insert',
             'tags' => ['tag1', 'tag2']
            ];
        $response = $this->post('/api/note', $data, self::$headers);
        $ret = json_decode($response->content(), true);
        $this->assertTrue($ret['success']);
        $this->assertEquals($ret['data']['message'], 'test insert');
        $this->assertEquals($ret['data']['tags'], '["tag1","tag2"]');
    }

    public function testInsertFailure()
    {
        DB::table('notes')->truncate();
        $data = [
             'message' => '',
             'tags' => ''
            ];
        $response = $this->post('/api/note', $data, self::$headers);
        $ret = json_decode($response->content(), true);
        $this->assertFalse($ret['success']);
    }

    public function testEditSuccess()
    {
        DB::table('notes')->truncate();
        DB::table('notes')->insert(
            ['id' => 100,
             'user_id' => 1,
             'message' => 'test'
            ]);
        $data = [
             'message' => 'test edit',
             'tags' => '["tag1"]'
            ];
        $response = $this->put('/api/note/100', $data, self::$headers);
        $ret = json_decode($response->content(), true);
        $this->assertTrue($ret['success']);
        $this->assertEquals($ret['data']['message'], 'test edit');
        $this->assertEquals($ret['data']['tags'], '["tag1"]');
    }

    public function testEditFailureNoId()
    {
        DB::table('notes')->truncate();
        DB::table('notes')->insert(
            ['id' => 100,
             'user_id' => 1,
             'message' => 'test'
            ]);
        $data = [
             'message' => 'test test',
             'tags' => '["tag1"]'
            ];
        $response = $this->put('/api/note/99', $data, self::$headers);
        $ret = json_decode($response->content(), true);
        $this->assertFalse($ret['success']);
    }

    public function testEditFailureNoMessage()
    {
        DB::table('notes')->truncate();
        DB::table('notes')->insert(
            ['id' => 100,
             'user_id' => 1,
             'message' => 'test'
            ]);
        $data = [
             'message' => '',
             'tags' => '["tag1"]'
            ];
        $response = $this->put('/api/note/100', $data, self::$headers);
        $ret = json_decode($response->content(), true);
        $this->assertFalse($ret['success']);
    }

    //TODO: create JSON test data flat file for large objects
    public function testGetNotes()
    {
        DB::table('notes')->truncate();
        DB::table('notes')->insert(
            ['id' => 100,
             'user_id' => 1,
             'message' => 'test1'
            ]);
        DB::table('notes')->insert(
            ['id' => 101,
             'user_id' => 1,
             'message' => 'test2',
             'tags' => '["tag2"]'
            ]);
        DB::table('notes')->insert(
            ['id' => 102,
             'user_id' => 1,
             'message' => 'test3',
             'tags' => '["tag2", "tag3"]'
            ]);
        $response = $this->get('/api/notes/1/50', self::$headers);
        $ret = json_decode($response->content(), true);
        $this->assertTrue($ret['success']);
        $this->assertEquals($ret['data'][2]['id'], 102);
        $this->assertEquals($ret['data'][1]['id'], 101);
        $this->assertEquals($ret['data'][0]['id'], 100);
        $this->assertEquals($ret['data'][2]['message'], 'test3');
        $this->assertEquals($ret['data'][1]['message'], 'test2');
        $this->assertEquals($ret['data'][0]['message'], 'test1');
        $this->assertEquals($ret['data'][0]['tags'], null);
        $this->assertEquals($ret['data'][1]['tags'], '["tag2"]');
        $this->assertEquals($ret['data'][2]['tags'], '["tag2", "tag3"]');
    }

}
