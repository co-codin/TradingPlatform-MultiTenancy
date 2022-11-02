<?php

namespace Modules\Token\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Token\Models\Token;
use Modules\Token\Repositories\TokenRepository;
use Modules\Token\Services\TokenStorage;

class TokenController extends Controller
{
    public function __construct(
        protected TokenRepository $tokenRepository,
        protected TokenStorage $tokenStorage
    ) {
        $this->authorizeResource(Token::class, 'token');
    }

    public function all()
    {
        
    }

    public function index()
    {

    }

    public function store()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
