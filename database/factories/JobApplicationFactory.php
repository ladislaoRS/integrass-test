<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\JobApplication;
use Faker\Generator as Faker;

$factory->define(JobApplication::class, function (Faker $faker) {
    return [
        'user_id' => factory(\App\User::class),
        'job_id' => factory(\App\Job::class),

    ];
});
