<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request, User $user)
    {
        $request->validate([
            'body'           => 'required|string|max:2000',
            'attachments'    => 'nullable|array',
            'attachments.*'  => 'file|max:10240|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,gif,zip,txt',
        ]);

        // Handle multiple files — store as JSON array
        $paths = [];
        $names = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $names[] = $file->getClientOriginalName();
                $paths[] = $file->store('messages', 'public');
            }
        }

        Message::create([
            'user_id'         => $user->id,
            'body'            => $request->body,
            'attachment'      => !empty($paths) ? json_encode($paths) : null,
            'attachment_name' => !empty($names) ? json_encode($names) : null,
            'seen'            => false,
        ]);

        return back()->with('success', "Message sent to {$user->name}.");
    }

    public function destroy(Message $message)
    {
        if ($message->attachment) {
            $paths = json_decode($message->attachment, true) ?? [$message->attachment];
            foreach ($paths as $path) {
                \Storage::disk('public')->delete($path);
            }
        }
        $message->delete();
        return back()->with('success', 'Message deleted.');
    }
}
