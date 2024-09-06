<?php

namespace App\Http\Controllers;

use App\Mail\Orders;
use App\Models\User;
use App\Models\ChMessage;
use App\Mail\WelcomeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    
    function sendEmailToUser(Request $request)
    {      
    // $emails=User::all();
    // foreach($emails as $email){
    //     Mail::to($email)->send(new Orders($request));  
    // }
    
    Mail::to($request->email)->send(new Orders($request));  

        return back()->with("status", "Email sent successfully");
    }


     // view email data
     function sendDataView()
     {
         return view('post.send-data-view');
     }



     
     public function markdownTemplate(){
       
        return view('emails.send-email');
    }

    public function markdownTemplatesendemail(Request $request){
        $usersData=User::all();
        
    Mail::to($request->email)->send(new WelcomeEmail($request,$usersData));
    return "email sent using markdown";
    }



    // ajax
    public function getRecordBody(Request $request){
        
        $id = $request->id;
        // dd($id);
        // Find the record by ID and fetch the body
        $record = ChMessage::find($id);
        if ($record) {
            return response()->json(['body' => $record->body]);
        }
        return response()->json(['error' => 'Record not found'], 404);
    }

    
    public function updateMessage(Request $request){
        $id = $request->myuppdateId;
        // dd($id);
        $record = ChMessage::find($id);
        if($record){
            $record->body = $request->message;
            $record->save();
            // return response()->json(['success' => 'Record updated successfully']);
            return back();
        } else{
            return response()->json(['error' => 'Record not found'], 404);
        }
    }
}
