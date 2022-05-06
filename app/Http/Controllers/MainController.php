<?php

namespace App\Http\Controllers;

use App\Http\Requests\deleteToDoRequest;
use App\Http\Requests\getToDoRequest;
use App\Http\Requests\newToDoRequest;
use App\Http\Requests\updateToDoRequest;
use App\Models\Events;

class MainController extends Controller
{
    const TABLE_NAME = 'events';
    const ID = 'id';
    const TITLE = 'title';
    const MSG = 'msg';
    const MESSAGE = 'message';
    const JSON_ERROR = 'The request is not a valid JSON';
    const DB_ERROR = 'The DB error';
    const SUCCESSFUL = 'successful';
    const IS_ALL = 'isAll';
    const DATA = 'data';
    const TIME = 'time';

    public function newToDo(newToDoRequest $request)
    {
        $title = $request->input(SELF::TITLE);
        $msg = $request->input(SELF::MSG);
        $time = $request->input(SELF::TIME);
        $result = Events::insert([SELF::TITLE => $title, SELF::MSG => $msg, SELF::TIME => $time]);

        if ($result == 1) {
            return response()->json([SELF::MESSAGE => SELF::SUCCESSFUL], 201);
        } else {
            return response()->json([SELF::MESSAGE => SELF::DB_ERROR], 400);
        }
    }

    public function updateToDo(updateToDoRequest $request)
    {
        $id = $request->input(SELF::ID);
        $result = Events::whereId($id)->update($request->all());

        if ($result == 1) {
            return response()->json([SELF::MESSAGE => SELF::SUCCESSFUL], 201);
        } else {
            return response()->json([SELF::MESSAGE => SELF::DB_ERROR], 400);
        }
    }

    public function deleteToDo(deleteToDoRequest $request)
    {
        $id = $request->input(SELF::ID);
        $isAll = $request->input(SELF::IS_ALL);

        if ($isAll) {
            $result = Events::truncate();
        } else {
            $result = Events::whereId($id)->delete();
        }

        if ($result == 1) {
            return response()->json([SELF::MESSAGE => SELF::SUCCESSFUL], 200);
        } else {
            return response()->json([SELF::MESSAGE => SELF::DB_ERROR], 400);
        }
    }

    public function getToDo(getToDoRequest $request)
    {
        $id = $request->input(SELF::ID);
        $isGetAll = $request->input(SELF::IS_ALL);

        if ($id) {
            $data = Events::whereId($id)->get();
        } else if ($isGetAll) {
            $data = Events::all();
        } else {
            $data = null;
        }

        if ($data) {
            return response()->json([
                SELF::MESSAGE => SELF::SUCCESSFUL,
                SELF::DATA => $data
            ]);
        } else {
            // db error
            return response()->json([
                SELF::MESSAGE => SELF::DB_ERROR,
            ], 500);
        }
    }

    // private function _checkJson(Request $request)
    // {
    //     if (empty($request->json()->all())) {
    //         return response()->json([
    //             SELF::MESSAGE => 'The request is not a valid JSON.',
    //         ], 400);
    //     }
    // }
}
