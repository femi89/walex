<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Position;
use App\Models\Employee;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PositionEmployeesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_position_employees()
    {
        $position = Position::factory()->create();
        $employees = Employee::factory()
            ->count(2)
            ->create([
                'position_id' => $position->id,
            ]);

        $response = $this->getJson(
            route('api.positions.employees.index', $position)
        );

        $response->assertOk()->assertSee($employees[0]->first_name);
    }

    /**
     * @test
     */
    public function it_stores_the_position_employees()
    {
        $position = Position::factory()->create();
        $data = Employee::factory()
            ->make([
                'position_id' => $position->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.positions.employees.store', $position),
            $data
        );

        $this->assertDatabaseHas('employees', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $employee = Employee::latest('id')->first();

        $this->assertEquals($position->id, $employee->position_id);
    }
}
