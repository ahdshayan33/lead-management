<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display all staff members.
     */
    public function index()
    {
        $staff = User::where('role', 'staff')
            ->withCount('assignedLeads')
            ->orderBy('name')
            ->get();

        return view('staff.index', compact('staff'));
    }

    /**
     * Display the create staff form.
     */
    public function create()
    {
        return view('staff.create');
    }

    /**
     * Store a new staff member.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'staff',
        ]);

        return redirect()
            ->route('staff.index')
            ->with('success', 'Staff member created successfully!');
    }

    /**
     * Display a specific staff member.
     */
    public function show(User $user)
    {
        if ($user->role !== 'staff') {
            abort(404);
        }

        $user->loadCount('assignedLeads');

        $assignedLeads = $user->assignedLeads()
            ->latest()
            ->get();

        return view('staff.show', compact('user', 'assignedLeads'));
    }


        /**
     * Display the edit staff form.
     */
    public function edit(User $user)
    {
        if ($user->role !== 'staff') {
            abort(404);
        }

        return view('staff.edit', compact('user'));
    }


    public function update(Request $request, User $user)
    {
        if ($user->role !== 'staff') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()
            ->route('staff.show', $user)
            ->with('success', 'Staff details updated successfully.');
    }



}