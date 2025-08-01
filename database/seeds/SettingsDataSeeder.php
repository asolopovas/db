<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SettingsDataSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
    public function run()
    {

        DB::table('settings')->insert([
        'name'       => 'terms_and_conditions',
        'value'      => 'docs/Terms and Conditions.pdf',
        'type'       => 'file',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
        ]);

        DB::table('settings')->insert([
        'name'       => 'information_pack',
        'value'      => 'docs/Information Pack.pdf',
        'type'       => 'file',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
        ]);

        DB::table('settings')->insert([
        'name'       => 'quotation_message',
        'value'      => '<p>Thank you for your enquiry. </p><p>As per your request please see attached your quotation to do the works. </p><p>Lead time is 4 to 5 weeks. </p><p>Payment terms can be seen on the quotation. </p><p>If you have any questions of queries, please do not hesitate to get in contact.</p>',
        'type'       => 'text',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
        ]);
        DB::table('settings')->insert([
        'name'       => 'invoice_message',
        'value'      => '<p>Thank you for your order.</p><p>As per your request please find attached your Invoice. </p><p>Lead time is 4 to 5 weeks unless otherwise stated. </p><p>Payment terms can be seen on the Invoice attached. </p><p>If you could kindly confirm once the payment has been made that would be most appreciated. </p><p>If you have any further questions or queries, please do not hesitate to get in contact.</p>',
        'type'       => 'text',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
        ]);

        DB::table('settings')->insert([
        'name'  => 'algolia_app_id',
        'value' => env('ALGOLIA_APP_ID'),
        ]);

        DB::table('settings')->insert([
        'name'  => 'algolia_secret',
        'value' => env('ALGOLIA_SEARCH_ONLY'),
        ]);
    }
}
