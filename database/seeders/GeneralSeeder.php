<?php

namespace Database\Seeders;

use App\Models\General;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $general = [
            [
                'kode_id'=>'LOCATION',
                'kode_desc_id'=>'LATITUDE',
                'desc'=>'LATITUDE',
                'value'=>'-6.3723356'
            ],
            [
                'kode_id'=>'LOCATION',
                'kode_desc_id'=>'LONGTITUDE',
                'desc'=>'LONGTITUDE',
                'value'=>'106.7126704'
            ],

            [
                'kode_id'=>'POINT',
                'kode_desc_id'=>'TAK',
                'desc'=>'TIDAK ABSENS KELAS',
                'value'=>'1'
            ],

            [
                'kode_id'=>'POINT',
                'kode_desc_id'=>'TLT',
                'desc'=>'TELAT',
                'value'=>'3'
            ],

            [
                'kode_id'=>'POINT',
                'kode_desc_id'=>'OTH',
                'desc'=>'LAINNYA',
                'value'=>'3'
            ],
        ];

        foreach ($general as $key => $value) {
            General::create($value);
        }
    }
}
