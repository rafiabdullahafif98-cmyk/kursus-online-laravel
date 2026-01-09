<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course; 

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        $courses = Course::where('is_published', true)
            ->with(['category', 'pengajar']) 
            ->latest()
            ->take(6)
            ->get();
            
        return view('home', compact('courses'));
    }
}