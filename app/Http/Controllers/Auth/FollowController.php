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
                'user_id' => $user->id,
                'action_user_id' => $currentUser->id,
                'type' => 'follow',
            ]);
            $message = 'You are now following ' . $user->name;
        } else {
            $message = 'You are already following ' . $user->name;
        }

        return redirect()->back()->with('success', $message);
    }

    public function unfollow(User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->following->contains($user)) {
            $currentUser->following()->detach($user);
            $message = 'You have unfollowed ' . $user->name;
        } else {
            $message = 'You are not following ' . $user->name;
        }

        return redirect()->back()->with('success', $message);
    }
}
