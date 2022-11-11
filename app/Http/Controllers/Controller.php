<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasResponse;
use OpenApi\Annotations as OA;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Swagger(
 *   schemes={"http"},
 *   host="localhost",
 *   basePath="/",
 *   @OA\Info(
 *     title="Stoxtech Admin API",
 *     version="1.0.0"
 *   )
 * )
 *
 * @OA\Schema (
 *     schema="Meta",
 *     type="object",
 *     @OA\Property(
 *          property="pagination",
 *          type="object",
 *          @OA\Property(property="total", type="integer"),
 *          @OA\Property(property="count", type="integer"),
 *          @OA\Property(property="per_page", type="integer"),
 *          @OA\Property(property="current_page", type="integer"),
 *          @OA\Property(property="total_pages", type="integer"),
 *          @OA\Property(property="links", type="array", @OA\Items(type="string"))
 *     )
 * )
 *
 * @OA\Schema (
 *     schema="ValidationError",
 *     type="object",
 *     @OA\Property(property="message", type="string", example="Error description"),
 *     @OA\Property(property="errors", type="array", @OA\Items(type="string"))
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, HasResponse, ValidatesRequests;
}
