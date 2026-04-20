<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display settings page
     */
    public function index()
    {
        return view('settings.index');
    }

    /**
     * Display notifications settings page
     */
    public function notifications()
    {
        return view('settings.notifications');
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'language' => ['nullable', 'string', 'in:id,en,ar'],
            'timezone' => ['nullable', 'string'],
            'date_format' => ['nullable', 'string'],
            'theme' => ['nullable', 'string', 'in:light,dark,auto'],
            'font_size' => ['nullable', 'string', 'in:small,medium,large'],
        ]);

        // Save to user preferences (you can store in database or session)
        $user = auth()->user();
        $user->settings = array_merge($user->settings ?? [], $validated);
        $user->save();

        return redirect()
            ->route('settings.index')
            ->with('success', 'Pengaturan berhasil disimpan!');
    }
}
