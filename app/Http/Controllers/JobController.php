<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Tag;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateJobRequest;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $job = Job::latest()->with(['employer', 'tag'])->get()->groupBy('sponsored');

        return view('jobs.index', [
            'sponsoredJobs' => $job[1],
            'jobs' => $job[0],
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return View('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'titile'      => ['required'],
            'salary'      => ['required', 'email', 'unique:users,email'],
            'location'    => ['required'],
            'schedule'    => ['required', Rule::in(['Part Time', 'Full Time'])],
            'url'         => ['required', 'active_url'],
            'tags'        => ['nullable']
        ]);
        $attributes['sponsored'] = request()->has('sponsored');
        $job = Auth::user()->employer->jobs()->create(Arr::except($attributes, 'tags'));
        if ($attributes) {
            foreach (explode(',', $attributes['tags']) as $tag) {
                $job->tag($tag);
            }
        }
    }
}
