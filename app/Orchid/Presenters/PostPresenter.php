<?php

declare(strict_types=1);

namespace App\Orchid\Presenters;

use Illuminate\Support\Str;
use Laravel\Scout\Builder;
use Orchid\Screen\Contracts\Searchable;
use Orchid\Support\Presenter;

class PostPresenter extends Presenter implements Searchable
{
    /**
     * Returns the label for this presenter, which is used in the UI to identify it.
     */
    public function label(): string
    {
        return 'Посты';
    }

    /**
     * Returns the title for this presenter, which is displayed in the UI as the main heading.
     */
    public function title(): string
    {
        return $this->entity->title;
    }

    /**
     * Returns the subtitle for this presenter, which provides additional context about the user.
     */
    public function subTitle(): string
    {
        return Str::limit($this->entity->text, 50);
    }

    /**
     * Returns the URL for the user's Gravatar image, or a default image if one is not found.
     */
    public function url(): string
    {
        return route('platform.post.edit', $this->entity);
    }

    /**
     * Returns the URL for the user's Gravatar image, or a default image if one is not found.
     */
    public function image(): ?string
    {
        // Если у поста есть обложка через Orchid Attachment:
        // return $this->entity->attachment()->first()?->url();

        return null;
    }

    /**
     * Returns the number of models to return for a compact search result.
     * This method is used by the search functionality to display a list of matching results.
     */
    public function perSearchShow(): int
    {
        return 5;
    }

    /**
     * Returns a Laravel Scout builder object that can be used to search for matching users.
     * This method is used by the search functionality to retrieve a list of matching results.
     */
    public function searchQuery(?string $query = null): Builder
    {
        return $this->entity->search($query);
    }
}
