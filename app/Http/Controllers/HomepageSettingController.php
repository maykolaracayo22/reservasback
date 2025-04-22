<?php

namespace App\Http\Controllers;

use App\Models\HomepageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomepageSettingController extends Controller
{
    public function index()
    {
        $setting = HomepageSetting::first();

        if (!$setting) {
            return response()->json(['error' => 'No hay configuración registrada'], 404);
        }

        return response()->json([
            'title' => $setting->title_text,
            'title_color' => $setting->title_color,
            'title_size' => $setting->title_size,
            'description' => $setting->description,
            'background_color' => $setting->background_color,
            'image_url' => $setting->image_path
                ? asset('storage/' . $setting->image_path)
                : null,
        ]);
    }

    public function __construct()
    {
        //$this->middleware('auth:sanctum');
        //$this->middleware('role:super-admin');
    }

    // Obtener configuración
    public function show()
    {
        $settings = HomepageSetting::first();
        return response()->json($settings);
    }
    public function public()
{
    $settings = HomepageSetting::first();

    if (!$settings) {
        return response()->json([
            'message' => 'No hay configuración disponible',
        ], 404);
    }

    return response()->json([
        'title_text' => $settings->title_text,
        'title_color' => $settings->title_color,
        'title_size' => $settings->title_size,
        'description' => $settings->description,
        'background_color' => $settings->background_color,
        'image_url' => $settings->image_path ? asset('storage/' . $settings->image_path) : null,
    ]);

}

    // Actualizar configuración
    public function update(Request $request)
    {
        $data = $request->validate([
            'title_text'      => 'nullable|string|max:255',
            'title_color'     => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'title_size'      => 'nullable|string',
            'description'     => 'nullable|string',
            'background_color'=> 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'image'           => 'nullable|image|max:2048',
        ]);

        $settings = HomepageSetting::first();

        // Manejar subida de imagen
        if ($request->hasFile('image')) {
            // Borrar imagen antigua
            if ($settings->image_path) {
                Storage::disk('public')->delete($settings->image_path);
            }
            $path = $request->file('image')->store('homepage', 'public');
            $data['image_path'] = $path;
        }

        $settings->update($data);

        return response()->json($settings);
    }
    public function removeImage()
    {
        $home = HomepageSetting::first();
        if ($home && $home->image_path) {
            Storage::disk('public')->delete($home->image_path);
            $home->image_path = null;
            $home->save();
        }
        return response()->json(['message' => 'Imagen eliminada']);
    }


}
