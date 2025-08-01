<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'name'       => 'Woodland Flooring Ltd',
            'address'    => '
                143 Woodland Drive<br>
                Watford, England<br>
                WD17 3DA
            ',
            'telephone1' => '+44-754-049-2060',
            'telephone2' => '',
            'web'        => 'https://woodlandflooring.co.uk',
            'email'      => 'info@woodlandflooring.co.uk',
            'vat_number' => '',
            'bank'       => '',
            'sort_code'  => '',
            'account_nr' => '',
            'disclaimer' => 'Disclaimer: This electronic message contains information from “Woodland Flooring Ltd”. which may be privileged or confidential and is intended solely for the use of the intended recipient.  If you are not the intended recipient be aware that any disclosure, copying, distribution, use of or reliance on the contents of this information is prohibited. If you have received this electronic message in error, please notify us by telephone or email listed above this message immediately. Please note that any views or opinions presented in this email are solely those of the author and do not necessarily represent those of “Woodland Flooring Ltd”.
All reasonable precautions have been taken to ensure that no viruses are present in this e-mail. Woodland Flooring Ltd. cannot accept any responsibility for loss or damage arising from the use of this e-mail or the attachments and recommend that you subject these to virus checking procedures prior to use.
Woodland Flooring Ltd, Registered Office: 143 Woodland Drive, Watford, England, WD17 3DA Registered in England No: 15541971
',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
