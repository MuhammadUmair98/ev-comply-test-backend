<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\NewsletterRecipient;
use Illuminate\Http\Request;

class NewsletterRecipientController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:newsletter_recipients,email',
        ]);
        $recipients = new NewsletterRecipient();
        $recipients->email = $request->email;
        $save = $recipients->save();
        if ($save) {
            dispatch(new SendEmailJob($recipients->email));
            return [
                "response-code" => 200,
                "body" => [
                    "message" => "Record updated successfully.",
                    "data" => $recipients,
                ]
            ];
        }
        return [
            "response-code" => 422,
            "body" => [
                "message" => "An Error has occured.",

            ]
        ];
    }
}
