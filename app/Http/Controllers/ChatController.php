<?php

namespace App\Http\Controllers;

use App\Events\MessageCreated;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia("Chat/Chats", [
            "chats" => Auth::user()
                            ->chats()
                            ->with('participants')
                            ->with('messages', function ($query) {
                                $query->latest()->limit(1);
                            })
                            ->orderByDesc("updated_at")
                            ->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $chat = Chat::create();

        foreach ($request->input('participants') as $participant) {
            $chat->participants()->attach($participant['id']);
        }

        $message = New Message([
            'text' => $request->input('text')
        ]);

        $message->sender()->associate(auth()->user());
        $message->chat()->associate($chat);
        $message->save();

        event(new MessageCreated($message));

        return redirect('/chat/' . $chat->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat)
    {
        return inertia('Chat/ConversationView', [
            'messages' => Message::where('chat_id', $chat->id)
                                    ->with('chat')
                                    ->with('sender')
                                    ->orderBy('created_at')
                                    ->get(),
            'chat' => $chat->where('id', $chat->id)->with('participants')->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
