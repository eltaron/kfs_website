<?php

use App\Http\Controllers\AboutPageController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\LandmarkController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\EmergencyReportController;
use App\Http\Controllers\ErpController;
use App\Http\Controllers\EstidamaController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GovernorateController;
use App\Http\Controllers\HayahKarimaController;
use App\Http\Controllers\InvestmentContactController;
use App\Http\Controllers\LoginCitizenController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\RegisterCitizenController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ServiceSubmissionController;
use App\Http\Controllers\ServiceSurveyController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\InvestmentPlanController;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OfficialsController;
use App\Http\Controllers\GisPortalController;

use Illuminate\Http\Request;
use App\Models\ServiceSubmission;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/wait', [HomeController::class, 'wait'])->name('wait');
Route::get('/interactive-map', [MapController::class, 'index'])->name('governorate.map');

Route::get('/search', SearchController::class)->name('search');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service:id}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/investments', [InvestmentController::class, 'index'])->name('investments.index');
Route::get('/investments/{investment:id}', [InvestmentController::class, 'show'])->name('investments.show');

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/landmarks', [LandmarkController::class, 'index'])->name('landmarks.index');
Route::get('/landmarks/{landmark:id}', [LandmarkController::class, 'show'])->name('landmarks.show');

Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');

Route::get('/suggestions/create', [SuggestionController::class, 'create'])->name('suggestions.create');
Route::post('/suggestions', [SuggestionController::class, 'store'])->name('suggestions.store');

Route::get('/directory', [DirectoryController::class, 'index'])->name('directory.index');

Route::get('/survey/service-center', [ServiceSurveyController::class, 'create'])->name('surveys.service.create');
Route::post('/survey/service-center', [ServiceSurveyController::class, 'store'])->name('surveys.service.store');

Route::get('/register/citizen', [RegisterCitizenController::class, 'create'])->name('register.citizen');
Route::post('/register/citizen', [RegisterCitizenController::class, 'store'])->name('register.citizen.store');

Route::get('/login', [LoginCitizenController::class, 'create'])->name('login');
Route::post('/login', [LoginCitizenController::class, 'store']);

Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');

Route::post('/logout', [LoginCitizenController::class, 'destroy'])->name('logout');
Route::get('/about/governor-message', [AboutPageController::class, 'governorMessage'])->name('about.governor');

Route::get('/estidama', [EstidamaController::class, 'index'])->name('estidama.index');
Route::get('/estidama/programs', [EstidamaController::class, 'programs'])->name('estidama.programs');

Route::get('/eis_estimation', [EmergencyReportController::class, 'create'])->name('emergency.create');
Route::post('/emergency-report', [EmergencyReportController::class, 'store'])->name('emergency.store');
Route::get('/investment-plans', [InvestmentPlanController::class, 'index'])->name('investment.plans.index');
Route::get('/officials/{role}', [OfficialsController::class, 'show'])->name('officials.show');

Route::get('/about-us', [GovernorateController::class, 'about'])->name('about');
Route::get('/hayah-karima-projects', [HayahKarimaController::class, 'index'])->name('projects.hay-karima');

Route::get('/investments/contact/message', [InvestmentContactController::class, 'create'])->name('investment.contact');
Route::post('/investments/contact/message', [InvestmentContactController::class, 'store'])->name('investment.contact.store');

Route::prefix('gis-portal')->group(function () {
    Route::get('/', [GisPortalController::class, 'index'])->name('gis.index');
    Route::get('/service/{slug}', [GisPortalController::class, 'showService'])
        ->name('gis.service.show');
    Route::get('/track-request', [GisPortalController::class, 'track'])
        ->name('gis.tracking');
    Route::middleware(['auth'])->group(function () {
        Route::get('/apply/{slug}', [GisPortalController::class, 'createApplication'])
            ->name('gis.apply.start');
        Route::post('/apply/{slug}/submit', [GisPortalController::class, 'storeApplication'])
            ->name('gis.apply.submit');
        Route::get('/application-success/{id}', [GisPortalController::class, 'success'])
            ->name('gis.apply.success');
    });
    Route::prefix('api')->group(function () {
        Route::get('/markaz/{markazId}/units', [GisPortalController::class, 'getLocalUnits']);
        Route::get('/unit/{unitId}/villages', [GisPortalController::class, 'getVillages']);
    });
});

Route::post('/efinance/gis/callback', [GisPortalController::class, 'paymentCallback'])
    ->name('efinance.gis.callback');
Route::middleware(['auth'])->group(function () {
    Route::get('/citizen/submissions/{id}', [DashboardController::class, 'show'])
        ->name('citizen.submissions.show');
    Route::get('/erp-governorate', [ErpController::class, 'index'])->name('employee.erp.index');
    Route::post('/services/{service}/submit', [ServiceSubmissionController::class, 'store'])
        ->name('services.submit');
    Route::get('/services/submitted/success', function () {
        return view('services.success');
    })->name('services.success');
    Route::get('/receipts/{transaction}/print', [ReceiptController::class, 'print'])->name('receipt.print');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('citizen.dashboard');
    Route::get('/estidama/apply/{program:id}', [EstidamaController::class, 'apply'])->name('estidama.apply');
    Route::post('/estidama/apply/{program:id}', [EstidamaController::class, 'storeApplication'])->name('estidama.storeApplication');
});
Route::get('/phpinfo-test', function () {
    phpinfo();
});

Route::get('/gis/print/{record}', function (App\Models\GisSubmission $record) {
    return view('gis.print-submission', compact('record'));
})->name('gis.print')->middleware(['auth']);

Route::get('/gis/removal-print/{record}', function (App\Models\RemovalOrder $record) {
    return view('gis.print-removal-decision', compact('record'));
})->name('gis.removal.print')->middleware(['auth']);

Route::post('/efinance/callback', function (Request $request) {
    $status = $request->input('ResponseCode');
    $reqNum = $request->input('SenderRequestNumber');
    $submission = ServiceSubmission::find($reqNum);
    if ($submission && $status === '000') {
        $submission->update(['status' => 'paid']);
        return response('000');
    }

    return response('FAILED');
})->name('efinance.callback')->withoutMiddleware([
    \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class
]);

Route::get('/phpinfo', function () {
    phpinfo();
});
