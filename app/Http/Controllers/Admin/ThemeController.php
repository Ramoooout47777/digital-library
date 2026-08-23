<?php
// app/Http/Controllers/Admin/ThemeController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ThemeController extends Controller
{
    public function switchTheme(Request $request)
    {
        $theme = $request->input('theme', 'dark');
        
        if (!in_array($theme, ['dark', 'light'])) {
            $theme = 'dark';
        }
        
        Session::put('admin_theme', $theme);
        
        return response()->json(['success' => true, 'theme' => $theme]);
    }
}