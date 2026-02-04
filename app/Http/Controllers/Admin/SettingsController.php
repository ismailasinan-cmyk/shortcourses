<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $setting = Setting::where('key', 'payment_procedure_path')->first();
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'payment_procedure' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120', // 5MB max
        ]);

        if ($request->hasFile('payment_procedure')) {
            $path = $request->file('payment_procedure')->store('public/settings');
            
            // Remove 'public/' from path for storage retrieval if using default link
            // Actually, if I store as 'public/settings', the Db path is 'public/settings/...'
            // I should just use 'settings' and disk 'public' to be consistent.
            
            $path = $request->file('payment_procedure')->store('settings', 'public');

            Setting::updateOrCreate(
                ['key' => 'payment_procedure_path'],
                ['value' => $path]
            );
        }

        return back()->with('success', 'Payment procedure uploaded successfully.');
    }
}
