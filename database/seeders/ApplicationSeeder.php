<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\ShortCourse;
use App\Models\Payment;
use Illuminate\Support\Str;

class ApplicationSeeder extends Seeder
{
    public function run()
    {
        $courses = ShortCourse::all();

        if ($courses->isEmpty()) {
            return;
        }

        // Create 20 dummy applications
        for ($i = 0; $i < 20; $i++) {
            $course = $courses->random();
            $year = date('Y');
            $ref = 'ACETEL-SC-' . $year . '-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT);
            
            $status = collect(['PENDING', 'PAID', 'FAILED'])->random();
            $createdAt = now()->subDays(rand(0, 30));

            $app = Application::create([
                'application_ref' => $ref,
                'surname' => 'Doe' . $i,
                'first_name' => 'John',
                'other_name' => 'A.',
                'email' => 'user' . $i . '@example.com',
                'phone' => '080123456' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'gender' => rand(0, 1) ? 'Male' : 'Female',
                'date_of_birth' => '1995-01-01',
                'address' => '123 Lagos Street',
                'state' => 'Lagos',
                'lga' => 'Ikeja',
                'ssce_type' => 'WAEC',
                'ssce_year' => '2012',
                'ssce_exam_number' => '12345678AB',
                'ssce_file_path' => 'dummy.pdf',
                'short_course_id' => $course->id,
                'amount' => $course->fee,
                'payment_status' => $status,
                'locale' => 'en',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            if ($status == 'PAID') {
                Payment::create([
                    'application_id' => $app->id,
                    'remita_rrr' => Str::upper(Str::random(12)),
                    'amount' => $app->amount,
                    'status' => 'SUCCESS',
                    'channel' => 'REMITA',
                    'response_payload' => json_encode(['status' => '00', 'message' => 'Approved']),
                    'paid_at' => $createdAt->addMinutes(10),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }
}
