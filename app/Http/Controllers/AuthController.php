<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Services\AuthService;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    #[OA\Post(
        path: "/api/register",
        summary: "Регистрация",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "email", "password", "password_confirmation"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "name"),
                    new OA\Property(property: "email", type: "string", example: "user@example.com"),
                    new OA\Property(property: "password", type: "string", example: "secret123"),
                    new OA\Property(property: "password_confirmation", type: "string", example: "secret123"),
                ]
            )
        ),
        tags: ["Auth"],
        responses: [
            new OA\Response(response: 201, description: "Created"),
            new OA\Response(response: 422, description: "Validation Error")
        ]
    )]
    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());

        return response()->json($result, 201);
    }

    #[OA\Post(
        path: "/api/login",
        summary: "Логин",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "email", type: "string", example: "user@example.com"),
                    new OA\Property(property: "password", type: "string", example: "secret123"),
                ]
            )
        ),
        tags: ["Auth"],
        responses: [
            new OA\Response(response: 200, description: "Success"),
            new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]
    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        return response()->json($result, 200);
    }
}
