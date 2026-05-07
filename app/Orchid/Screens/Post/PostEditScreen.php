<?php

namespace App\Orchid\Screens\Post;

use App\Models\Post;
use App\Orchid\Layouts\Post\PostEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PostEditScreen extends Screen
{
    /**
     * @var Post
     */
    public $post;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @param Post $post
     *
     * @return array
     */
    public function query(Post $post): iterable
    {
        if ($post->exists) {
            $post->load('user');
        }

        return [
            'post' => $post
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->post->exists ? 'Edit Post' : 'Create Post';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Manage blog content, including titles, body text, and publication status.';
    }

    /**
     * The permissions required to access this screen.
     */
    public function permission(): ?iterable
    {
        return [
            'platform.posts',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Remove')
                ->icon('bs.trash3')
                ->confirm('Once the post is deleted, all of its data will be permanently removed.')
                ->method('remove')
                ->canSee($this->post->exists),

            Button::make('Save')
                ->icon('bs.check-circle')
                ->method('save'),

            Link::make('Cancel')
                ->icon('bs.x-circle')
                ->route('platform.post.list'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block(PostEditLayout::class)
                ->title('Post Information')
                ->description('Update the main content and details of your publication.')
                ->commands(
                    Button::make('Save')
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->method('save')
                ),

            Layout::legend('post', [
                Sight::make('user.name', 'Author'),
                Sight::make('created_at', 'Created')
                    ->render(fn(Post $post) => $post->created_at?->format('d.m.Y H:i')),
            ])
                ->title('Data')
                ->canSee($this->post->exists),
        ];
    }

    /**
     * Save or update the post.
     *
     * @param Post $post
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(Post $post, Request $request)
    {
        $request->validate([
            'post.title' => 'required|string|max:255',
            'post.text' => 'required|string',
        ]);

        $post->fill($request->get('post'));

        if (!$post->exists) {
            $post->user_id = Auth::id();
        }

        $post->save();

        Toast::info('Post was saved successfully.');

        return redirect()->route('platform.post.list');
    }

    /**
     * Remove the post.
     *
     * @param Post $post
     *
     * @return RedirectResponse
     * @throws \Exception
     *
     */
    public function remove(Post $post)
    {
        $post->delete();

        Toast::info('Post was removed.');

        return redirect()->route('platform.post.list');
    }
}
