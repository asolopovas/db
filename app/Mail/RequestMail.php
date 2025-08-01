<?php

namespace App\Mail;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestMail extends Mailable
{
    use Queueable, SerializesModels;

    private $message;
    private $order;
    private $requestType;

    /**
     * Create a new message instance.
     *
     * @param $orderData
     */
    public function __construct($orderData, $requestType)
    {
        $this->order = $orderData;
        $this->requestType = $requestType;
        $this->message = Setting::where('name', "request_{$requestType}")->first()->value;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $markdown = $this->markdown('emails.request', [
            'message' => $this->message,
            'order'   => $this->order,
            'user' => $this->order['user']
        ]);

        $markdown->from('info@3oak.co.uk', '3 Oak');
        $markdown->subject = ucfirst($this->requestType)." Request";

        return $markdown;
    }
}
