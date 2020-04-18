<?php
namespace App\Http\Controllers;

use App\Service\Pushall;
use Illuminate\Http\Request;

class PushServiceController extends Controller
{

    public function form()
    {
        return view('service');
    }

    public function send(Pushall $pushall)
    {
        $data = \request()->validate([
            'title' => 'required|between:15,80',
            'text'   => 'required|max:500',
        ]);
        $pushall->send($data['title'].(string)time(), $data['text']);
        flash('Сообщение успешно отправлено');
        return back();
    }
}
