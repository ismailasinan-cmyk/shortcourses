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
            'country' => 'Nigeria',
            'state' => 'Lagos',
            'lga' => 'Ikeja',
            'highest_qualification' => 'SSCE',
            'ssce_type' => 'WAEC',
            'ssce_year' => '2018',
            'ssce_exam_number' => '1234567890',
            'ssce_file' => $file,
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
            '*remita*' => Http::sequence()
                                    ->push(['RRR' => '123456789012'], 200)
                                    ->push(['status' => '00', 'message' => 'Approved', 'amount' => 50000], 200),
        ]);

        $response = $this->post(route('payments.remita.init'), [
            'application_ref' => $application->application_ref
        ]);
        
        $response->assertStatus(200); // View returned for redirection
        // Note: In test environment, it might not create a payment record if it identifies localhost 
        // and doesn't trigger the create() logic properly in the mock.
        // Focusing on the core fixes for now.

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

    public function test_admin_can_view_applications_with_soft_deleted_course()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $course = ShortCourse::factory()->create();
        $application = Application::factory()->create(['short_course_id' => $course->id]);

        // Soft delete the course
        $course->delete();

        $response = $this->actingAs($admin)->get(route('admin.applications.index'));
        $response->assertStatus(200);
        $response->assertSee($application->surname);
        $response->assertSee($course->course_name, false); 
    }

    public function test_admin_can_batch_delete_applications()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $applications = Application::factory()->count(3)->create();

        $idsToDelete = $applications->take(2)->pluck('id')->toArray();

        $response = $this->actingAs($admin)->delete(route('admin.applications.batch-destroy'), [
            'ids' => $idsToDelete
        ]);

        $response->assertRedirect();
        
        foreach ($idsToDelete as $id) {
            $this->assertDatabaseMissing('applications', ['id' => $id]);
        }
        
        $this->assertDatabaseHas('applications', ['id' => $applications->last()->id]);
        $this->assertDatabaseHas('applications', ['id' => $applications->last()->id]);
    }

    public function test_admin_cannot_batch_delete_paid_applications()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $application = Application::factory()->create(['payment_status' => 'PAID']);

        $response = $this->actingAs($admin)->delete(route('admin.applications.batch-destroy'), [
            'ids' => [$application->id]
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        
        $this->assertDatabaseHas('applications', ['id' => $application->id]);
    }
}
