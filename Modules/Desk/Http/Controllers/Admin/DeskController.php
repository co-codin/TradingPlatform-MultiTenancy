<?php

namespace Modules\Desk\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Desk\Dto\DeskDto;
use Modules\Desk\Http\Requests\DeskCreateRequest;
use Modules\Desk\Http\Requests\DeskUpdateRequest;
use Modules\Desk\Http\Resources\DeskResource;
use Modules\Desk\Models\Desk;
use Modules\Desk\Repositories\DeskRepository;
use Modules\Desk\Services\DeskStorage;

class DeskController extends Controller
{
    public function __construct(
        protected DeskRepository $deskRepository,
        protected DeskStorage $deskStorage
    ) {
        $this->authorizeResource(Desk::class, 'desk');
    }

    public function all()
    {
        $this->authorize('viewAny');

        $desks = $this->deskRepository->all();

        return DeskResource::collection($desks);
    }

    public function index()
    {
        $desks = $this->deskRepository->jsonPaginate();

        return DeskResource::collection($desks);
    }

    public function show(int $desk)
    {
        $desk = $this->deskRepository->find($desk);

        return new DeskResource($desk);
    }

    public function store(DeskCreateRequest $request)
    {
        $deskDto = DeskDto::fromFormRequest($request);

        $desk = $this->deskStorage->store($deskDto);

        return new DeskResource($desk);
    }

    public function update(int $desk, DeskUpdateRequest $request)
    {
        $desk = $this->deskRepository->find($desk);

        $desk = $this->deskStorage->update(
            $desk, DeskDto::fromFormRequest($request)
        );

        return new DeskResource($desk);
    }

    public function destroy(int $desk)
    {
        $desk = $this->deskRepository->find($desk);

        $this->deskStorage->delete($desk);

        return response()->noContent();
    }
}
