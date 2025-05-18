<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CloudflareService;

class MediaUploadController extends Controller
{
    public function uploadImage(Request $request, CloudflareService $cloudflare)
    {
        $request->validate([
            'file' => 'required|image|max:10240', // 10MB
        ]);

        $result = $cloudflare->uploadImage($request->file('file'));
        return response()->json($result);
    }

    public function uploadVideo(Request $request, CloudflareService $cloudflare)
    {
        $request->validate([
            'file' => 'required|mimetypes:video/mp4,video/quicktime|max:51200', // 50MB
        ]);

        $result = $cloudflare->uploadVideo($request->file('file'));
        return response()->json($result);
    }
}
