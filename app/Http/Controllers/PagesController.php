<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Redirector;
use Mail;

class PagesController extends Controller
{
    public function getIndex()
    {
        $posts = Post::orderBy('created_at', 'desc')->limit(4)->get();
        return view('pages.welcome')->withPosts($posts);
    }

    public function getAbout()
    {
        $first = "Denis";
        $last = "Chekarev";

        $full = $first . " " . $last;
        $email = "hayabusakras@gmail.com";
        $data=[];
        $data['email'] = $email;
        $data['fullname'] = $full;
        return view('pages.about')->withData($data);
    }

    public function getContact()
    {
        return view('pages.contacts');
    }

    public function postContact(Request $request)
    {
        $this->validate($request, ['email'  =>'required|email',
                                    'subject'=>'min:3',
                                    'message'=>'min:10']);
        $data = array(
            'email' => $request->email,
            'subject' => $request->subject,
            'bodyMessage'  => $request->message
        );
        Mail::send('emails.contact', $data, function($message) use($data) {
            $message->from($data['email']);
            $message->to('Hayabusakras@gmail.com');
            $message->subject($data['subject']);
        });
        Session::flash('success', 'Your Email was sent!');
        return view('blog.index');
    }

}
