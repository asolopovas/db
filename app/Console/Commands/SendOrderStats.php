<?php

namespace App\Console\Commands;

use App\Helpers\MailerSetup;
use App\Helpers\OrdersHelper;
use App\Mail\StatsMail;
use Illuminate\Console\Command;

class SendOrderStats extends Command
{
  use MailerSetup;
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'email:stats';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Email Order Data';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   * @throws \Exception
   */
  public function handle()
  {
    $mailer = $this->internalMailer();
    $ordersHelper = new OrdersHelper;
    $ordersHelper->makeStats(true);
    $mailer->send(new StatsMail);
  }
}
