<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\FaqRepository;
use Mail;
use App\Mail\ContactUs;

class HomeController extends Controller
{
    
    function __construct(UserRepository $userRepo, FaqRepository $faqRepo)
    {
        $this->userRepo = $userRepo;
        $this->faqRepo  = $faqRepo;
    }

    public function commingSoon()
    {
        /*$appointment = \App\Models\Appointment::find(2);
        echo "<pre>";
        print_r($appointment->getCostCalculationAttribute());
        echo "</pre>";
        exit;*/
        
                
        /*$user = \Auth::guard('coach')->user();
        echo "<pre>";
        print_r($user->sendEmailVerificationNotification());
        echo "</pre>";
        exit;*/
        /*$appointment = \App\Models\Appointment::find(59);
        echo "<pre>";
        print_r($appointment->getCostCalculationAttribute());
        echo "</pre>";
        exit;*/
        return view('commingsoon');
    }

    public function commingSoonImpressum()
    {
        return view('commingsoonimpressum');
    }

    public function index()
    {
    	$page_title  = 'Himmlish BERATEN';
        $top_coaches = $this->userRepo->getTopCoaches(10);
        /*echo "<pre>";
        print_r($top_coaches->toArray());
        echo "</pre>";
        exit;*/
    	return view('index', compact('page_title', 'top_coaches'));
    }

    public function aboutUs()
    {
        $page_title  = 'About us';
        return view('aboutus', compact('page_title'));
    }

    public function contactUs()
    {
        $page_title  = 'Contact us';
        return view('contact', compact('page_title'));
    }

    public function postContactUs(Request $request)
    {
        Mail::to(env('ADMIN_EMAIL', 'kontakt@himmlischberaten.de'))->send(new ContactUs($request->name, $request->email, $request->description));
        return 'Request submitted';
    }

    public function contactThankYou()
    {
        $page_title = 'Thank You for contacting';
        return view('contact-thankyou', compact('page_title'));
    }

    public function faq()
    {
        $page_title  = 'FAQ';
        $user_faqs   = $this->faqRepo->getFaqs('user');
        $coach_faqs  = $this->faqRepo->getFaqs('coach');
        return view('faq', compact('page_title', 'user_faqs', 'coach_faqs'));
    }

    public function imprint()
    {
        $page_title  = 'Imprint';
        return view('imprint', compact('page_title'));
    }

    public function organisation()
    {
        $page_title  = 'Organisation';
        return view('organisation', compact('page_title'));
    }

    public function resetPassword()
    {
        $page_title = 'Reset password';
        return view('reset_password', compact('page_title'));
    }

    public function faithPrinciple()
    {
        $page_title = 'Principles of faith';
        return view('faith_principles', compact('page_title'));
    }

    public function agb()
    {
        $page_title = 'AGB';
        return view('agb', compact('page_title'));
    }

    public function dataProtection()
    {
        $page_title = 'Data Protection';
        return view('data-protection', compact('page_title'));
    }

    public function newsletter(Request $request)
    {
        $email = isset($request->newsletter_email)?$request->newsletter_email:'';
        $page_title = 'Newsletter';
        return view('newsletter', compact('page_title', 'email'));
    }

}
