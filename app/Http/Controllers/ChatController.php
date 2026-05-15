<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('chat', compact('users'));
    }

    public function getMessages(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
        ]);

        $messages = Message::with('sender', 'receiver')
            ->where(function ($query) use ($request) {
                $query->where('sender_id', $request->sender_id)
                    ->where('receiver_id', $request->receiver_id);
            })
            ->orWhere(function ($query) use ($request) {
                $query->where('sender_id', $request->receiver_id)
                    ->where('receiver_id', $request->sender_id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        $message->load('sender', 'receiver');

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }
}
