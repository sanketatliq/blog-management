<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class BlogService
{
    // Create Blog
    public function createBlog(array $data, $image)
    {
        $blog = Blog::create([
            'title'       => $data['title'],
            'description' => $data['description'],
            'created_by'  => Auth::id(),
        ]);

        $blog->addMedia($image)->toMediaCollection('blog_images');

        return $blog;
    }

    // Update Blog
    public function updateBlog(Blog $blog, array $data, $image = null)
    {
        $blog->update([
            'title'       => $data['title'] ?? $blog->title,
            'description' => $data['description'] ?? $blog->description,
        ]);

        if ($image) {
            $blog->clearMediaCollection('blog_images');
            $blog->addMedia($image)->toMediaCollection('blog_images');
        }

        return $blog->fresh();
    }

    // Delete Blog
    public function deleteBlog(Blog $blog)
    {
        $blog->clearMediaCollection('blog_images');
        $blog->delete();
    }

    // Toggle Like
    public function toggleLike(Blog $blog): array
    {
        $userId = Auth::id();

        $like = Like::where([
            'user_id'       => $userId,
            'likeable_id'   => $blog->id,
            'likeable_type' => Blog::class,
        ])->first();

        if ($like) {
            $like->delete();
            $isLiked = false;
        } else {
            Like::create([
                'user_id'       => $userId,
                'likeable_id'   => $blog->id,
                'likeable_type' => Blog::class,
            ]);
            $isLiked = true;
        }

        $blog = $blog->fresh();
        $blog->is_liked    = $isLiked;
        $blog->likes_count = $blog->likes()->count();

        return [
            'blog'     => $blog,
            'is_liked' => $isLiked,
        ];
    }

    // Get Blogs with filters, search, and pagination
    public function getBlogs(int $perPage, array $filters = [])
    {
        $userId = Auth::id();

        $query = Blog::with('creator')
            ->withCount('likes')
            ->withExists(['likes as is_liked' => fn ($q) => $q->where('user_id', $userId)]);

        // Search in title, description, and creator name/email
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('creator', fn ($u) => $u->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        // User filter
        if (! empty($filters['user_id'])) {
            $query->where('created_by', $filters['user_id']);
        }

        // Sorting
        $sortOrder = in_array($filters['sort_order'] ?? '', ['asc', 'desc']) ? $filters['sort_order'] : 'desc';
        $sortBy    = $filters['sort_by'] ?? 'created_at';

        if ($sortBy === 'like_count') {
            $query->orderBy('likes_count', $sortOrder);
        } else {
            $allowedColumns = ['title', 'description', 'created_at'];
            $query->orderBy(in_array($sortBy, $allowedColumns) ? $sortBy : 'created_at', $sortOrder);
        }

        return $query->paginate($perPage);
    }
}
