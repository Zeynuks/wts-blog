<?php
namespace App\Http\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class PostService
{
    public function getPosts(int $limit, int $offset): Collection
    {
        return Post::with('user:id,name')
            ->filters()
            ->latest()
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function getUserPosts(User $user, int $limit, int $offset): Collection
    {
        return $user->posts()
            ->filters()
            ->latest()
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function createPost(User $user, array $data): Post
    {
        return $user->posts()->create($data);
    }
}
