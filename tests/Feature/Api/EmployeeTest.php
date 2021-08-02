<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Employee;

use App\Models\Position;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeTest extends TestCase
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
    public function it_gets_employees_list()
    {
        $employees = Employee::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.employees.index'));

        $response->assertOk()->assertSee($employees[0]->first_name);
    }

    /**
     * @test
     */
    public function it_stores_the_employee()
    {
        $data = Employee::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.employees.store'), $data);

        $this->assertDatabaseHas('employees', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_employee()
    {
        $employee = Employee::factory()->create();

        $position = Position::factory()->create();
        $user = User::factory()->create();

        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'gender' => 'male',
            'position_id' => $position->id,
            'user_id' => $user->id,
        ];

        $response = $this->putJson(
            route('api.employees.update', $employee),
            $data
        );

        $data['id'] = $employee->id;

        $this->assertDatabaseHas('employees', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->deleteJson(
            route('api.employees.destroy', $employee)
        );

        $this->assertSoftDeleted($employee);

        $response->assertNoContent();
    }
}
