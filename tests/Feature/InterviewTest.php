<?php

namespace Tests\Feature;

use App\Candidate;
use App\Job;
use App\JobApplication;
use App\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class InterviewTest extends TestCase
{

    /**
     * @test
     */
    public function only_authenticated_candidates_can_see_job_postings()
    {
        //arrange
        $candidate = factory(Candidate::class)->create();
        $candidate->api_token = null;

        //act
        $response = $this->get('/api/jobs', $this->myHeaders($candidate));

        //assert
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function a_candidate_can_see_job_postings()
    {
        $candidate = factory(Candidate::class)->create();
        $response = $this->get('/api/jobs', $this->myHeaders($candidate));

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function a_candidate_can_apply_to_a_job_posting()
    {
        $this->withoutExceptionHandling();
        //arrange
        factory(Job::class, 10)->create();
        $job = Job::inRandomOrder()->first();
        $candidate = factory(Candidate::class)->create();

        //act
        $response = $this->post('/api/jobs/'.$job->id.'/apply', [], $this->myHeaders($candidate));

        //assert
        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertCount(1, JobApplication::all());
        $jobApplication = JobApplication::first();
        $this->assertEquals($job->id, $jobApplication->job_id);
        $this->assertEquals($candidate->id, $jobApplication->user_id);

    }

    protected function myHeaders(User $user)
    {
        return [
            'Authorization' => 'Bearer '.$user->api_token,
            'Accept' => 'application/json',
        ];
    }
}
