<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    // POST /company/register
    public function register(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'inn'  => 'nullable|string|max:12',
            'description' => 'nullable|string',
            'logo' => 'nullable|image',
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $path;
        }

        $company = Company::create($data);

        // attach user as owner
        $company->users()->attach($user->id, ['is_owner' => true]);
        $user->update(['role' => $user->role === 'admin' ? 'admin' : 'company']);

        return response()->json($company, 201);
    }

    // GET /company/profile
    public function show(Request $request)
    {
        $company = $request->user()->companies()->first();
        if (! $company) {
            return response()->json(['message' => 'No company'], 404);
        }
        return response()->json($company->load('users'));
    }

    // PUT /company/profile
    public function update(Request $request)
    {
        $company = $request->user()->companies()->firstOrFail();
        if (! $request->user()->isOwnerOf($company) && $request->user()->role !== 'admin') {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'inn' => 'sometimes|nullable|string|max:12',
            'description' => 'sometimes|nullable|string',
            'logo' => 'sometimes|nullable|image',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $path;
        }

        $company->update($data);

        return response()->json($company);
    }
}