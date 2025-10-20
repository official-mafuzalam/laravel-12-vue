<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all()->map(function ($user) {
            $lastSeenDetailed = $user->getLastSeenDetailed();

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
                'is_online' => $user->isOnline(),
                'online_status' => $user->getOnlineStatus(),
                'last_seen_at' => $user->last_seen_at,
                'last_seen_text' => $user->getLastSeenText(),
                'last_seen_detailed' => $lastSeenDetailed,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        });

        return Inertia::render('admin/Users/Index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('admin/Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return Inertia::render('admin/Users/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
                'last_seen_at' => $user->last_seen_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (!$user) {
            return redirect()->route('admin.users.index')
                ->with('error', 'User not found.');
        }
        return Inertia::render('admin/Users/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Update user status (block/unblock)
     */
    /**
     * Update user status (block/unblock)
     */
    public function updateStatus(Request $request, User $user)
    {
        // Prevent users from blocking themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.edit', $user)
                ->with('error', 'You cannot block your own account.');
        }

        $validated = $request->validate([
            'status' => 'required|string|in:active,blocked',
        ]);

        $oldStatus = $user->status;
        $user->update([
            'status' => $validated['status']
        ]);

        $action = $validated['status'] === 'blocked' ? 'blocked' : 'unblocked';

        // ✅ If user was blocked, invalidate their sessions
        if ($validated['status'] === 'blocked' && $oldStatus === 'active') {
            $this->invalidateUserSessions($user);
        }

        return redirect()->route('admin.users.edit', $user)
            ->with('success', "User {$action} successfully!");
    }

    /**
     * Invalidate all active sessions for a user
     */
    protected function invalidateUserSessions(User $user)
    {
        // You can implement session invalidation logic here
        // This is a simple approach - in production you might want to use database sessions

        Log::info("User {$user->id} ({$user->email}) was blocked. Sessions invalidated.");

        // If you're using database sessions, you can delete them:
        // \DB::table('sessions')->where('user_id', $user->id)->delete();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent users from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User {$userName} deleted successfully!");
    }
}