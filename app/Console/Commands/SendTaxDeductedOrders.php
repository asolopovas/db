<?php

namespace App\Console\Commands;

use App\Helpers\MailerSetup;
use App\Helpers\OrdersHelper;
use App\Mail\OrdersWithTaxDeductionsMail;
use App\Models\Order;
use Illuminate\Console\Command;

class SendTaxDeductedOrders extends Command
{
  use MailerSetup;
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'email:tax-deducted-orders';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Send tax deducted orders';

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

    // Send Tax Deducted Orders
    $taxDeductedOrders = Order::TaxDeducted(true)->get();
    if ( $taxDeductedOrders->isNotEmpty() ) {
      $ordersHelper->genZip($taxDeductedOrders);

      $mailer->send(new OrdersWithTaxDeductionsMail);
    }
  }
}
