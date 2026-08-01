<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\View;

trait RespondsWithAdminViews
{
    protected function viewOrJson(string $view, array $data = [])
    {
        if (View::exists($view)) {
            return view($view, $data);
        }

        return response()->json($data);
    }

    protected function stored(string $route, string $message = 'Saved successfully.')
    {
        return redirect()->route($route)->with('success', $message);
    }

    protected function deleted(string $message = 'Deleted successfully.'): JsonResponse
    {
        return response()->json(['message' => $message]);
    }
}
