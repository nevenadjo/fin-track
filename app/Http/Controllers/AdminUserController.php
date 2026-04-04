<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%");
        }

        $users = $query->paginate(10);

        return view('pages.admin.users', compact('users'));
    }

    public function toggleBlock(User $user, LogService $logService)
    {
        $user->is_blocked = !$user->is_blocked;
        $user->save();

        if($user->is_blocked) {
            $logService->log('user_block', 'Blocked user ID: ' . $user->id);
        }
        else {
            $logService->log('user_unblock', 'Unblocked user ID: '.$user->id);
        }

        return back()->with(['success' => 'User updated successfully']);
    }

    public function changeRole(Request $request, User $user, LogService $logService)
    {
        $user->role_id = $request->role;
        $user->save();

        $logService->log('user_role', 'Changed role for user ID: '.$user->id);

        return back()->with(['success' => 'User updated successfully']);
    }
}
