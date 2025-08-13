<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StatsMail extends Mailable implements ShouldQueue
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public $message;

  public function __construct()
  {
    $month = (new Carbon('first day of last month'))->format('M-Y');

    $this->message = "Please see attached the statistics for the month of $month";
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $markdown = $this->markdown('emails.internal-email', ['message' => $this->message]);
    $markdown->from('info@3oak.co.uk', '3 Oak Stats');
    $markdown->subject('3 Oak Monthly Statistic');
    $markdown->attachFromStorage('xlsx/commissions-data.xlsx');
    $markdown->attachFromStorage('xlsx/services-data.xlsx');
    $markdown->attachFromStorage('xlsx/materials-data.xlsx');
    $markdown->attachFromStorage('xlsx/products-data.xlsx');

    return $markdown;
  }
}
