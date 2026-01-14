<?php

namespace Tests\Feature\Api\V1;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_get_list_of_tasks(): void
    {
        // Arrange
        $tasks = Task::factory()->count(2)->create();
        //Act
        $response = $this->getJson("/api/v1/tasks");
        // Assert: status é 200 OK e tem 2 itens
        $response->assertOk();
        $response->assertJsonCount(2, "data");
        $response->assertJsonStructure([
            "data" => [
                ["id", "name", "is_completed"]
            ]
        ]);
    }

    public function test_guest_can_get_single_task(): void
    {
        // Arrange: criando a task
        $task = Task::factory()->create();

        // Act: Fazendo a request para o endpoint com o id

        $response = $this->getJson("/api/v1/tasks/" . $task->id);

        // Assert: Response contem os dados correto da task

        $response->assertOk();
        $response->assertJsonStructure([
            "data" => ["id", "name", "is_completed"]
        ]);

        $response->assertJson([
            "data" => [
                "id" => $task->id,
                "name" => $task->name,
                "is_completed" => $task->is_completed,
            ]
        ]);
    }

    // `POST /tasks` -> create a new task
    public function test_guest_can_create_a_task(): void
    {
        $response = $this->postJson("/api/v1/tasks", [
            "name" => "Test task",
        ]);

        $response->assertCreated();
        $response->assertJsonStructure(["data" => ["id", "name", "is_completed"]]);

        $this->assertDatabaseHas("tasks", ["name" => "Test task"]);
    }
    // `PUT /tasks/{id}` -> update existing task

    public function test_guest_can_update_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->putJson("/api/v1/tasks/". $task->id, ["name" => "Test task2"]);
        $response->assertOk();

    }
    public function test_guest_cannot_update_task_with_invalid_data(): void
    {
        $task = Task::factory()->create();

        $response = $this->putJson("/api/v1/tasks/". $task->id, ["name" => ""]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(["name"]);
    }
    // `PATCH /tasks/{id}/complete` -> mark the task as completed or incomplete

    public function test_guest_can_toggle_task_completion(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(["user_id" => $user->id]);

        $this->actingAs($user);

        $response = $this->patchJson("/api/v1/tasks/{$task->id}/complete", ["is_completed" => true]);

        $response->assertOk();
        $response->assertJsonFragment(["is_completed" => true]);
    }

    public function test_guest_cannot_toggle_completed_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(["user_id" => $user->id]);

        $this->actingAs($user);

        $response = $this->patchJson("/api/v1/tasks/{$task->id}/complete", ["is_completed" => "yes"]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(["is_completed"]);
    }

    // `DELETE /tasks/{id}` -> delete a task

    public function test_guest_can_delete_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/v1/tasks/".$task->id);
        $response->assertNoContent();
    }


}
