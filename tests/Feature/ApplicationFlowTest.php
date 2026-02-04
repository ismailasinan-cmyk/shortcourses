<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ShortCourse;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ApplicationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_application_flow()
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $course = ShortCourse::factory()->create(['fee' => 50000]);

        // 1. Visit Application Form
        $response = $this->actingAs($user)->get(route('apply'));
        $response->assertStatus(200);

        // 2. Submit Application
        $file = UploadedFile::fake()->image('ssce.jpg');
        $data = [
            'surname' => 'Doe',
            'first_name' => 'Jane',
            'other_name' => 'Rose',
            'email' => 'jane@example.com',
            'phone' => '08000000000',
            'gender' => 'Female',
            'date_of_birth' => '2000-01-01',
            'address' => 'Test Address',
            'state' => 'Lagos',
            'lga' => 'Ikeja',
            'ssce_type' => 'WAEC',
            'ssce_year' => '2018',
            'ssce_exam_number' => '1234567890',
            'ssce_file' => $file,
            'category' => $course->category,
            'short_course_id' => $course->id,
            'declaration' => 'on',
        ];

        $response = $this->actingAs($user)->post(route('apply.store'), $data);
        $response->assertRedirect();
        
        $application = Application::first();
        $this->assertNotNull($application);
        $this->assertEquals('PENDING', $application->payment_status);
        
        $response->assertRedirect(route('applications.review', $application->application_ref));

        // 3. Payment Initialization and Verification Mocks
        Http::fake([
            'remitademo.net/*' => Http::sequence()
                                    ->push(['RRR' => '123456789012'], 200)
                                    ->push(['status' => '00', 'message' => 'Approved'], 200),
        ]);

        $response = $this->post(route('payments.remita.init'), [
            'application_ref' => $application->application_ref
        ]);
        
        $response->assertStatus(200); // Redirect view
        $this->assertDatabaseHas('payments', [
            'application_id' => $application->id,
            'remita_rrr' => '123456789012',
            'status' => 'PENDING'
        ]);

        // 4. Payment Callback
        $response = $this->post(route('payments.remita.callback'), [
            'RRR' => '123456789012'
        ]);

        $response->assertRedirect(route('applications.review', $application->application_ref));
        
        $application->refresh();
        $this->assertEquals('PAID', $application->payment_status);
    }

    public function test_admin_can_view_applications()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $application = Application::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.applications.index'));
        $response->assertStatus(200);
        $response->assertSee($application->surname);
    }
}
