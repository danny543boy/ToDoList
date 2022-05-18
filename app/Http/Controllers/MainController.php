<?php

namespace App\Http\Controllers;

use App\Http\Requests\newToDoRequest;
use App\Http\Requests\updateToDoRequest;
use App\Models\Event;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class MainController extends Controller
{
    const MESSAGE = 'message';
    const UPDATE_ERROR = 'update todo fail';
    const NEW_FAIL = 'new todo fail';
    const DELETE_FAIL = 'delete todo fail';
    const SUCCESSFUL = 'successful';
    const DATA = 'data';

    public function newToDo(newToDoRequest $request)
    {
        $result = Event::insert($request->getInsertArray());

        if (!$result) {
            return response()->json([self::MESSAGE => self::NEW_FAIL], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([self::MESSAGE => self::SUCCESSFUL]);
    }

    public function updateToDo(updateToDoRequest $request)
    {
        $id = $request->input(Event::ID);
        $data = Event::findOrFail($id);
        $result = $data->update($request->getUpdateArray());

        if (!$result) {
            return response()->json([self::MESSAGE => self::UPDATE_ERROR], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([self::MESSAGE => self::SUCCESSFUL]);
    }

    public function deleteToDo(int $id)
    {
        $data = Event::findOrFail($id);
        $result = $data->delete();

        if (!$result) {
            return response()->json([self::MESSAGE => self::DELETE_FAIL], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([self::MESSAGE => self::SUCCESSFUL]);
    }

    public function deleteAllToDo()
    {
        // TODO:目前還沒有User表，先加入此api，將來加入User後修正
        $result = Event::truncate();

        if (!$result) {
            return response()->json([self::MESSAGE => self::DELETE_FAIL], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([self::MESSAGE => self::SUCCESSFUL]);
    }

    public function getToDo(int $id)
    {
        $data = Event::findOrFail($id);

        return response()->json([
            self::MESSAGE => self::SUCCESSFUL,
            self::DATA => $data,
        ]);
    }

    public function getAllToDo()
    {
        $data = Event::all();

        return response()->json([
            self::MESSAGE => self::SUCCESSFUL,
            self::DATA => $data,
        ]);
    }
}
