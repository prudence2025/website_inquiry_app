<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RequirementType;

class RequirementTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'AODD Pumps',
            'Alum Powder',
            'Caustic Soda',
            'Chlorine',
            'Dishwasher Pumps',
            'Dosing Pumps',
            'Drum Pumps',
            'Filter Cloths',
            'Filter Press',
            'High-pressure Pumps',
            'Hydrated Lime',
            'Laundry Pumps',
            'Liquid Filter Bags',
            'Magnetic Pumps',
            'PAC',
            'Peristaltic Pumps',
            'Polymer',
            'Pool Acid',
            'RO Plants',
            'STP',
            'Screw Pumps',
            'Soda Ash',
            'Submersible Pumps',
            'Swimming Pools',
            'Wastewater Treatment Plants',
            'Others',
        ];

        foreach ($types as $type) {
            RequirementType::firstOrCreate(['name' => $type]);
        }
    }
}
