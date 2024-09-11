<?php

namespace App\Http\Controllers;

use App\Models\Job;


class SearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $jobs = Job::query()
            ->with(['employer', 'tag'])
            ->where('title', 'LIKE', '%' . request('q') . '%')
            ->get();
        return View('results', ['jobs' => $jobs]);
    }
}
