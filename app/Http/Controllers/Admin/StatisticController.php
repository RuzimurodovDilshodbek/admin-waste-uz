<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Http\Requests\UpdateStatisticRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Statistic;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class StatisticController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('statistic_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statistics = Statistic::all();

        return view('admin.statistics.index', compact('statistics'));
    }

    public function edit(Statistic $statistic)
    {
        abort_if(Gate::denies('statistic_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $locales = config('app.locales');

        return view('admin.statistics.edit', compact('statistic','locales'));
    }

    public function update(UpdateStatisticRequest $request, Statistic $statistic)
    {
        abort_if(Gate::denies('statistic_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statistic->update($request->all());

        return redirect()->route('admin.statistics.index');
    }
}
