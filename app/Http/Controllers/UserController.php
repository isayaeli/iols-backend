<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Get all users with operator role
     */
    public function operators(): JsonResponse
    {
        $operators = User::where('role', 'operator')
            ->select('id', 'name', 'email') // only return necessary fields
            ->get();

        return response()->json($operators);
    }
}
