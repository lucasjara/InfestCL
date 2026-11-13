<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CardController extends Controller
{
    public function uploadImage(Request $request)
    {
        $validated = $request->validate([
            'image' => [
                'required',
                'image',
                'mimes:jpeg,png,webp',
                'max:5120',
                'dimensions:max_width=3000,max_height=3000',
            ],
        ]);

        $image = $validated['image'];

        $extension = $image->extension();

        $filename = Str::uuid() . '.' . $extension;

        $path = $image->storeAs(
            'cards',
            $filename,
            'public'
        );

        return response()->json([
            'success' => true,
            'url' => Storage::disk('public')->url($path),
        ], 201);
    }
}
