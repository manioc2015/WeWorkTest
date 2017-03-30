<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Note;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Create Note
     *
     * @return array
     */
    public function createNote(Request $request) {
        $n = new Note();
        $data = $request->all();
        $data['user_id'] = $n->user_id = Auth::user()->id;
        $errors = $n->validationFails(__FUNCTION__, $data);
        $n->message = $data['message'];
        $n->tags = json_encode($data['tags']);
        if (!$errors) {
            $n->save();
            return self::returnSuccess($n);
        } 
        return self::returnFailure($errors->first());
    }

    /**
     * Update Note
     *
     * @return array
     */
    public function updateNote(Request $request, $id) {
        $n = Note::find($id);
        if (!$n) {
            return self::returnFailure(__('notes.note_not_exist', ['id' => $id]));
        }
        $data = $request->all();
        $data['id'] = $id;
        $data['user_id'] = $n->user_id;
        $errors = $n->validationFails(__FUNCTION__, $data);
        $n->message = $data['message'];
        $n->tags = $data['tags'];
        if (!$errors) {
            if ($n->user_id != Auth::user()->id) {
                return self::returnFailure(__('notes.note_not_owner'));
            }
            $n->save();
            return self::returnSuccess($n);
        } 
        return self::returnFailure($errors->first());
    }

    /**
     * Delete a note
     *
     * @return boolean
     */
    public function deleteNote($id) {
        $n = Note::find($id);
        if (!$n) {
            return self::returnFailure(__('notes.note_not_exist', ['id' => $id]));
        }
        if ($n->user_id != Auth::user()->id) {
            return self::returnFailure(__('notes.note_not_owner'));
        }
        if ($n->delete()) {
            return self::returnSuccess([]);
        }
    }

    /**
     * Fetch Notes
     *
     * @return array
     */
    public function getNotes($page = 1, $limit = 50) {
        $data = Note::where('user_id', Auth::user()->id)
            ->skip($page - 1)
            ->take($limit)
            ->orderBy('created_at', 'DESC')
            ->get();
        return self::returnSuccess($data);
    }
}
