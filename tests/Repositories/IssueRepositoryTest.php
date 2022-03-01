<?php namespace Tests\Repositories;

use App\Models\Issue;
use App\Repositories\IssueRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class IssueRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var IssueRepository
     */
    protected $issueRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->issueRepo = \App::make(IssueRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_issue()
    {
        $issue = Issue::factory()->make()->toArray();

        $createdIssue = $this->issueRepo->create($issue);

        $createdIssue = $createdIssue->toArray();
        $this->assertArrayHasKey('id', $createdIssue);
        $this->assertNotNull($createdIssue['id'], 'Created Issue must have id specified');
        $this->assertNotNull(Issue::find($createdIssue['id']), 'Issue with given id must be in DB');
        $this->assertModelData($issue, $createdIssue);
    }

    /**
     * @test read
     */
    public function test_read_issue()
    {
        $issue = Issue::factory()->create();

        $dbIssue = $this->issueRepo->find($issue->id);

        $dbIssue = $dbIssue->toArray();
        $this->assertModelData($issue->toArray(), $dbIssue);
    }

    /**
     * @test update
     */
    public function test_update_issue()
    {
        $issue = Issue::factory()->create();
        $fakeIssue = Issue::factory()->make()->toArray();

        $updatedIssue = $this->issueRepo->update($fakeIssue, $issue->id);

        $this->assertModelData($fakeIssue, $updatedIssue->toArray());
        $dbIssue = $this->issueRepo->find($issue->id);
        $this->assertModelData($fakeIssue, $dbIssue->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_issue()
    {
        $issue = Issue::factory()->create();

        $resp = $this->issueRepo->delete($issue->id);

        $this->assertTrue($resp);
        $this->assertNull(Issue::find($issue->id), 'Issue should not exist in DB');
    }
}
