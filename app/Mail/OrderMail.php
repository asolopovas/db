<?php

namespace App\Mail;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $orderData;
    public $orderPDF;
    public $logoCID;

    /**
     * Create a new message instance.
     *
     * @param array|null $orderData
     * @param string|null $orderPDF
     */
    public function __construct($orderData = null, $orderPDF = null)
    {
        $this->orderData = $orderData;
        $this->orderPDF = $orderPDF;
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        $subject = $this->orderData['status'] ?? 'Order Mail';

        return new Envelope(
            from: new Address('info@3oak.co.uk', '3 Oak'),
            to: $this->orderData['order']->customer->email1,
            subject: $subject
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        $setting = Setting::all()->keyBy('name');
        $orderStatus = $this->orderData['status'] ?? null;

        $message = $orderStatus === 'Invoice'
            ? $setting['invoice_message']['value']
            : $setting['quotation_message']['value'];

        return new Content(
            markdown: 'emails.order-mail',
            with: [
                'user' => auth()->user(),
                'order' => $this->orderData,
                'message' => $message,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments()
    {
        $attachments = [];

        // Attach files from storage
        $files = array_filter(Storage::files('docs'), function ($file) {
            return strpos($file, 'pdf') !== false;
        });

        foreach ($files as $file) {
            $attachments[] = Attachment::fromStorage($file);
        }

        // Attach Order PDF
        if ($this->orderData && $this->orderData['status'] === 'Invoice') {
            $orderFilename = $this->orderData['proforma']
                ? "Proforma Invoice #{$this->orderData['id']}.pdf"
                : "Invoice #{$this->orderData['id']}.pdf";
        } else {
            $orderFilename = "{$this->orderData['status']}-{$this->orderData['id']}.pdf";
        }

        $attachments[] = Attachment::fromData(fn () => $this->orderPDF, $orderFilename)
            ->withMime('application/pdf');

        return $attachments;
    }
}
