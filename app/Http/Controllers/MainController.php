<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\newToDoRequest;
use App\Http\Requests\updateToDoRequest;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $userId = Auth::user()->id;
        $result = Event::insert($this->getInsertArray($request, $userId));

        if (!$result) {
            return response()->json([self::MESSAGE => self::NEW_FAIL], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([self::MESSAGE => self::SUCCESSFUL]);
    }

    public function updateToDo(updateToDoRequest $request, int $id)
    {
        $userId = Auth::user()->id;
        $data = User::findOrFail($userId)
            ->todos()
            ->findOrFail($id);
        $result = $data->update($this->getUpdateArray($request));

        if (!$result) {
            return response()->json([self::MESSAGE => self::UPDATE_ERROR], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([self::MESSAGE => self::SUCCESSFUL]);
    }

    public function deleteToDo(int $id)
    {
        $userId = Auth::user()->id;
        $data = User::findOrFail($userId)
            ->todos()
            ->findOrFail($id);
        $result = $data->delete();

        if (!$result) {
            return response()->json([self::MESSAGE => self::DELETE_FAIL], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([self::MESSAGE => self::SUCCESSFUL]);
    }

    public function deleteAllToDo()
    {
        $userId = Auth::user()->id;
        $result = User::findOrFail($userId)
            ->todos()
            ->truncate();

        if (!$result) {
            return response()->json([self::MESSAGE => self::DELETE_FAIL], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([self::MESSAGE => self::SUCCESSFUL]);
    }

    public function getToDo(int $id)
    {
        $userId = Auth::user()->id;
        $data = User::findOrFail($userId)
            ->todos()
            ->findOrFail($id);

        return response()->json([
            self::MESSAGE => self::SUCCESSFUL,
            self::DATA => $data,
        ]);
    }

    public function getAllToDo()
    {
        $data = Auth::user()->todos;

        return response()->json([
            self::MESSAGE => self::SUCCESSFUL,
            self::DATA => $data,
        ]);
    }

    /**
     * 登入
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        $email = $request->validated('email');
        $user = User::where('email', $email)->firstOrFail();
        $token = $user->createToken('token-name')->plainTextToken;

        return response()->json([
            "MESSAGE" => "登入成功",
            "token" => $token,
        ]);
    }

    /**
     * 登出
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Auth::user()->tokens()->delete();

        return response()->json([
            "MESSAGE" => "已登出",
        ], SymfonyResponse::HTTP_OK);
    }

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
