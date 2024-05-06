<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
}) -> name('index');

Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'showDashboard'])
    -> middleware(['auth'])
    -> name('show_dashboard');

Route::post('launcher-register', [\App\Http\Controllers\DashboardController::class, 'processLauncherRegisterForm'])
    -> middleware(['auth'])
    -> name('process_launcher_register');

Route::get('users', [App\Http\Controllers\UsersController::class, 'showUsers'])
    -> middleware(['auth'])
    -> name('show_users');

Route::post('user-search', [\App\Http\Controllers\UsersController::class, 'processUserSearch'])
    -> middleware(['auth'])
    -> name('process_user_search');

Route::post('user-role', [App\Http\Controllers\UsersController::class, 'processUserRoleForm'])
    -> middleware(['auth'])
    -> name('process_user_role');

Route::post('user-ban', [App\Http\Controllers\UsersController::class, 'processUserBanForm'])
    -> middleware(['auth'])
    -> name('process_user_ban');

Route::post('whitelist-request', [\App\Http\Controllers\DashboardController::class, 'processWhitelistRequest'])
    -> middleware(['auth'])
    -> name('process_whitelist_request');

Route::post('whitelist-request-search', [\App\Http\Controllers\WhitelistController::class, 'processWhitelistSearch'])
    -> middleware(['auth'])
    -> name('process_whitelist_search');

Route::get('whitelist-requests', [\App\Http\Controllers\WhitelistController::class, 'showWhitelistRequests'])
    -> middleware(['auth'])
    -> name('show_whitelist_requests');

Route::post('whitelist', [\App\Http\Controllers\WhitelistController::class, 'processWhitelist'])
    -> middleware(['auth'])
    -> name('process_whitelist');

Route::get('whitelist-questions', [\App\Http\Controllers\WhitelistController::class, 'showWhitelistQuestions'])
    -> middleware(['auth'])
    -> name('show_whitelist_questions');

Route::post('whitelist-question', [\App\Http\Controllers\WhitelistController::class, 'processWhitelistQuestion'])
    -> middleware(['auth'])
    -> name('process_whitelist_question');

Route::post('whitelist-question-create', [\App\Http\Controllers\WhitelistController::class, 'processWhitelistQuestionCreation'])
    -> middleware(['auth'])
    -> name('process_whitelist_question_creation');

Route::get('faq', [\App\Http\Controllers\FaqController::class, 'showFaq'])
    -> middleware(['auth'])
    -> name('show_faq');

Route::post('faq-create', [\App\Http\Controllers\FaqController::class, 'processFAQCreation'])
    -> middleware(['auth'])
    -> name('process_faq_creation');

Route::get('streamers', [\App\Http\Controllers\StreamersController::class, 'showStreamers'])
    -> middleware(['auth'])
    -> name('show_streamers');

Route::post('streamer-create', [\App\Http\Controllers\StreamersController::class, 'processStreamerCreation'])
    -> middleware(['auth'])
    -> name('process_streamer_creation');

Route::post('streamer-edit', [\App\Http\Controllers\StreamersController::class, 'processStreamerEdit'])
    -> middleware(['auth'])
    -> name('process_streamer_edit');

Route::post('streamer-delete', [\App\Http\Controllers\StreamersController::class, 'processStreamerDelete'])
    -> middleware(['auth'])
    -> name('process_streamer_delete');

Route::get('staff', [\App\Http\Controllers\StaffController::class, 'showStaff'])
    -> middleware(['auth'])
    -> name('show_staff');

Route::post('staff-create', [\App\Http\Controllers\StaffController::class, 'processStaffCreation'])
    -> middleware(['auth'])
    -> name('process_staff_creation');

Route::post('staff-edit', [\App\Http\Controllers\StaffController::class, 'processStaffEdit'])
    -> middleware(['auth'])
    -> name('process_staff_edit');

Route::post('staff-delete', [\App\Http\Controllers\StaffController::class, 'processStaffDelete'])
    -> middleware(['auth'])
    -> name('process_staff_delete');

Route::get('sponsors', [\App\Http\Controllers\SponsorsController::class, 'showSponsors'])
    -> middleware(['auth'])
    -> name('show_sponsors');

Route::post('sponsor-create', [\App\Http\Controllers\SponsorsController::class, 'processSponsorCreation'])
    -> middleware(['auth'])
    -> name('process_sponsor_creation');

Route::post('sponsor-edit', [\App\Http\Controllers\SponsorsController::class, 'processSponsorEdit'])
    -> middleware(['auth'])
    -> name('process_sponsor_edit');

Route::post('sponsor-delete', [\App\Http\Controllers\SponsorsController::class, 'processSponsorDelete'])
    -> middleware(['auth'])
    -> name('process_sponsor_delete');

Route::get('job-application', [\App\Http\Controllers\JobApplicationController::class, 'showJobApplications'])
    -> middleware(['auth'])
    -> name('show_job_applications');

Route::post('job-application-create', [\App\Http\Controllers\JobApplicationController::class, 'processJobApplicationCreation'])
    -> middleware(['auth'])
    -> name('process_job_application_creation');

Route::post('job-application-job-create', [\App\Http\Controllers\JobApplicationController::class, 'processJobApplicationJobCreation'])
    -> middleware(['auth'])
    -> name('process_job_application_job_creation');

Route::get('job-questions', [\App\Http\Controllers\JobApplicationController::class, 'showJobQuestions'])
    -> middleware(['auth'])
    -> name('show_job_questions');

Route::post('job-question-create', [\App\Http\Controllers\JobApplicationController::class, 'processJobQuestionCreation'])
    -> middleware(['auth'])
    -> name('process_job_question_creation');

Route::post('job-question-process', [\App\Http\Controllers\JobApplicationController::class, 'processJobQuestion'])
    -> middleware(['auth'])
    -> name('process_job_question');

Route::post('job-application-edit', [\App\Http\Controllers\JobApplicationController::class, 'processJobApplicationEdit'])
    -> middleware(['auth'])
    -> name('process_job_application_edit');

Route::post('job-application-delete', [\App\Http\Controllers\JobApplicationController::class, 'processJobApplicationDelete'])
    -> middleware(['auth'])
    -> name('process_job_application_delete');

Route::get('job-application-attempts', [\App\Http\Controllers\JobApplicationController::class, 'showJobApplicationAttempts'])
    -> middleware(['auth'])
    -> name('show_job_application_attempts');

Route::post('job-application-attempt', [\App\Http\Controllers\JobApplicationController::class, 'processJobApplicationAttempt'])
    -> middleware(['auth'])
    -> name('process_job_application_attempt');

Route::get('rules', [\App\Http\Controllers\RulesController::class, 'showRules'])
    -> middleware(['auth'])
    -> name('show_rules');

Route::post('rule-create', [\App\Http\Controllers\RulesController::class, 'processRuleCreation'])
    -> middleware(['auth'])
    -> name('process_rule_creation');

Route::get('forum', [\App\Http\Controllers\ForumController::class, 'showForum'])
    -> middleware(['auth'])
    -> name('show_forum');

Route::get('/posts/{post}', [App\Http\Controllers\ForumController::class, 'destroyPost'])
    -> middleware(['auth'])
    -> name('post.destroy');

Route::post('post-create', [App\Http\Controllers\ForumController::class, 'processPostCreationForm'])
    -> middleware(['auth'])
    -> name('process_post_create');

Route::post('post-edit', [App\Http\Controllers\ForumController::class, 'processPostEditForm'])
    -> middleware(['auth'])
    -> name('process_post_edit');
