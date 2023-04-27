<?php

namespace App\Http\Controllers;

use App\Services\TestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class WorkflowController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Bpmn/BpmnViewer', ['csrf_token' => csrf_token(), 'xml' => Cache::get('xml')]);
    }

    public function test(Request $request, TestService $testService)
    {
        $request->validate([
            'xml' => 'required'
        ]);

        return $testService->setXml($request->post('xml'))->handle2();
    }
}
