<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = Badge::badgesDefecto();
        
        foreach ($badges as $badgeData) {
            Badge::updateOrCreate(
                ['nombre' => $badgeData['nombre']],
                $badgeData
            );
        }
    }
}
