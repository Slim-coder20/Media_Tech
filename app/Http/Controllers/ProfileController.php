<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        unset($validated['image']);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('image')) {
            $this->deleteStoredProfileImage($user->image);

            $fileName = now()->format('YmdHis').'.'.$request->file('image')->extension();
            $directory = public_path('back_auth/assets/profile');

            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $request->file('image')->move($directory, $fileName);
            $user->image = $fileName;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile modifié avec succès');
    }

    private function deleteStoredProfileImage(?string $image): void
    {
        if ($image === null || $image === '' || str_contains($image, '/')) {
            return;
        }

        $path = public_path('back_auth/assets/profile/'.$image);

        if (is_file($path)) {
            unlink($path);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
