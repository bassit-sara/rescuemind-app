<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SosRequestController;
use App\Http\Controllers\MissingPersonController;
use App\Http\Controllers\HazardReportController;
use App\Http\Controllers\AiCompanionController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\ReliefPointController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\MentalAssessmentController;
use App\Http\Controllers\MoodLogController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\OfficerDashboardController;
use App\Http\Controllers\MentalDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\FamilySafeController;
use App\Http\Controllers\PreparednessController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC ROUTES
// ============================================================
Route::get('/', function () {
    $alerts = \App\Models\Alert::where('is_active', true)->orderByDesc('level')->take(5)->get();
    $reliefPoints = \App\Models\ReliefPoint::where('is_active', true)->count();
    $totalSos = \App\Models\SosRequest::count();
    $resolvedSos = \App\Models\SosRequest::where('status', 'safe')->count();
    return view('welcome', compact('alerts', 'reliefPoints', 'totalSos', 'resolvedSos'));
})->name('home');

Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
Route::get('/alerts/{alert}', [AlertController::class, 'show'])->name('alerts.show');
Route::get('/relief-points', [ReliefPointController::class, 'index'])->name('relief-points.index');
Route::get('/preparedness', [PreparednessController::class, 'index'])->name('preparedness.index');
Route::get('/how-to-use', function () {
    return view('how-to-use');
})->name('how-to-use');

// AI Companion Chat (Public)
Route::post('/ai-companion/chat', [AiCompanionController::class, 'chat'])->name('ai-companion.chat');

// News (public)
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

// Landing Pages for 3 Dimensions
Route::get('/mt1', function () {
    $riskAreas = \App\Models\RiskArea::all();
    return view('pages.mt1', compact('riskAreas'));
})->name('mt1');

Route::get('/mt2', function () {
    return view('pages.mt2');
})->name('mt2');

Route::get('/mt3', function () {
    return view('pages.mt3');
})->name('mt3');

// Privacy Policy
Route::view('/privacy-policy', 'pages.privacy-policy')->name('privacy.policy');

// ============================================================
// PUBLIC SOS
// ============================================================
Route::get('/sos', [App\Http\Controllers\SosRequestController::class, 'create'])->name('sos.create');
Route::post('/sos', [App\Http\Controllers\SosRequestController::class, 'store'])->name('sos.store');
Route::get('/sos/guest-success', [App\Http\Controllers\SosRequestController::class, 'guestSuccess'])->name('sos.guest.success');

// ============================================================
// AUTH ROUTES (user role+)
// ============================================================
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->hasRole('super_admin')) return redirect()->route('super-admin.dashboard');
        if ($user->hasRole('admin')) return redirect()->route('admin.dashboard');
        if ($user->hasRole('officer')) return redirect()->route('officer.dashboard');
        if ($user->hasRole('mental_officer')) return redirect()->route('mental-officer.dashboard');
        if ($user->hasRole('volunteer')) return redirect()->route('volunteer.dashboard');
        return redirect()->route('home');
    })->name('dashboard');



    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // News comments (auth required)
    Route::post('/news/{news}/comment', [NewsController::class, 'comment'])->name('news.comment');
    Route::delete('/news/comment/{comment}', [NewsController::class, 'deleteComment'])->name('news.comment.destroy');

    // SOS (Auth Required)
    Route::get('/sos/my', [SosRequestController::class, 'myRequests'])->name('sos.my');
    Route::get('/sos/{sosRequest}', [SosRequestController::class, 'show'])->name('sos.show');

    // Missing Person
    Route::resource('missing-persons', MissingPersonController::class)->only(['index', 'create', 'store', 'show']);

    // Hazard Report
    Route::resource('hazard-reports', HazardReportController::class)->only(['index', 'create', 'store', 'show']);

    // MT3 8 Modules Routes
    Route::get('/mt3', function () { return view('pages.mt3'); })->name('mt3');
    Route::prefix('mt3')->name('mt3.')->group(function () {
        Route::get('/home-recovery', [App\Http\Controllers\Mt3Controller::class, 'homeRecovery'])->name('home-recovery');
        Route::get('/community-needs', [App\Http\Controllers\Mt3Controller::class, 'communityNeeds'])->name('community-needs');
        Route::get('/recovery-tracking', [App\Http\Controllers\Mt3Controller::class, 'recoveryTracking'])->name('recovery-tracking');
        Route::get('/community-needs-tracking', [App\Http\Controllers\Mt3Controller::class, 'needsTracking'])->name('needs-tracking');
        Route::get('/volunteer', [App\Http\Controllers\Mt3Controller::class, 'volunteer'])->name('volunteer');
        Route::post('/volunteer', [App\Http\Controllers\Mt3Controller::class, 'storeVolunteer'])->name('volunteer.store');
        Route::get('/donation', [App\Http\Controllers\Mt3Controller::class, 'donation'])->name('donation');
        Route::post('/donation', [App\Http\Controllers\Mt3Controller::class, 'storeDonation'])->name('donation.store');
        Route::get('/ai-matching', [App\Http\Controllers\Mt3Controller::class, 'aiMatching'])->name('ai-matching');
        Route::get('/ai-companion', [App\Http\Controllers\Mt3Controller::class, 'aiCompanion'])->name('ai-companion');
        Route::get('/livelihood', [App\Http\Controllers\Mt3Controller::class, 'livelihood'])->name('livelihood');
        Route::post('/livelihood', [App\Http\Controllers\Mt3Controller::class, 'storeLivelihood'])->name('livelihood.store');
        Route::get('/livelihood/tracking', [App\Http\Controllers\Mt3Controller::class, 'livelihoodTracking'])->name('livelihood-tracking');
        Route::get('/analytics', [App\Http\Controllers\Mt3Controller::class, 'analytics'])->name('analytics');
        Route::post('/home-recovery', [App\Http\Controllers\Mt3Controller::class, 'storeHomeRecovery'])->name('home-recovery.store');
        Route::post('/community-needs', [App\Http\Controllers\Mt3Controller::class, 'storeCommunityNeed'])->name('community-needs.store');
        Route::post('/submit-form', [App\Http\Controllers\Mt3Controller::class, 'submitForm'])->name('submit-form');
    });

    // Family Safe Check
    Route::post('/family/safe', [FamilySafeController::class, 'markSafe'])->name('family.safe');
    Route::get('/family/status', [FamilySafeController::class, 'index'])->name('family.status');

    // Mental Health
    Route::prefix('mental')->name('mental.')->group(function () {
        Route::get('/', function () {
            $user = auth()->user();
            $assessments = $user->mentalAssessments()->latest()->take(5)->get();
            $moods = $user->moodLogs()->latest()->take(30)->get();
            $customForms = \App\Models\CustomAssessment::where('is_active', true)->get();
            return view('mental.index', compact('assessments', 'moods', 'customForms'));
        })->name('index');

        Route::get('/assess/{type}', [MentalAssessmentController::class, 'create'])->name('assess.create');
        Route::post('/assess', [MentalAssessmentController::class, 'store'])->name('assess.store');
        Route::get('/result/{mentalAssessment}', [MentalAssessmentController::class, 'show'])->name('assess.show');

        Route::get('/mood', [MoodLogController::class, 'index'])->name('mood.index');
        Route::post('/mood', [MoodLogController::class, 'store'])->name('mood.store');
        Route::patch('/mood/{mood}', [MoodLogController::class, 'update'])->name('mood.update');
        Route::delete('/mood/{mood}', [MoodLogController::class, 'destroy'])->name('mood.destroy');

        Route::get('/journal', [JournalController::class, 'index'])->name('journal.index');
        Route::post('/journal', [JournalController::class, 'store'])->name('journal.store');
        Route::patch('/journal/{journal}', [JournalController::class, 'update'])->name('journal.update');
        Route::delete('/journal/{journal}', [JournalController::class, 'destroy'])->name('journal.destroy');

        Route::resource('appointments', AppointmentController::class)->only(['index', 'create', 'store', 'show']);
        
        Route::get('/articles', [\App\Http\Controllers\MentalArticleController::class, 'index'])->name('articles');
        Route::get('/articles/{mentalArticle}', [\App\Http\Controllers\MentalArticleController::class, 'show'])->name('articles.show');
    });

    Route::post('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['success' => true]);
    })->name('notifications.read');
});

// ============================================================
// VOLUNTEER ROUTES
// ============================================================
Route::middleware(['auth', 'role:volunteer|officer|admin|super_admin'])->prefix('volunteer')->name('volunteer.')->group(function () {
    Route::get('/dashboard', [VolunteerController::class, 'dashboard'])->name('dashboard');
    Route::get('/tasks', [VolunteerController::class, 'tasks'])->name('tasks');
    Route::post('/tasks/{sosRequest}/accept', [VolunteerController::class, 'acceptTask'])->name('tasks.accept');
    Route::post('/tasks/{sosRequest}/report', [VolunteerController::class, 'reportTask'])->name('tasks.report');
    Route::get('/tasks/{sosRequest}/navigate', [VolunteerController::class, 'navigate'])->name('tasks.navigate');
});

// ============================================================
// OFFICER ROUTES
// ============================================================
Route::middleware(['auth', 'role:officer|admin|super_admin'])->prefix('officer')->name('officer.')->group(function () {
    Route::get('/dashboard', [OfficerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/sos', [SosRequestController::class, 'officerIndex'])->name('sos.index');
    Route::post('/sos/{sosRequest}/assign', [SosRequestController::class, 'assign'])->name('sos.assign');
    Route::patch('/sos/{sosRequest}/status', [SosRequestController::class, 'updateStatus'])->name('sos.status');
    Route::get('/sos/{sosRequest}/navigate', [SosRequestController::class, 'navigate'])->name('sos.navigate');
    Route::get('/missing-persons', [MissingPersonController::class, 'officerIndex'])->name('missing.index');
    Route::patch('/missing-persons/{missingPerson}/status', [MissingPersonController::class, 'updateStatus'])->name('missing.status');
    Route::get('/hazard-reports', [HazardReportController::class, 'officerIndex'])->name('hazard.index');
    Route::patch('/hazard-reports/{hazardReport}/verify', [HazardReportController::class, 'verify'])->name('hazard.verify');
});

// ============================================================
// MENTAL OFFICER ROUTES
// ============================================================
Route::middleware(['auth', 'role:mental_officer|admin|super_admin'])->prefix('mental-officer')->name('mental-officer.')->group(function () {
    Route::get('/dashboard', [MentalDashboardController::class, 'index'])->name('dashboard');
    Route::get('/assessments', [MentalAssessmentController::class, 'officerIndex'])->name('assessments');
    Route::get('/assessments/{mentalAssessment}', [MentalAssessmentController::class, 'officerShow'])->name('assessments.show');
    Route::get('/appointments', [AppointmentController::class, 'officerIndex'])->name('appointments');
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
    Route::resource('articles', \App\Http\Controllers\MentalArticleController::class)->parameters(['articles' => 'mentalArticle'])->except(['index', 'show']);
});

// ============================================================
// ADMIN ROUTES
// ============================================================
Route::middleware(['auth', 'role:admin|super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('relief-points', ReliefPointController::class)->except(['show']);
    Route::resource('resources', ResourceController::class)->except(['show']);
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::patch('/users/{user}/role', [AdminDashboardController::class, 'updateRole'])->name('users.role');
    Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/alerts', [AlertController::class, 'adminIndex'])->name('alerts.index');
    Route::get('/alerts/create', [AlertController::class, 'create'])->name('alerts.create');
    Route::post('/alerts', [AlertController::class, 'store'])->name('alerts.store');
    Route::get('/alerts/{alert}/edit', [AlertController::class, 'edit'])->name('alerts.edit');
    Route::patch('/alerts/{alert}', [AlertController::class, 'update'])->name('alerts.update');
    Route::delete('/alerts/{alert}', [AlertController::class, 'destroy'])->name('alerts.destroy');
    // News admin CRUD
    Route::get('/news', [NewsController::class, 'adminIndex'])->name('news.index');
    Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
    Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{news}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');
});

// ============================================================
// SUPER ADMIN ROUTES
// ============================================================
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('super-admin.dashboard');
    })->name('index');
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [SuperAdminDashboardController::class, 'analytics'])->name('analytics');
    Route::get('/users', [SuperAdminDashboardController::class, 'users'])->name('users');
    Route::patch('/users/{user}/role', [SuperAdminDashboardController::class, 'updateRole'])->name('users.role');
    Route::delete('/users/{user}', [SuperAdminDashboardController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/system-logs', [SuperAdminDashboardController::class, 'systemLogs'])->name('system-logs');
    Route::get('/shelter-monitoring', [SuperAdminDashboardController::class, 'shelterMonitoring'])->name('shelter');
    Route::get('/resource-monitoring', [SuperAdminDashboardController::class, 'resourceMonitoring'])->name('resources');
    
    // System Settings
    Route::get('/settings', [\App\Http\Controllers\SuperAdminSettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\SuperAdminSettingsController::class, 'update'])->name('settings.update');
    
    // Super Admin MT3 Management
    Route::prefix('mt3')->name('mt3.')->group(function () {
        Route::get('/home-recovery', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'homeRecovery'])->name('home-recovery');
        Route::patch('/home-recovery/{id}', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'updateHomeRecovery'])->name('home-recovery.update');
        Route::delete('/home-recovery/{id}', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'destroyHomeRecovery'])->name('home-recovery.destroy');
        Route::get('/community-needs', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'communityNeeds'])->name('community-needs');
        Route::patch('/community-needs/{id}', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'updateCommunityNeed'])->name('community-needs.update');
        Route::delete('/community-needs/{id}', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'destroyCommunityNeed'])->name('community-needs.destroy');
        Route::get('/volunteer', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'volunteer'])->name('volunteer');
        Route::patch('/volunteer/{volunteer}/approval', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'updateVolunteerApproval'])->name('volunteer.approval');
        Route::get('/donation', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'donation'])->name('donation');
        Route::get('/livelihood', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'livelihood'])->name('livelihood');
        Route::patch('/livelihood/{id}', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'updateLivelihood'])->name('livelihood.update');
        Route::delete('/livelihood/{id}', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'destroyLivelihood'])->name('livelihood.destroy');
        Route::get('/ai-matching', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'aiMatching'])->name('ai-matching');
        Route::get('/analytics', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'analytics'])->name('analytics');
        
        // Mental Recovery routes
        Route::get('/mental-recovery', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'mentalRecovery'])->name('mental-recovery');
        Route::post('/mental-recovery/assessments', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'storeMentalAssessment'])->name('mental-recovery.assessments.store');
        Route::patch('/mental-recovery/assessments/{id}', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'updateMentalAssessment'])->name('mental-recovery.assessments.update');
        Route::delete('/mental-recovery/assessments/{id}', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'destroyMentalAssessment'])->name('mental-recovery.assessments.destroy');

        // Dynamic Assessment Form Builder
        Route::get('/mental-forms', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'mentalForms'])->name('mental-forms');
        Route::post('/mental-forms', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'storeMentalForm']);
        Route::put('/mental-forms/{id}', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'updateMentalForm']);
        Route::delete('/mental-forms/{id}', [\App\Http\Controllers\SuperAdminMt3Controller::class, 'destroyMentalForm']);
    });
});

// ============================================================
// SOCIAL LOGIN ROUTES
// ============================================================
Route::get('/auth/{provider}/redirect', [\App\Http\Controllers\Auth\SocialLoginController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [\App\Http\Controllers\Auth\SocialLoginController::class, 'callback'])->name('social.callback');

// ============================================================
// LINE NOTIFY
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/line-notify/redirect', [\App\Http\Controllers\LineNotifyController::class, 'redirect'])->name('line-notify.connect');
    Route::get('/line-notify/callback', [\App\Http\Controllers\LineNotifyController::class, 'callback'])->name('line-notify.callback');
    Route::post('/line-notify/disconnect', [\App\Http\Controllers\LineNotifyController::class, 'disconnect'])->name('line-notify.disconnect');

    // Shelter Bookings (User)
    Route::get('/bookings', [\App\Http\Controllers\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/relief-points/{reliefPoint}/book', [\App\Http\Controllers\BookingController::class, 'create'])->name('bookings.create');
    Route::post('/relief-points/{reliefPoint}/book', [\App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{booking}/route-ai', [\App\Http\Controllers\BookingController::class, 'evaluateRoute'])->name('bookings.route-ai');

    // Admin Bookings Management
    Route::middleware(['role:admin|super_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/bookings', [\App\Http\Controllers\AdminBookingController::class, 'index'])->name('bookings.index');
        Route::patch('/bookings/{booking}', [\App\Http\Controllers\AdminBookingController::class, 'update'])->name('bookings.update');
    });

    // Tracking
    Route::post('/api/tracking/location', [\App\Http\Controllers\TrackingController::class, 'updateLocation'])->name('tracking.location');
});

// ============================================================
// LINE WEBHOOK
// ============================================================
Route::post('/webhook/line', [\App\Http\Controllers\LineWebhookController::class, 'handle']);

require __DIR__ . '/auth.php';
