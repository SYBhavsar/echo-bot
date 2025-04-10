<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessengerController extends Controller
{
    // 1️⃣ Facebook Verification (GET Request)
    public function verifyWebhook(Request $request)
    {
        $verifyToken = env('FACEBOOK_VERIFY_TOKEN');

        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode === 'subscribe' && $token === $verifyToken) {
            return response($challenge);
        } else {
            return response('Forbidden', 403);
        }
    }

    // 2️⃣ Handle Incoming Messages (POST Request)
    public function handleMessage(Request $request)
    {
        Log::info('Messenger Webhook Event:', $request->all());

        // Get the incoming message data
        $data = $request->all();

            $senderId = $data['entry'][0]['messaging'][0]['sender']['id'];
            $messageText = $data['entry'][0]['messaging'][0]['message']['text'];

            // Send echo response
            $this->sendMessage($senderId, $messageText);


        return response()->json(['status' => 'ok']);
    }

    private function sendMessage($recipientId, $messageText)
    {
        $accessToken = env('PAGE_ACCESS_TOKEN');
        $url = "https://graph.facebook.com/v22.0/546581708547909/messages?access_token=$accessToken";

        $response = Http::post($url, [
            'recipient' => ['id' => $recipientId],
            'message' => ['text' => $messageText]
        ]);

        Log::info('Messenger API Response:', $response->json());
    }
}
