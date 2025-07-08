<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompanyManagerController extends Controller
{
    // GET /company/managers
    public function index(Request $request)
    {
        $company = $request->user()->companies()->firstOrFail();
        return response()->json($company->users);
    }

    // POST /company/managers
    public function store(Request $request)
    {
        $company = $request->user()->companies()->firstOrFail();
        if (! $request->user()->isOwnerOf($company) && $request->user()->role !== 'admin') {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|min:6',
        ]);

        $manager = User::firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'password' => isset($data['password']) ? Hash::make($data['password']) : Hash::make('password'),
                'role' => 'company',
            ]
        );

        $company->users()->syncWithoutDetaching([$manager->id => ['is_owner' => false]]);

        return response()->json($manager, 201);
    }

    // DELETE /company/managers/{user}
    public function destroy(Request $request, User $user)
    {
        $company = $request->user()->companies()->firstOrFail();
        if (! $request->user()->isOwnerOf($company) && $request->user()->role !== 'admin') {
            abort(403);
        }

        $company->users()->detach($user->id);

        return response()->json(['message' => 'Manager removed']);
    }
}