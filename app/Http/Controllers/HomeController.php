<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Redirect to the main home route which uses TohfishController
        return redirect()->route('home');
    }
}