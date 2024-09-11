<?php
/*
    AAA
    Arrange, Act, Assert
*/

use App\Models\Employer;
use App\Models\Job;

it('belongs to an employer', function () {
    //Arrange
    $employer = Employer::factory()->create();
    $job = Job::factory()->create([
        'employer_id' => $employer->id,
    ]);

    //Act
    expect($job->employer->is($employer))->toBeTrue;
});

it('can have Tags', function () {
    $job = Job::factory()->create();
    $job->tag('Backend');
    expect($job->tags)->toHaveCount(1);
});
