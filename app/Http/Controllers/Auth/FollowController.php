<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        $currentUser = auth()->user();

        if (!$currentUser->following->contains($user)) {
            $currentUser->following()->attach($user);

            Notification::create([
                'user_id' => $user->id, // User yang di-follow (penerima notifikasi)
                'action_user_id' => $currentUser->id, // User yang melakukan follow (pelaku tindakan)
                'type' => 'follow',
            ]);
        }

        return redirect()->back()->with('success', 'You are now following ' . $user->name);
    }

    public function unfollow(User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->following->contains($user)) {
            $currentUser->following()->detach($user);
        }

        return redirect()->back()->with('success', 'You have unfollowed ' . $user->name);
    }
}

