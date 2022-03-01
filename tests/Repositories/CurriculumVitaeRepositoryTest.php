<?php namespace Tests\Repositories;

use App\Models\CurriculumVitae;
use App\Repositories\CurriculumVitaeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CurriculumVitaeRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CurriculumVitaeRepository
     */
    protected $curriculumVitaeRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->curriculumVitaeRepo = \App::make(CurriculumVitaeRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_curriculum_vitae()
    {
        $curriculumVitae = CurriculumVitae::factory()->make()->toArray();

        $createdCurriculumVitae = $this->curriculumVitaeRepo->create($curriculumVitae);

        $createdCurriculumVitae = $createdCurriculumVitae->toArray();
        $this->assertArrayHasKey('id', $createdCurriculumVitae);
        $this->assertNotNull($createdCurriculumVitae['id'], 'Created CurriculumVitae must have id specified');
        $this->assertNotNull(CurriculumVitae::find($createdCurriculumVitae['id']), 'CurriculumVitae with given id must be in DB');
        $this->assertModelData($curriculumVitae, $createdCurriculumVitae);
    }

    /**
     * @test read
     */
    public function test_read_curriculum_vitae()
    {
        $curriculumVitae = CurriculumVitae::factory()->create();

        $dbCurriculumVitae = $this->curriculumVitaeRepo->find($curriculumVitae->id);

        $dbCurriculumVitae = $dbCurriculumVitae->toArray();
        $this->assertModelData($curriculumVitae->toArray(), $dbCurriculumVitae);
    }

    /**
     * @test update
     */
    public function test_update_curriculum_vitae()
    {
        $curriculumVitae = CurriculumVitae::factory()->create();
        $fakeCurriculumVitae = CurriculumVitae::factory()->make()->toArray();

        $updatedCurriculumVitae = $this->curriculumVitaeRepo->update($fakeCurriculumVitae, $curriculumVitae->id);

        $this->assertModelData($fakeCurriculumVitae, $updatedCurriculumVitae->toArray());
        $dbCurriculumVitae = $this->curriculumVitaeRepo->find($curriculumVitae->id);
        $this->assertModelData($fakeCurriculumVitae, $dbCurriculumVitae->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_curriculum_vitae()
    {
        $curriculumVitae = CurriculumVitae::factory()->create();

        $resp = $this->curriculumVitaeRepo->delete($curriculumVitae->id);

        $this->assertTrue($resp);
        $this->assertNull(CurriculumVitae::find($curriculumVitae->id), 'CurriculumVitae should not exist in DB');
    }
}
