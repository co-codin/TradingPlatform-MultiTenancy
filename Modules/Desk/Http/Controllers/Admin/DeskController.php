<?php

namespace Modules\Desk\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Desk\Repositories\DeskRepository;
use Modules\Desk\Services\DeskStorage;

class DeskController extends Controller
{
    public function __construct(
        protected DeskRepository $deskRepository,
        protected DeskStorage $deskStorage
    ) {}

    public function index()
    {

    }

    public function store()
    {
        
    }
}
