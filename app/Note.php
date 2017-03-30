<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Note extends Model
{
    //enable softdeletes
    use SoftDeletes;

    //table name
    protected $table = 'notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message', 'tags', 'user_id',
    ];

    //validation logic
    protected $rules = array(
	'createNote' => array(
            'user_id' => 'required',
            'message' => 'required'
        ),
	'updateNote' => array(
            'id' => 'required',
            'user_id' => 'required',
            'message' => 'required'
        )
    );

    public function validationFails($method, $data)
    {
        // make a new validator object
        $v = Validator::make($data, $this->rules[$method]);
        // return the result
        if ($v->passes()) {
            return false;
        }
        return $v->errors();
    }

    //User relationship
    public function user() {
        return $this->hasOne('App\User');
    }
}
