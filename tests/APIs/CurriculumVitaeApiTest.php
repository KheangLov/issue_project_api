<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CurriculumVitae;

class CurriculumVitaeApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_curriculum_vitae()
    {
        $curriculumVitae = CurriculumVitae::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/curriculum_vitaes', $curriculumVitae
        );

        $this->assertApiResponse($curriculumVitae);
    }

    /**
     * @test
     */
    public function test_read_curriculum_vitae()
    {
        $curriculumVitae = CurriculumVitae::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/curriculum_vitaes/'.$curriculumVitae->id
        );

        $this->assertApiResponse($curriculumVitae->toArray());
    }

    /**
     * @test
     */
    public function test_update_curriculum_vitae()
    {
        $curriculumVitae = CurriculumVitae::factory()->create();
        $editedCurriculumVitae = CurriculumVitae::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/curriculum_vitaes/'.$curriculumVitae->id,
            $editedCurriculumVitae
        );

        $this->assertApiResponse($editedCurriculumVitae);
    }

    /**
     * @test
     */
    public function test_delete_curriculum_vitae()
    {
        $curriculumVitae = CurriculumVitae::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/curriculum_vitaes/'.$curriculumVitae->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/curriculum_vitaes/'.$curriculumVitae->id
        );

        $this->response->assertStatus(404);
    }
}
