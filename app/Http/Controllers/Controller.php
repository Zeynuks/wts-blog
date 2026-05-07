<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(version: "1.0.0", description: "API для регистрации и постов", title: "My Social API")]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    name: "Authorization",
    in: "header",
    bearerFormat: "JWT",
    scheme: "bearer"
)]
abstract class Controller
{
    //
}
