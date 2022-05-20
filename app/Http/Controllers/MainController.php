<?php

namespace App\Http\Controllers;

use App\Http\Requests\newToDoRequest;
use App\Http\Requests\updateToDoRequest;
use App\Models\Event;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class MainController extends Controller
{
    const MESSAGE = 'message';
    const UPDATE_ERROR = 'update todo fail';
    const NEW_FAIL = 'new todo fail';
    const DELETE_FAIL = 'delete todo fail';
    const SUCCESSFUL = 'successful';
    const DATA = 'data';

    public function newToDo(newToDoRequest $request, int $userId)
    {
        User::findOrFail($userId);
        $result = Event::insert($this->getInsertArray($request, $userId));

        if (!$result) {
            return response()->json([self::MESSAGE => self::NEW_FAIL], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([self::MESSAGE => self::SUCCESSFUL]);
    }

    public function updateToDo(updateToDoRequest $request, int $userId, int $id)
    {
        $data = User::findOrFail($userId)
            ->todos()
            ->findOrFail($id);
        $result = $data->update($this->getUpdateArray($request));

        if (!$result) {
            return response()->json([self::MESSAGE => self::UPDATE_ERROR], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([self::MESSAGE => self::SUCCESSFUL]);
    }

    public function deleteToDo(int $userId, int $id)
    {
        $data = User::findOrFail($userId)
            ->todos()
            ->findOrFail($id);
        $result = $data->delete();

        if (!$result) {
            return response()->json([self::MESSAGE => self::DELETE_FAIL], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([self::MESSAGE => self::SUCCESSFUL]);
    }

    public function deleteAllToDo(int $userId)
    {
        $result = User::findOrFail($userId)
            ->todos()
            ->truncate();

        if (!$result) {
            return response()->json([self::MESSAGE => self::DELETE_FAIL], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([self::MESSAGE => self::SUCCESSFUL]);
    }

    public function getToDo(int $userId, int $id)
    {
        $data = User::findOrFail($userId)
            ->todos()
            ->findOrFail($id);

        return response()->json([
            self::MESSAGE => self::SUCCESSFUL,
            self::DATA => $data,
        ]);
    }

    public function getAllToDo(int $userId)
    {
        $data = User::findOrFail($userId)->todos;

        return response()->json([
            self::MESSAGE => self::SUCCESSFUL,
            self::DATA => $data,
        ]);
    }

    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'password' => ['required', 'confirmed'],
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     event(new Registered($user));

    //     Auth::login($user);

    //     return response()->noContent();
    // }

    public function getInsertArray(newToDoRequest $request, int $userId)
    {
        $title = $request->validated(Event::TITLE);
        $msg = $request->validated(Event::MSG);
        $time = $request->validated(Event::TIME);

        $result = [
            Event::TITLE => $title,
            Event::MSG => $msg,
            Event::TIME => $time,
            Event::USER_ID => $userId,
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
