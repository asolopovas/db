<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrdersWithTaxDeductionsMail extends Mailable implements ShouldQueue
{
  use Queueable, SerializesModels;

  private $message;

  /**
   * Create a new message instance.
   *
   */
  public function __construct()
  {
    $this->message = "Please find attached Orders Invoices with Tax Deductions.";
  }

  /**
   * Build the message.
   *
   * @return $this
   * @throws \Exception
   */
  public function build()
  {
    $markdown = $this->markdown('emails.internal-email', ['message' => $this->message]);
    $markdown->from('info@3oak.co.uk', '3 Oak DB');
    $markdown->subject('3 Oak Tax Deducted Orders');

    $markdown->attachFromStorage('zip/orders.zip');

    return $markdown;
  }
}
