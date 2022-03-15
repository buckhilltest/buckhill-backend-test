<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function userProfile(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    public function userListing(): JsonResponse
    {
        return response()->json(User::all());
    }

    public function userEdit(UpdateUser $request, $uuid): JsonResponse
    {
        $data = $request->validated();

        $user = User::where('uuid', $uuid)->firstOrFail();

        if ($user->update($data)) {
            return response()->json($user->fresh());
        }

        return response()->json([
            'message' => 'Error deleting user'
        ], 500);
    }

    public function userDelete($uuid): JsonResponse
    {
        $user = User::where('uuid', $uuid)->firstOrFail();

        if ($user->email === 'admin@buckhill.co.uk') {
            return response()->json([
                'message' => 'You can not delete the master user'
            ], 400);
        }

        if ($user->delete()) {
            return response()->json([
                'message' => 'User deleted successfully'
            ]);
        }

        return response()->json([
            'message' => 'Error deleting user'
        ], 500);
    }
}
