<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\DetailResource;
use App\Http\Resources\Blog\PaginatedResource;
use App\Models\Blog;
use App\Services\BlogService;
use App\Traits\ApiResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    use ApiResponseHelper;

    public function __construct(protected BlogService $blogService) {}

    // Create Blog
    public function createBlog(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title'       => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
                'image'       => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->messages()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $blog = $this->blogService->createBlog($request->only('title', 'description'), $request->file('image'));

            return $this->sendResponse('Blog created successfully.', new DetailResource($blog));
        } catch (\Throwable $th) {
            Log::error('Failed to create blog', [$th->getMessage()]);

            return $this->sendError('Something went wrong.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Edit Blog
    public function editBlog(Request $request, Blog $blog)
    {
        try {
            if ($blog->created_by !== Auth::id()) {
                return $this->sendError('You can only edit your own blogs.', Response::HTTP_FORBIDDEN);
            }

            $validator = Validator::make($request->all(), [
                'title'       => ['sometimes', 'string', 'max:255'],
                'description' => ['sometimes', 'string'],
                'image'       => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->messages()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $blog = $this->blogService->updateBlog($blog, $request->only('title', 'description'), $request->file('image'));

            return $this->sendResponse('Blog updated successfully.', new DetailResource($blog));
        } catch (\Throwable $th) {
            Log::error('Failed to update blog', [$th->getMessage()]);

            return $this->sendError('Something went wrong.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Delete Blog
    public function deleteBlog(Blog $blog)
    {
        try {
            if ($blog->created_by !== Auth::id()) {
                return $this->sendError('You can only delete your own blogs.', Response::HTTP_FORBIDDEN);
            }

            $this->blogService->deleteBlog($blog);

            return $this->sendResponse('Blog deleted successfully.');
        } catch (\Throwable $th) {
            Log::error('Failed to delete blog', [$th->getMessage()]);

            return $this->sendError('Something went wrong.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Get Blogs
    public function getBlogs(Request $request)
    {
        try {
            $perPage = $request->perPage ?? 10;

            $blogs = $this->blogService->getBlogs($perPage);

            return $this->sendResponse('Blogs fetched successfully.', new PaginatedResource($blogs));
        } catch (\Throwable $th) {
            Log::error('Failed to fetch all the blogs', [$th->getMessage()]);

            return $this->sendError('Something went wrong.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
