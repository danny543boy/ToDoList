<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function runInsert(Request $request)
    {
        if (empty($request->json()->all())) {
            return response()->json([SELF::MESSAGE => SELF::JSON_ERROR], 400);
        }

        // $this->_checkJson($request);

        $title = $request->input(SELF::TITLE);
        $msg = $request->input(SELF::MSG);

        $result = DB::table(self::TABLE_NAME)->insert([SELF::TITLE => $title, SELF::MSG => $msg]);

        return response()->json([SELF::MESSAGE => SELF::SUCCESSFUL], 200);
    }

    public function runUpdate(Request $request)
    {
        if (empty($request->json()->all())) {
            return response()->json([SELF::MESSAGE => SELF::JSON_ERROR,], 400);
        }

        $id = $request->input(SELF::ID);
        $data = $request->input(SELF::DATA);

        if ($data) {
            $result = DB::table(self::TABLE_NAME)->where(SELF::ID, $id)
            ->update([
                SELF::TITLE => $data[SELF::TITLE],
                SELF::MSG => $data[SELF::MSG]
            ]);
        }

        return response()->json([
            SELF::MESSAGE => SELF::SUCCESSFUL,
        ], 200);
    }

    public function runDelete(Request $request)
    {
        if (empty($request->json()->all())) {
            return response()->json([SELF::MESSAGE => SELF::JSON_ERROR], 400);
        }

        $id = $request->input(SELF::ID);
        $isAll = $request->input(SELF::IS_ALL);

        if ($isAll) {
            $result = DB::table(SELF::TABLE_NAME)->truncate();
        } else {
            $result = DB::table(SELF::TABLE_NAME)->where(SELF::ID, $id)->delete();
        }

        if($result == 1){
            return response()->json([SELF::MESSAGE => SELF::SUCCESSFUL], 200);
        }else{
            return response()->json([SELF::MESSAGE => SELF::DB_ERROR], 400);
        }
    }

    public function runGet(Request $request)
    {
        if (empty($request->json()->all())) {
            return response()->json([SELF::MESSAGE => SELF::JSON_ERROR,], 400);
        }

        $id = $request->input(SELF::ID);
        $isGetAll = $request->input(SELF::IS_ALL);

        if($id){
            $data = DB::table(self::TABLE_NAME)->where(SELF::ID,$id)->get();
        }else if($isGetAll){
            $data = DB::table(self::TABLE_NAME)->get();
        }else{
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
