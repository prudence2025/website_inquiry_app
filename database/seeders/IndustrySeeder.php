<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Industry; 

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $industries = [
            ['name' => 'Agro-based Industries'],
            ['name' => 'Bottled Drinking Water Companies'],
            ['name' => 'Breweries & Distilleries'],
            ['name' => 'Cement & Construction Materials'],
            ['name' => 'Chemical & Petrochemical Industry'],
            ['name' => 'Cosmetic & Personal Care Manufacturing'],
            ['name' => 'Dairy Processing Plants'],
            ['name' => 'Desalination Plants'],
            ['name' => 'Educational Institutions (Universities, Schools with labs)'],
            ['name' => 'Fertilizer & Agrochemicals'],
            ['name' => 'Fisheries'],
            ['name' => 'Food & Beverage Manufacturing'],
            ['name' => 'Hospitals & Healthcare Facilities'],
            ['name' => 'Hotels & Resorts'],
            ['name' => 'Housing & Apartment Complexes'],
            ['name' => 'Laundry & Dry-Cleaning Services'],
            ['name' => 'Leather & Tannery Industry'],
            ['name' => 'Mining & Mineral Processing'],
            ['name' => 'Municipal Water Supply Boards'],
            ['name' => 'Oil & Gas Refineries'],
            ['name' => 'Paints & Coatings Manufacturing'],
            ['name' => 'Paper & Pulp Industry'],
            ['name' => 'Pharmaceutical & Biotech Plants'],
            ['name' => 'Power Plants (thermal & nuclear)'],
            ['name' => 'Restaurants & Catering Services'],
            ['name' => 'Shopping Malls & Complexes'],
            ['name' => 'Textile & Dyeing Industry'],
            ['name' => 'Other'],
        ];

        Industry::insert($industries);
    }
}
