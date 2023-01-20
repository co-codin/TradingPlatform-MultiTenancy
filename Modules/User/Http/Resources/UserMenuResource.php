<?php

declare(strict_types=1);

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Enums\UserMenu;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     schema="UserMenu",
 *     type="object",
 *     required={
 *         "name",
 *         "link",
 *         "submenu",
 *     },
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="link", type="string"),
 *     @OA\Property(
 *         property="submenu",
 *         description="Submenu",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             required={
 *                 "name",
 *                 "link",
 *             },
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="link", type="string"),
 *         ),
 *     ),
 * )
 *
 * Class UserMenuResource
 * @mixin JsonResource
 */
final class UserMenuResource extends JsonResource
{
    public function toArray($request)
    {
        $menuList = [];
        foreach (UserMenu::menu() as $key => $menu) {
            if ($this->can($menu['permission'])) {
                $menuList[$key] = [
                    'name' => __($menu['name']),
                    'link' => $menu['link']
                ];

                foreach ($menu['submenu'] as $submenu) {
                    if ($this->can($submenu['permission'])) {
                        $menuList[$key]['submenu'][] = [
                            'name' => __($submenu['name']),
                            'link' => $submenu['link'],
                        ];
                    }
                }
            }
        }

        return $menuList;
    }
}
