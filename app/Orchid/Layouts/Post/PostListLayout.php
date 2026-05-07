<?php

namespace App\Orchid\Layouts\Post;

use App\Models\Post;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PostListLayout extends Table
{
    /**
     * @var string
     */
    protected $target = 'posts';

    /**
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('title', __('Title'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (Post $post) => Link::make($post->title)
                    ->route('platform.post.edit', $post)),

            TD::make('created_at', __('Created'))
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->sort()
                ->filter(TD::FILTER_DATE_RANGE),

            TD::make('updated_at', __('Last edit'))
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->defaultHidden()
                ->sort(),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Post $post) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        Link::make(__('Edit'))
                            ->route('platform.post.edit', $post->id)
                            ->icon('bs.pencil'),

                        Button::make(__('Delete'))
                            ->icon('bs.trash3')
                            ->confirm(__('Are you sure you want to delete this post?'))
                            ->method('remove', [
                                'id' => $post->id,
                            ]),
                    ])),
        ];
    }
}
