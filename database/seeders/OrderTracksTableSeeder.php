<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models as Database;

class OrderTracksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('order_track')->truncate();

        $tracks = ['In delivering', 'In preparing', 'Completed'];

        foreach ($tracks as $track) {
            Database\OrderTrack::create([
                'status' => $track
            ]);
        }
    }
}
