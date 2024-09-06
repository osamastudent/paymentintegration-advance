<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Response;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAiController extends Controller
{
    

	public function index(Request $request)
	{
    
        $apiKey = env('OPENAI_API_KEY');

// dd($apiKey);

    $search="who is google";
$data=Http::withHeaders([
'Content-Type'=>'application/json',
'Authorization' => 'Bearer ' . $apiKey,
])
->post('https://api.openai.com/v1/chat/completions',[
'model' => 'gpt-3.5-turbo',
			'messages' => [
				['role' => 'user', 
                'content' => $search
            ]
			],
            'temperature'=>0.5,
            'max-tokens'=>100,
            'top_p'=>1.0,
            'frequency_penalty'=>0.52,
            'presence_penalty'=>0.5,
            'stop'=>["11."],
])->json();
dd($data);

return response()->json([$data['choices'][0]['messages'],200,array(),JSON_PRETTY_PRINT]);
    }

// public function index(Request $request)
// 	{
// 		// $request->validate([
// 		// 	'question' => 'required',
// 		// ]);
		
// 		// $question = $request->question;
// 		$question ="who is google";
// // dd($question);
// 		$response = OpenAi::chat()->create([
// 			'model' => 'gpt-3.5-turbo',
// 			'messages' => [
// 				['role' => 'user', 'content' => $question],
// 			],
// 		]);

//         dd($response);
// 		$answer = trim($response['choices'][0]['message']['content']);

// 		return response()->json(['question' => $question, 'answer' => $answer]);

// 	}


}
