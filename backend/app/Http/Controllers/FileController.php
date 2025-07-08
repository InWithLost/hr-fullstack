<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480' // 20MB
        ]);

        $uploaded = $request->file('file');
        $path = $uploaded->store('uploads', 'public');

        $file = File::create([
            'user_id' => $request->user()?->id,
            'original_name' => $uploaded->getClientOriginalName(),
            'path' => $path,
            'mime' => $uploaded->getClientMimeType(),
            'size' => $uploaded->getSize(),
        ]);

        return response()->json(['id' => $file->id, 'url' => $file->url]);
    }

    public function destroy(Request $request, File $file)
    {
        // Only owner or admin can delete
        if ($file->user_id && $request->user()->id !== $file->user_id && $request->user()->role !== 'admin') {
            abort(403);
        }

        Storage::disk('public')->delete($file->path);
        $file->delete();
        return response()->json(['message' => 'deleted']);
    }
}