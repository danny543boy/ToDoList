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
        $result = Event::insert($this->getInsertArray($request));

        if (!$result) {
            return response()->json([self::MESSAGE => self::NEW_FAIL], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([self::MESSAGE => self::SUCCESSFUL]);
    }

    public function updateToDo(updateToDoRequest $request, int $id)
    {
        $data = Event::findOrFail($id);
        $result = $data->update($this->getUpdateArray($request));

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

    public function getInsertArray(newToDoRequest $request)
    {
        $title = $request->validated(Event::TITLE);
        $msg = $request->validated(Event::MSG);
        $time = $request->validated(Event::TIME);

        $result = [
            Event::TITLE => $title,
            Event::MSG => $msg,
            Event::TIME => $time,
        ];

        return $result;
    }

    public function getUpdateArray(updateToDoRequest $request)
    {
        $updateArray = [];
        $title = $request->validated(Event::TITLE, -1);
        $msg = $request->validated(Event::MSG, -1);
        $time = $request->validated(Event::TIME, -1);

        if ($title != -1) {
            $updateArray[Event::TITLE] = $title;
        }
        if ($msg != -1) {
            $updateArray[Event::MSG] = $msg;
        }
        if ($time != -1) {
            $updateArray[Event::TIME] = $time;
        }

        return $updateArray;
    }
}
