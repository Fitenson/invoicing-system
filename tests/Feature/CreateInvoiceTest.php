<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Modules\User\Model\User;
use App\Modules\Project\Model\Project;
use App\Modules\Invoice\Model\Invoice;
use Tests\TestCase;

class CreateInvoiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_invoice_can_be_created()
    {
        $this->withoutMiddleware();

        // Create a client for invoice
        $user = User::factory()->create([
            'name' => 'user',
            'email' => 'test@example.com',
            'password' => Hash::make('88888888'),
        ]);

        $project = Project::factory()->create([
            'name'  => 'Project001',
            'description'=> 'Hello World',
            'rate_per_hour' => '22.5',
            'total_hours'=> '200'
        ]);

        Log::info($project);

        $data = [
            'invoice' => [
                'client' => $user->id,
                'description' => 'Hello World',
            ],
            'invoice_has_projects' => [
                [
                    'project' => $project->id
                ]
            ]
        ];


        // Call the API
        $response = $this->post('/invoice/store', $data);

        // Assert redirection or success response
        $response->assertStatus(302); // or 201/200 if JSON response

        // Assert invoice is stored in DB
        // Get the last inserted invoice
        $invoice = Invoice::latest()->first();

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'client' => $user->id,
            'description' => 'Hello World',
        ]);

        $this->assertDatabaseHas('invoice_has_projects', [
            'invoice' => $invoice->id,
            'project' => $project->id,
        ]);
    }


    public function test_invoice_can_be_created_with_empty_data()
    {
        $this->withoutMiddleware();

        $data = [
            'invoice' => [],
            'invoice_has_projects' => []
        ];

        // Call the API
        $response = $this->post('/invoice/store', $data);

        // Assert redirection or success response
        $response->assertStatus(302);

        // Get the last inserted invoice
        $invoice = Invoice::latest()->first();

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
        ]);
    }
}
