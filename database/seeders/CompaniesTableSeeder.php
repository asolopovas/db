<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CompaniesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('companies')->insert([
            'name'       => '3 Oak',
            'address'    => '
                219 Northfield Avenue<br>
                Ealing, London<br>
                W13 9QU
            ',
            'telephone1' => '+44-208-840-8031',
            'telephone2' => '',
            'web'        => 'https://3oak.co.uk',
            'email'      => 'info@3oak.co.uk',
            'vat_number' => '',
            'bank'       => '',
            'sort_code'  => '',
            'account_nr' => '',
            'disclaimer' => 'Disclaimer: This electronic message contains information from "3 Oak Wood Flooring Tm Ltd". which may be privileged or confidential and is intended solely for the use of the intended recipient.  If you are not the intended recipient be aware that any disclosure, copying, distribution, use of or reliance on the contents of this information is prohibited. If you have received this electronic message in error, please notify us by telephone or email listed above this message immediately. Please note that any views or opinions presented in this email are solely those of the author and do not necessarily represent those of "3 Oak Flooring Ltd".
All reasonable precautions have been taken to ensure that no viruses are present in this e-mail. 3 Oak Wood Flooring Ltd. cannot accept any responsibility for loss or damage arising from the use of this e-mail or the attachments and recommend that you subject these to virus checking procedures prior to use.
3 Oak Wood Flooring Ltd, Registered Office: 219 Northfield Avenue, London, England, W13 9QU Registered in England No: 09691723
',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}