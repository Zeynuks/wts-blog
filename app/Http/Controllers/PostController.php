<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StorePostRequest;
use App\Http\Services\PostService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PostController extends Controller
{
    public function __construct(protected PostService $postService) {}

    #[OA\Get(
        path: "/api/posts",
        summary: "Список постов",
        security: [["bearerAuth" => []]],
        tags: ["Posts"],
        parameters: [
            new OA\Parameter(name: "limit", in: "query", schema: new OA\Schema(type: "integer", default: 10)),
            new OA\Parameter(name: "offset", in: "query", schema: new OA\Schema(type: "integer", default: 0)),
            new OA\Parameter(
                name: "filter[title]",
                description: "Поиск по названию",
                in: "query",
                schema: new OA\Schema(type: "string")
            ),
            new OA\Parameter(
                name: "filter[user_id]",
                description: "ID автора поста",
                in: "query",
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "filter[created_at][start]",
                description: "Дата создания ОТ (ГГГГ-ММ-ДД)",
                in: "query",
                schema: new OA\Schema(type: "string", format: "date")
            ),
            new OA\Parameter(
                name: "filter[created_at][end]",
                description: "Дата создания ДО (ГГГГ-ММ-ДД)",
                in: "query",
                schema: new OA\Schema(type: "string", format: "date")
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: "OK"),
            new OA\Response(
                response: 401,
                description: "Ошибка авторизации",
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: "message", type: "string", example: "Unauthenticated.")
                ])
            )
        ]
    )]
    public function index(Request $request)
    {
        $posts = $this->postService->getPosts(
            $request->query('limit', 10),
            $request->query('offset', 0)
        );

        return response()->json($posts);
    }

    #[OA\Post(
        path: "/api/posts",
        summary: "Создать пост",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "title", type: "string"),
                    new OA\Property(property: "text", type: "string"),
                ]
            )
        ),
        tags: ["Posts"],
        responses: [
            new OA\Response(
                response: 201,
                description: "Пост создан",
                content: new OA\JsonContent(ref: "#/components/schemas/Post")
            ),
            new OA\Response(
                response: 401,
                description: "Ошибка авторизации",
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: "message", type: "string", example: "Unauthenticated.")
                ])
            )
        ]
    )]
    public function store(StorePostRequest $request)
    {
        $post = $this->postService->createPost(
            $request->user(),
            $request->validated()
        );

        return response()->json($post, 201);
    }

    #[OA\Get(
        path: "/api/posts/my",
        summary: "Список постов текущего пользователя",
        security: [["bearerAuth" => []]],
        tags: ["Posts"],
        parameters: [
            new OA\Parameter(name: "limit", in: "query", schema: new OA\Schema(type: "integer", default: 10)),
            new OA\Parameter(name: "offset", in: "query", schema: new OA\Schema(type: "integer", default: 0)),
            new OA\Parameter(
                name: "filter[title]",
                description: "Поиск по названию",
                in: "query",
                schema: new OA\Schema(type: "string")
            ),
            new OA\Parameter(
                name: "filter[created_at][start]",
                description: "Дата создания ОТ (ГГГГ-ММ-ДД)",
                in: "query",
                schema: new OA\Schema(type: "string", format: "date")
            ),
            new OA\Parameter(
                name: "filter[created_at][end]",
                description: "Дата создания ДО (ГГГГ-ММ-ДД)",
                in: "query",
                schema: new OA\Schema(type: "string", format: "date")
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: "Список ваших постов"),
            new OA\Response(
                response: 401,
                description: "Ошибка авторизации",
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: "message", type: "string", example: "Unauthenticated.")
                ])
            )
        ]
    )]
    public function myPosts(Request $request)
    {
        $posts = $this->postService->getUserPosts(
            $request->user(),
            $request->query('limit', 10),
            $request->query('offset', 0)
        );

        return response()->json($posts);
    }
}
