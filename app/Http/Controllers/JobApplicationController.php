<?php

namespace App\Http\Controllers;

use App\Job;
use App\Candidate;
use App\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class JobApplicationController extends ApiBaseController
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Job $job, Request $request, Candidate $candidate)
    {
        $this->authorize('create', JobApplication::class);
        
        $jobApplication = JobApplication::create([
            'job_id' => $job->id,
            'user_id' => Auth::user()->id,    
        ]);

         return $this->respondCreated('The job application was succesfully created');
    }
}
