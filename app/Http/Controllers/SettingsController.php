<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $weatherSettings = $user->weatherSettings()->first();
        $preferences = $user->preference;

        // Available weather API providers
        $weatherProviders = [
            'openweathermap' => 'OpenWeatherMap',
            'weatherapi' => 'WeatherAPI.com',
            'accuweather' => 'AccuWeather',
        ];

        // Available languages
        $languages = [
            'fr' => 'Français',
            'en' => 'English',
            'ar' => 'العربية',
        ];

        return view('settings.index', compact(
            'user',
            'weatherSettings',
            'preferences',
            'weatherProviders',
            'languages'
        ));
    }

    /**
     * Update the weather settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateWeatherSettings(Request $request)
    {
        $validated = $request->validate([
            'api_provider' => 'required|string',
            'api_key' => 'required|string',
            'location' => 'nullable|string',
        ]);

        $user = auth()->user();
        $weatherSettings = $user->weatherSettings()->first();

        if ($weatherSettings) {
            $weatherSettings->update($validated);
        } else {
            $user->weatherSettings()->create($validated);
        }

        return redirect()->route('settings.index')->with('success', 'Paramètres météo mis à jour avec succès.');
    }

    /**
     * Update the user preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'language' => 'required|string|in:fr,en,ar',
            'theme' => 'required|string|in:light,dark',
        ]);

        $user = auth()->user();
        $preferences = $user->preference;

        if ($preferences) {
            $preferences->update($validated);
        } else {
            $user->preference()->create($validated);
        }

        return redirect()->route('settings.index')->with('success', 'Préférences mises à jour avec succès.');
    }
}
