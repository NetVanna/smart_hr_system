<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Holiday;

class CambodianHolidaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holidays = [
            ['name' => 'Victory Over Genocide Day', 'name_kh' => 'ទិវាជ័យជម្នះលើរបបប្រល័យពូជសាសន៍', 'date' => '2025-01-07', 'description' => 'Commemorating the end of the Khmer Rouge regime.', 'description_kh' => 'រំលឹកខួបនៃការបញ្ចប់របបខ្មែរក្រហម'],
            ['name' => 'International Women\'s Day', 'name_kh' => 'ទិវាអន្តរជាតិនារី', 'date' => '2025-03-08', 'description' => 'Global day celebrating the social, economic, cultural, and political achievements of women.', 'description_kh' => 'ទិវាពិភពលោកអបអរសាទរសមិទ្ធផលសង្គម សេដ្ឋកិច្ច វប្បធម៌ និងនយោបាយរបស់ស្ត្រី'],
            ['name' => 'Khmer New Year', 'name_kh' => 'បុណ្យចូលឆ្នាំថ្មី ប្រពៃណីជាតិ', 'date' => '2025-04-14', 'description' => 'Day 1 of the traditional solar new year.', 'description_kh' => 'ថ្ងៃទី១ នៃបុណ្យចូលឆ្នាំថ្មី'],
            ['name' => 'Khmer New Year', 'name_kh' => 'បុណ្យចូលឆ្នាំថ្មី ប្រពៃណីជាតិ', 'date' => '2025-04-15', 'description' => 'Day 2 of the traditional solar new year.', 'description_kh' => 'ថ្ងៃទី២ នៃបុណ្យចូលឆ្នាំថ្មី'],
            ['name' => 'Khmer New Year', 'name_kh' => 'បុណ្យចូលឆ្នាំថ្មី ប្រពៃណីជាតិ', 'date' => '2025-04-16', 'description' => 'Day 3 of the traditional solar new year.', 'description_kh' => 'ថ្ងៃទី៣ នៃបុណ្យចូលឆ្នាំថ្មី'],
            ['name' => 'Visak Bochea Day', 'name_kh' => 'ពិធីបុណ្យវិសាខបូជា', 'date' => '2025-05-12', 'description' => 'Celebrating the birth, enlightenment, and nirvana of Buddha.', 'description_kh' => 'ការប្រារព្ធពិធីបុណ្យរំលឹកដល់ថ្ងៃចាកសិក្ខាបទ ការត្រាស់ដឹង និងការចូលបរិនិព្វានរបស់ព្រះពុទ្ធ'],
            ['name' => 'Royal Ploughing Ceremony', 'name_kh' => 'ព្រះរាជពិធីច្រត់ព្រះនង្គ័ល', 'date' => '2025-05-16', 'description' => 'Marking the beginning of the rice planting season.', 'description_kh' => 'ការកត់សម្គាល់ការចាប់ផ្តើមនៃរដូវដាំដុះស្រូវ'],
            ['name' => 'King Norodom Sihamoni\'s Birthday', 'name_kh' => 'ព្រះរាជពិធីបុណ្យចម្រើនព្រះជន្ម ព្រះបាទសម្តេចព្រះបរមនាថ នរោត្តម សីហមុនី', 'date' => '2025-05-14', 'description' => 'Birthday of His Majesty King Norodom Sihamoni.', 'description_kh' => 'ព្រះរាជពិធីបុណ្យចម្រើនព្រះជន្មរបស់ព្រះមហាក្សត្រ'],
            ['name' => 'Pchum Ben Day', 'name_kh' => 'ពិធីបុណ្យភ្ជុំបិណ្ឌ', 'date' => '2025-09-21', 'description' => 'Day 1 of the traditional ancestor festival.', 'description_kh' => 'ថ្ងៃទី១ នៃពិធីបុណ្យភ្ជុំបិណ្ឌ'],
            ['name' => 'Pchum Ben Day', 'name_kh' => 'ពិធីបុណ្យភ្ជុំបិណ្ឌ', 'date' => '2025-09-22', 'description' => 'Day 2 of the traditional ancestor festival.', 'description_kh' => 'ថ្ងៃទី២ នៃពិធីបុណ្យភ្ជុំបិណ្ឌ'],
            ['name' => 'Pchum Ben Day', 'name_kh' => 'ពិធីបុណ្យភ្ជុំបិណ្ឌ', 'date' => '2025-09-23', 'description' => 'Day 3 of the traditional ancestor festival.', 'description_kh' => 'ថ្ងៃទី៣ នៃពិធីបុណ្យភ្ជុំបិណ្ឌ'],
            ['name' => 'Constitution Day', 'name_kh' => 'ទិវាប្រកាសរដ្ឋធម្មនុញ្ញ', 'date' => '2025-09-24', 'description' => 'Commemorating the adoption of the 1993 constitution.', 'description_kh' => 'ការរំលឹកខួបនៃការអនុម័តរដ្ឋធម្មនុញ្ញឆ្នាំ១៩៩៣'],
            ['name' => 'Independence Day', 'name_kh' => 'ពិធីបុណ្យឯករាជ្យជាតិ', 'date' => '2025-11-09', 'description' => 'Celebrating Cambodia\'s independence from France in 1953.', 'description_kh' => 'ការប្រារព្ធពិធីបុណ្យឯករាជ្យជាតិរបស់កម្ពុជាពីប្រទេសបារាំងក្នុងឆ្នាំ១៩៥៣'],
            ['name' => 'Water Festival', 'name_kh' => 'ព្រះរាជពិធីបុណ្យអុំទូក បណ្តែតប្រទីប និងសំពះព្រះខែ អកអំបុក', 'date' => '2025-11-04', 'description' => 'Day 1 of the traditional boat racing festival.', 'description_kh' => 'ថ្ងៃទី១ នៃព្រះរាជពិធីបុណ្យអុំទូក'],
            ['name' => 'Water Festival', 'name_kh' => 'ព្រះរាជពិធីបុណ្យអុំទូក បណ្តែតប្រទីប និងសំពះព្រះខែ អកអំបុក', 'date' => '2025-11-05', 'description' => 'Day 2 of the traditional boat racing festival.', 'description_kh' => 'ថ្ងៃទី២ នៃព្រះរាជពិធីបុណ្យអុំទូក'],
            ['name' => 'Water Festival', 'name_kh' => 'ព្រះរាជពិធីបុណ្យអុំទូក បណ្តែតប្រទីប និងសំពះព្រះខែ អកអំបុក', 'date' => '2025-11-06', 'description' => 'Day 3 of the traditional boat racing festival.', 'description_kh' => 'ថ្ងៃទី៣ នៃព្រះរាជពិធីបុណ្យអុំទូក'],
        ];

        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(
                ['name' => $holiday['name'], 'date' => $holiday['date']],
                [
                    'name_kh' => $holiday['name_kh'] ?? null,
                    'description' => $holiday['description'],
                    'description_kh' => $holiday['description_kh'] ?? null,
                ]
            );
        }
    }
}
