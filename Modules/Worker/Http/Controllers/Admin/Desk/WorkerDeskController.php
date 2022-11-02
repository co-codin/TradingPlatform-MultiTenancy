<?php

namespace Modules\Worker\Http\Controllers\Admin\Desk;

use App\Http\Controllers\Controller;
use Modules\Worker\Http\Requests\Desk\WorkerDeskUpdateRequest;
use Modules\Worker\Repositories\WorkerRepository;

class WorkerDeskController extends Controller
{
    public function __construct(
        protected WorkerRepository $workerRepository
    ) {}

    public function update(WorkerDeskUpdateRequest $request, int $worker)
    {
        $worker = $this->workerRepository->find($worker);

        $this->authorize('update', $worker);

        $ids = $request->get('desks')->pluck('id')->filter()->unique();

        $worker->desks()->sync($ids);
    }
}
