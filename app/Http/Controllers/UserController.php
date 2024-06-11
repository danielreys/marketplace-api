<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController
{
    public function show(): JsonResponse {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated.'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        return response()->json($user);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        try {
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'sometimes|string|min:8',
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors();

            $response = [];

            if ($errors->has('name')) {
                $response['name'] = $errors->first('name');
            }

            if ($errors->has('email')) {
                $response['email'] = $errors->first('email');
            }

            if ($errors->has('password')) {
                $response['password'] = $errors->first('password');
            }

            return response()->json(['errors' => $response], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json($user);
    }
}
