<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class Feedback extends Controller
{

    public function list()
    {
        return view('feedback', [
            'messages' => Message::orderByDesc('created_at')->paginate(10),
        ]);
    }

    public function new(Request $request)
    {
        $data = $this->validate($request, [
            'email'   => 'required|email',
            'message' => 'required|max:1000',
        ]);
        Message::create([
                'email'   => $data['email'],
                'message' => $data['message'],
        ]);
        return view('contacts', [
            'success' => true,
        ]);
    }
}
