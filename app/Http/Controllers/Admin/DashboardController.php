<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InquiryStatus;
use App\Enums\MailStatus;
use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Project;
use App\Models\Service;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'newInquiries' => Inquiry::query()->where('status', InquiryStatus::New)->count(),
            'failedMail' => Inquiry::query()->where('mail_status', MailStatus::Failed)->count(),
            'publishedProjects' => Project::query()->published()->actual()->count(),
            'publishedConcepts' => Project::query()->published()->concept()->count(),
            'publishedServices' => Service::query()->published()->count(),
            'latestInquiries' => Inquiry::query()->latest()->limit(5)->get(),
        ]);
    }
}
