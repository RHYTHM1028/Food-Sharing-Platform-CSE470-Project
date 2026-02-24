<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class ProfileController extends Controller
{
    /**
     * Show the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();
        
        // Debug information
        \Log::info('User address raw format: ' . $user->address);
        \Log::info('User address type: ' . gettype($user->address));
        
        // Format address for display if it's JSON
        $formattedAddress = '';
        if ($user->address) {
            // If it's already a JSON object
            if (is_array($user->address) || is_object($user->address)) {
                $address = $user->address;
            } else {
                // If it's a JSON string, decode it
                $address = json_decode($user->address, true);
            }
            
            if (is_array($address)) {
                // Format address components
                $parts = [];
                if (!empty($address['street'])) $parts[] = $address['street'];
                if (!empty($address['city'])) $parts[] = $address['city'];
                if (!empty($address['state'])) $parts[] = $address['state'];
                if (!empty($address['postal_code'])) $parts[] = $address['postal_code'];
                if (!empty($address['country'])) $parts[] = $address['country'];
                
                $formattedAddress = implode(', ', $parts);
            } else {
                $formattedAddress = $user->address;
            }
        }
        
        return view('profile.show', [
            'user' => $user,
            'formattedAddress' => $formattedAddress
        ]);
    }

    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();
        
        // Initialize address components with default empty values
        $addressComponents = [
            'street' => '',
            'city' => '',
            'state' => '',
            'postal_code' => '',
            'country' => ''
        ];
        
        // If address exists, parse it from JSON
        if (!empty($user->address)) {
            // If address is already an array (due to the array casting in the User model)
            if (is_array($user->address)) {
                $address = $user->address;
            } else {
                // If it's a JSON string, decode it
                $address = json_decode($user->address, true);
            }
            
            // Copy existing address components to our array
            if (is_array($address)) {
                foreach ($addressComponents as $key => $value) {
                    if (isset($address[$key])) {
                        $addressComponents[$key] = $address[$key];
                    }
                }
            } else {
                // If address is a simple string, put it in the street field
                $addressComponents['street'] = (string) $user->address;
            }
        }
        
        return view('profile.edit', [
            'user' => $user,
            'addressComponents' => $addressComponents
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address.street' => ['nullable', 'string', 'max:255'],
            'address.city' => ['nullable', 'string', 'max:255'],
            'address.state' => ['nullable', 'string', 'max:255'],
            'address.postal_code' => ['nullable', 'string', 'max:20'],
            'address.country' => ['nullable', 'string', 'max:255'],
        ]);

        // Check if address array exists in the request
        $addressData = $request->input('address', []);
        
        // Filter out empty address components
        $filteredAddress = array_filter($addressData, function($value) {
            return !is_null($value) && $value !== '';
        });
        
        // Update user
        $user->name = $request->name;
        $user->phone = $request->phone;
        
        // Only update address if we have components
        if (!empty($filteredAddress)) {
            // Directly store as JSON string since your database expects it
            $user->address = json_encode($filteredAddress);
        } else {
            $user->address = null;
        }
        
        // Save user changes
        $user->save();
        
        // Debug information
        \Log::info('User updated with address: ' . $user->address);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the form for editing the user's password.
     *
     * @return \Illuminate\View\View
     */
    public function editPassword()
    {
        return view('profile.password.edit');
    }
    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Password updated successfully.');
    }
}