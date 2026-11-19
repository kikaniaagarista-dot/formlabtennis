<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentSettingController extends Controller
{
    public function edit()
    {
        $setting = PaymentSetting::firstOrCreate(
            [],
            [
                'payment_name' => 'FORMLAB Tennis',
                'is_active' => true,
            ]
        );

        return view('admin.settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'payment_name' => 'required|string|max:255',
            'qris_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $setting = PaymentSetting::firstOrCreate(
            [],
            ['payment_name' => 'FORMLAB Tennis', 'is_active' => true]
        );

        if ($request->hasFile('qris_image')) {
            if ($setting->qris_image) {
                Storage::disk('public')->delete($setting->qris_image);
            }

            $path = $request->file('qris_image')
                ->store('qris', 'public');

            $setting->qris_image = $path;
        }

        $setting->payment_name = $request->payment_name;
        $setting->is_active = $request->boolean('is_active');

        $setting->save();

        return redirect('/admin/settings')
            ->with('success', 'Payment settings updated successfully!');
    }
}
