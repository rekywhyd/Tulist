<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\HelpRequestMail;
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

    /**
     * Memproses form submit bantuan.
     */
    public function submitHelp(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'problem' => 'required|string',
        ]);

        // Kirim email ke admin (menggunakan config mail.from.address)
        $adminEmail = config('mail.from.address');
        
        Mail::to($adminEmail)->send(new HelpRequestMail(
            $request->name,
            auth()->user()->email,
            $request->problem
        ));

        return redirect()->back()->with('success', 'Your help request has been submitted successfully.');
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
     * Menampilkan halaman 'Workspace'.
     */
    public function workspace()
    {
        return view('workspace');
    }
}
