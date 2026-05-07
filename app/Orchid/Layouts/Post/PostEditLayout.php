<?php

namespace App\Orchid\Layouts\Post;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class PostEditLayout extends Rows
{
    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Input::make('post.title')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Title'))
                ->placeholder(__('Title')),

            Quill::make('post.text')
                ->title(__('Text'))
                ->placeholder(__('Text'))
                ->required(),
        ];
    }
}
