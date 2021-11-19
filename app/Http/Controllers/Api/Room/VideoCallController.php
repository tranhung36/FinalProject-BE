<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;

class VideoCallController extends Controller
{
    public function generate_token(Request $request, $id)
    {
        // Substitute your Twilio Account SID and API Key details
        $accountSid = env('TWILIO_ACCOUNT_SID');
        $apiKeySid = env('TWILIO_API_KEY');
        $apiKeySecret = env('TWILIO_API_SECRET');

        $room = Room::find($id);
        $identity = uniqid();
        $user = $request->user();
        $user_name = $user->first_name . ' ' .  $user->last_name;
        $room_name = $room->title;

        // Create an Access Token
        $token = new AccessToken(
            $accountSid,
            $apiKeySid,
            $apiKeySecret,
            3600,
            $identity,
        );

        // Grant access to Video
        $grant = new VideoGrant();
        $grant->setRoom($room_name);
        $token->addGrant($grant);

        // Serialize the token as a JWT
        $result = [
            "user_name" => $user_name,
            "room_name" => $room_name,
            "token" => $token->toJWT()
        ];

        return $this->sendResponse($result, 'Successfully');
    }
}
