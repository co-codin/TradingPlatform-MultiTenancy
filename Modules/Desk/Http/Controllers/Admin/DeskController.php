<?php

namespace Modules\Desk\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Desk\Http\Resources\DeskResource;
use Modules\Desk\Repositories\DeskRepository;
use Modules\Desk\Services\DeskStorage;

class DeskController extends Controller
{
    public function __construct(
        protected DeskRepository $deskRepository,
        protected DeskStorage $deskStorage
    ) {}

    public function all()
    {
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

    public function store()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
