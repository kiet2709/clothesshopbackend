<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models as Database;

class SizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('sizes')->truncate();

        $sizes = ['M', 'L', 'XL', 'XXL', 'XXXL'];

        foreach ($sizes as $size) {
            Database\Size::create([
                'name' => $size
            ]);
        }

        Schema::enableForeignKeyConstraints();
    }
}
