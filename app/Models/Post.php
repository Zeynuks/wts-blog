<?php

namespace App\Models;

use App\Orchid\Presenters\PostPresenter;
use Illuminate\Database\Eloquent\Model;

use OpenApi\Attributes as OA;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Screen\AsSource;

#[OA\Schema(
    schema: "Post",
    title: "Post Model",
    description: "Схема модели поста",
    properties: [
        new OA\Property(property: "id", type: "integer", readOnly: true, example: 1),
        new OA\Property(property: "title", type: "string", example: "Заголовок поста"),
        new OA\Property(property: "text", type: "string", example: "Содержимое поста"),
        new OA\Property(property: "user_id", type: "integer", example: 1),
        new OA\Property(property: "created_at", type: "string", format: "date-time", readOnly: true),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", readOnly: true),
    ]
)]
class Post extends Model
{
    use AsSource, Filterable, Attachable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'text',
        'user_id'
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id' => Where::class,
        'title' => Like::class,
        'user_id' => Where::class,
        'updated_at' => WhereDateStartEnd::class,
        'created_at' => WhereDateStartEnd::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'title',
        'user_id',
        'updated_at',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function presenter(): PostPresenter
    {
        return new PostPresenter($this);
    }
}
