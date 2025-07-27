<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        // Handle KTP image upload
        $ktpPath = null;
        if ($request->hasFile('ktp_image')) {
            $ktpPath = $request->file('ktp_image')->store('ktp-images', 'private');
        }

        $user = User::create([
            'name' => $request->name,
            'nik' => $request->nik,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'email' => $request->email,
            'ktp_image_url' => $ktpPath,
            'password' => Hash::make($request->password),
            'account_status' => 'pending_verification', // Inactive until admin approval
        ]);

        // Assign member role
        $user->assignMemberRole();

        event(new Registered($user));

        // Don't automatically login since account needs approval
        return redirect()->route('registration.success');
    }
}
