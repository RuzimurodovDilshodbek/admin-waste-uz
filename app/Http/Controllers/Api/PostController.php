<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ManagementPerson;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // GET /api/v1/posts
    public function index(Request $request)
    {
        $query = Post::query();

        // agar section_id yuborilsa filterlash
        if ($request->has('section_id')) {
            $query->whereJsonContains('section_ids', $request->section_id);
        }

        $posts = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }


    public function getManagementPersons(Request $request)
    {
        $query = ManagementPerson::query();

        if ($request->has('type')) {
            $query->where('type',$request->type);
        }

        $management = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $management
        ]);

    }
}
