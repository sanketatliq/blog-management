<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Support\Facades\Auth;

class BlogService
{
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

    public function deleteBlog(Blog $blog)
    {
        $blog->clearMediaCollection('blog_images');
        $blog->delete();
    }

    public function getBlogs(int $perPage)
    {
        return Blog::paginate($perPage);
    }
}
