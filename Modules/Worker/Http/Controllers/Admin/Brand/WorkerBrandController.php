<?php

namespace Modules\Worker\Http\Controllers\Admin\Brand;

use App\Http\Controllers\Controller;
use Modules\Worker\Http\Requests\Brand\WorkerBrandUpdateRequest;
use Modules\Worker\Repositories\WorkerRepository;

class WorkerBrandController extends Controller
{
    public function __construct(
        protected WorkerRepository $workerRepository
    ) {}

    public function update(WorkerBrandUpdateRequest $request, int $worker)
    {
        $worker = $this->workerRepository->find($worker);

        $this->authorize('update', $worker);

        $ids = $request->get('brands')->pluck('id')->filter()->unique();

        $worker->desks()->sync($ids);
    }
}
