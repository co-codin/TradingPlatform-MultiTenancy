<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Http\Request;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class BaseColumnPermissionValidator
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var User
     */
    private User $user;

    /**
     * @var Permission|null
     */
    private ?Permission $permission;

    public function __construct()
    {
        $this->request = request();
        $this->permission = $this->request
            ->user()
            ?->permissions()
            ->where('name', $this->getBasePermissionName())
            ->first();

        $this->boot();
    }

    /**
     * Get request.
     *
     * @return Request|null
     */
    final public function getRequest(): ?Request
    {
        return $this->request;
    }

    /**
     * Get permission.
     *
     * @return Permission|null
     */
    final public function getPermission(): ?Permission
    {
        return $this->permission;
    }

    /**
     * Set request.
     *
     * @param  Request  $request
     * @return $this
     */
    final public function setRequest(Request $request): BaseColumnPermissionValidator
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Set permission.
     *
     * @param  Permission  $permission
     * @return $this
     */
    final public function setPermission(Permission $permission): BaseColumnPermissionValidator
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * Boot.
     *
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    final protected function boot(): void
    {
        if ($this->getPermission()?->columns()->count() == 0) {
            return;
        }

        $fields = $this->getRequest()?->get('fields');

        $names = match (true) {
            ! isset($fields[$this->getRequestFieldName()]) => [],
            is_array($fields[$this->getRequestFieldName()]) => $fields[$this->getRequestFieldName()],
            is_string($fields[$this->getRequestFieldName()]) => array_map(
                'trim', explode(',', $fields[$this->getRequestFieldName()])
            ),
            default => [],
        };

        $columns = $this->getPermission()->columns()
            ->when(
                $names,
                fn ($q) => $q->whereIn('name', $names),
            )
            ->pluck('name');

        if ($columns->isNotEmpty()) {
            $this->request->merge([
                'fields' => [
                    $this->getRequestFieldName() => $columns->implode(','),
                ],
            ]);
        }
    }

     /**
      * Get request field name.
      *
      * @return string
      */
     abstract protected function getRequestFieldName(): string;

    /**
     * Get base permission name.
     *
     * @return string
     */
    abstract protected function getBasePermissionName(): string;
}
