<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GiraffeRequest;

class GiraffeRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'giraffe_id' => 'required|exists:giraffes,id',
        ]);

        $exists = GiraffeRequest::where('user_id', auth()->id())
            ->where('giraffe_id', $request->giraffe_id)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'You already have a pending request for this giraffe',
            ], 409);
        }

        $giraffeRequest = GiraffeRequest::create([
            'user_id' => auth()->id(),
            'giraffe_id' => $request->giraffe_id,
        ]);

        return response()->json([
            'message' => 'Request submitted successfully',
            'data' => $giraffeRequest
        ], 201);
    }

    public function index()
    {
        $requests = GiraffeRequest::with('giraffe')
            ->where('user_id', auth()->id())
            ->get();

        return response()->json($requests);
    }
}
