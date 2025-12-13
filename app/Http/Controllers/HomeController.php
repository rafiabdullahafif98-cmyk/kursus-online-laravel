<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $courses = Course::where('is_published', true)
            ->with('category', 'pengajar')
            ->latest()
            ->take(6)
            ->get();
            
        return view('welcome', compact('courses'));
    }
}