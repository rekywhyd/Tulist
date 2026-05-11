<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Menampilkan halaman bantuan.
     */
    public function help()
    {
        // Pastikan file ini ada: resources/views/pages/help.blade.php
        return view('help');
    }

    public function privacy()
    {
        // Pastikan file ini ada: resources/views/pages/privacy.blade.php
        return view('privacy');
    }

    /**
     * Menampilkan halaman 'About'.
     */
    public function about()
    {
        // Ini akan mencari file: resources/views/about.blade.php
        return view('about');
    }

    /**
     * Menampilkan halaman 'Contact Us'.
     */
    public function contact()
    {
        // Ini akan mencari file: resources/views/contact.blade.php
        return view('contact');
    }

    /**
     * Menampilkan halaman 'Home'.
     */
    public function home()
    {
        return view('home');
    }

    /**
     * Menampilkan halaman 'Schedule'.
     */
    public function schedule()
    {
        return view('schedule');
    }

    /**
     * Menampilkan halaman 'Employees'.
     */
    public function employees()
    {
        return view('employees');
    }
}
