<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    /**
     * All pages that have onboarding tours.
     */
    private array $allTourPages = ['home', 'schedule', 'workspace', 'workspace_selected', 'profile', 'help', 'privacy'];

    /**
     * Mark the current user's onboarding as completed for a specific page.
     */
    public function complete(Request $request)
    {
        $request->validate([
            'page' => 'required|string'
        ]);

        $user = Auth::user();
        
        $completed = $user->onboarding_completed ?? [];
        if (!is_array($completed)) {
            $completed = [];
        }

        if (!in_array($request->page, $completed)) {
            $completed[] = $request->page;
            $user->onboarding_completed = $completed;
        }

        // Check if all tour pages have been completed
        $allCompleted = empty(array_diff($this->allTourPages, $completed));
        if ($allCompleted) {
            $user->needs_onboarding = false;
        }

        $user->save();

        return response()->json(['success' => true]);
    }
}

