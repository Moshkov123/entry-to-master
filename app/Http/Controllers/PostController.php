<?php

namespace App\Http\Controllers;

use App\Models\Recordingtime;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Contact;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function post()
    {
        return view("post");
    }
    public function post_check(Request $request)
    {
        $validData = $request->validate([
            'airdatepicker' => 'required|min:2|max:120',
        ]);
    
        $request->session()->flash('airdatepicker', $request->input('airdatepicker'));
    
        // Get all contacts for the selected date
        $contacts = Contact::where('day', $request->input('airdatepicker'))->get();
    
        // Retrieve the associated Recordingtime for each contact
        $times = [];
        foreach ($contacts as $contact) {
            $times[] = Recordingtime::find($contact->recordingtimes_id);
        }
        $users = [];
        foreach ($contacts as $contact) {
            $users [] = User::find($contact->users_id);
        }

        $days = [];
        foreach ($contacts as $contact) {
            $days [] = Contact::find($contact->day);
        }
    
        return view("post", compact('contacts', 'times', 'users'));
    }

    public function delete($id)
    {
        $contact = Contact::find($id);
        if ($contact) {
            $contact->delete();
        }
        return redirect()->route('post');
    }
    
}
