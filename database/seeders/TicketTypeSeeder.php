<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketType;

class TicketTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Reguler',
            'VIP',
            'Early Bird',
        ];

        foreach ($types as $type) {
            TicketType::firstOrCreate([
                'nama' => $type
            ]);
        }
    }
}
