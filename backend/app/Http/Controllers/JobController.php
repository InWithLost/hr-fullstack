<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $jobs = Job::with(['company', 'specialty'])
            ->when($request->query('specialty'), function ($q, $slug) {
                $q->whereHas('specialty', fn ($query) => $query->where('slug', $slug));
            })
            ->when($request->query('q'), function ($q, $term) {
                $q->where('title', 'like', "%{$term}%");
            })
            ->latest()
            ->paginate(15);

        return response()->json($jobs);
    }

    public function show(Job $job)
    {
        $job->load(['company', 'specialty']);
        return response()->json($job);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'company' && $user->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary_from' => 'nullable|integer',
            'salary_to' => 'nullable|integer',
            'company_id' => 'required|exists:companies,id',
            'specialty_id' => 'required|exists:specialties,id',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(6);

        $job = Job::create($validated);

        return response()->json($job, 201);
    }

    public function update(Request $request, Job $job)
    {
        $user = $request->user();
        if ($user->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'salary_from' => 'nullable|integer',
            'salary_to' => 'nullable|integer',
            'status' => 'sometimes|in:draft,published,archived',
        ]);

        $job->update($validated);

        return response()->json($job);
    }

    public function destroy(Request $request, Job $job)
    {
        $user = $request->user();
        if ($user->role !== 'admin') {
            abort(403);
        }

        $job->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function apply(Request $request, Job $job)
    {
        $request->user()->applications()->create([
            'job_id' => $job->id,
            'cover_letter' => $request->input('cover_letter'),
        ]);

        return response()->json(['message' => 'Applied']);
    }

    public function withdraw(Request $request, Application $application)
    {
        if ($application->user_id !== $request->user()->id) {
            abort(403);
        }

        $application->update(['status' => 'withdrawn']);

        return response()->json(['message' => 'Withdrawn']);
    }
}