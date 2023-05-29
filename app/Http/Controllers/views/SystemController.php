<?php

namespace App\Http\Controllers\views;

use Inertia\Inertia;
use App\Http\Resources\SystemCollection;
use App\Http\Resources\SystemResource;

use App\Models\System;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class SystemController extends Controller
{
    private $perPage = 25;

    public function index(Request $request)
    {
        $column = $request->input('sort') ?? 'distance';
        $direction = $request->input('order') ?? 'asc';
        return Inertia::render('Systems/Index', [
            'systems' => new SystemCollection(System::orderBy($column, $direction)->paginate($this->perPage, ['*'], 'p')),
        ]);
    }

    public function show(string $name)
    {
        return Inertia::render('Systems/Show', [
            'systems' => new SystemResource(System::where('name', $name)->first()),
        ]);
    }
}
