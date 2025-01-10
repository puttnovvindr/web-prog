<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
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
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'gender' => ['required', 'in:Male,Female'],
            'field_of_work' => ['required', 'array', 'min:3'],
            'field_of_work.*' => ['required', 'string'],
            'linkedin' => 'required|url|regex:/^https:\/\/www\.linkedin\.com\/in\/[a-zA-Z0-9-]+\/$/',
            'mobile_number' => ['required', 'regex:/^\d+$/'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'fields_of_work' => json_encode($request->field_of_work),
            'linkedin_url' => $request->linkedin,
            'mobile_number' => $request->mobile_number,
            'wallet_balance' => rand(100000, 125000),
            'visible' => true,
        ]);


         // Simpan profile_picture jika ada
         $profilePicturePath = $request->file('profile_picture')
            ? $request->file('profile_picture')->store('profile_pictures', 'public')
            : null;

        // Tambahkan 'profile_picture' ke session
        session([
            'register_data' => array_merge($request->only([
                'name', 'email', 'password', 'gender', 'field_of_work', 'linkedin', 'mobile_number',
            ]), [
                'profile_picture' => $profilePicturePath,
            ]),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('payment.index') // Halaman pembayaran setelah registrasi
            ->with('message', 'You have successfully registered. Please proceed with the payment.');
    }

    public function handlePaymentSuccess(Request $request): RedirectResponse
    {
        // Ambil data yang telah disimpan di session
        $registerData = session('register_data');

        if (!$registerData) {
            return redirect()->route('login')->with('error', 'Invalid payment data.');
        }

        // Buat user langsung setelah pembayaran berhasil tanpa login
        $user = User::create([
            'name' => $registerData['name'],
            'email' => $registerData['email'],
            'password' => Hash::make($registerData['password']),
            'gender' => $registerData['gender'],
            'fields_of_work' => json_encode($registerData['field_of_work']),
            'linkedin_url' => $registerData['linkedin'],
            'mobile_number' => $registerData['mobile_number'],
            'profile_picture' => $registerData['profile_picture'], // Simpan path ke database
            'wallet_balance' => rand(100000, 125000), // Bisa disesuaikan
            'visible' => true,
        ]);


        // Hapus data pendaftaran yang ada di session
        session()->forget('register_data');


        // Langsung ke dashboard tanpa login
        return redirect()->route('dashboard')->with('message', 'Registration successful and payment verified.');
    }
}
