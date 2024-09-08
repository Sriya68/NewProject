<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            //'role' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255'
            //'description' => '|string|max:255'

        ]);

        // Store the image in the 'public/images' directory
        $path = $request->file('image')->store('images', 'public');

        // Create a new post
        $post = Post::create([
           // 'user_id' => Auth::id(),  // Authenticated user's ID
            'image' => $path, 

           // 'role' => $request->input('role'),
            //'role' => $request->role,

            'title' => $request->title,
            'description' => $request->description
        ]);

        // Return a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post
        ], 201);
    }
}
