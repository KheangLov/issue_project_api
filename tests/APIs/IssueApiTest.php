<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Issue;

class IssueApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_issue()
    {
        $issue = Issue::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/issues', $issue
        );

        $this->assertApiResponse($issue);
    }

    /**
     * @test
     */
    public function test_read_issue()
    {
        $issue = Issue::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/issues/'.$issue->id
        );

        $this->assertApiResponse($issue->toArray());
    }

    /**
     * @test
     */
    public function test_update_issue()
    {
        $issue = Issue::factory()->create();
        $editedIssue = Issue::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/issues/'.$issue->id,
            $editedIssue
        );

        $this->assertApiResponse($editedIssue);
    }

    /**
     * @test
     */
    public function test_delete_issue()
    {
        $issue = Issue::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/issues/'.$issue->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/issues/'.$issue->id
        );

        $this->response->assertStatus(404);
    }
}
