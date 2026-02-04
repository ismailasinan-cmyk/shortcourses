<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShortCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            // CISCO Category
            [
                'category' => 'CISCO',
                'course_name' => 'IT Essentials',
                'code' => 'CIS-ITE',
                'fee' => 30000.00,
                'duration' => '3 Months',
                'description' => 'Fundamental computer skills and hardware knowledge.',
                'status' => true,
            ],
            [
                'category' => 'CISCO',
                'course_name' => 'Introduction to IOT',
                'code' => 'CIS-IOT',
                'fee' => 35000.00,
                'duration' => '3 Months',
                'description' => 'Basics of Internet of Things.',
                'status' => true,
            ],
            [
                'category' => 'CISCO',
                'course_name' => 'CCNA I',
                'code' => 'CIS-CCNA1',
                'fee' => 50000.00,
                'duration' => '3 Months',
                'description' => 'Introduction to Networks.',
                'status' => true,
            ],
            [
                'category' => 'CISCO',
                'course_name' => 'CCNA II',
                'code' => 'CIS-CCNA2',
                'fee' => 50000.00,
                'duration' => '3 Months',
                'description' => 'Switching, Routing, and Wireless Essentials.',
                'status' => true,
            ],
            [
                'category' => 'CISCO',
                'course_name' => 'CCNA III',
                'code' => 'CIS-CCNA3',
                'fee' => 50000.00,
                'duration' => '3 Months',
                'description' => 'Enterprise Networking, Security, and Automation.',
                'status' => true,
            ],

            // ACETEL Category
            [
                'category' => 'ACETEL',
                'course_name' => 'Machine Learning',
                'code' => 'ACE-ML',
                'fee' => 70000.00,
                'duration' => '6 Months',
                'description' => 'Introduction to Machine Learning concepts.',
                'status' => true,
            ],
            [
                'category' => 'ACETEL',
                'course_name' => 'Cybersecurity',
                'code' => 'ACE-SEC',
                'fee' => 60000.00,
                'duration' => '4 Months',
                'description' => 'Fundamentals of Cybersecurity.',
                'status' => true,
            ],
            [
                'category' => 'ACETEL',
                'course_name' => 'Digital Literacy',
                'code' => 'ACE-DL',
                'fee' => 20000.00,
                'duration' => '2 Months',
                'description' => 'Basic digital skills for the modern world.',
                'status' => true,
            ],
        ];

        foreach ($courses as $course) {
            \App\Models\ShortCourse::create($course);
        }
    }
}
