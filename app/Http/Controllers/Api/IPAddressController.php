<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IpAddressRequest;
use App\Http\Resources\Api\IpAddressResource;
use App\Models\IpAddress;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class IPAddressController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $data = IpAddress::query()
            ->select('id', 'address', 'label', 'comment')
            ->latest()
            ->paginate(100);

        return IpAddressResource::collection($data);
    }

    public function store(IpAddressRequest $request): Response
    {
        IpAddress::create($request->validated());

        return response()->json([
            'message' => 'IP address created successfully.',
        ], Response::HTTP_CREATED);
    }

    public function update(IpAddressRequest $request, IpAddress $ipAddress): Response
    {
        $ipAddress->update($request->validated());

        return response()->json([
            'message' => 'IP address updated successfully.',
        ], Response::HTTP_OK);
    }

    public function destroy(IpAddress $ipAddress): Response
    {
        $ipAddress->delete();

        return response()->json([
            'message' => 'IP address deleted successfully.',
        ], Response::HTTP_OK);
    }
}
