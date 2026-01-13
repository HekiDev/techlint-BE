<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuditResource;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuditController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $data = AuditLog::query()
            ->with('user:id,name')
            ->latest()
            ->paginate(100);

        return AuditResource::collection($data);
    }
}
