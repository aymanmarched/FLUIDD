<?php

use AfricasTalking\SDK\AfricasTalking;
use App\Http\Controllers\Admin\HomePageController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\AvisClientController;
use App\Http\Controllers\ClientAvisController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClientNotificationController;
use App\Http\Controllers\ClientProposalController;
use App\Http\Controllers\ContactController;

use App\Http\Controllers\GarantieController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\MachineentretenirController;
use App\Http\Controllers\MachineMediaController;
use App\Http\Controllers\MarqueController;
use App\Http\Controllers\NotificationCenterController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceEntretienController;
use App\Http\Controllers\ServiceRemplacerController;
use App\Http\Controllers\TechnicianController;

use App\Http\Controllers\TechnicianMissionController;
use App\Http\Controllers\TypeEquipementController;
use App\Models\Machine;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;




// routes/web.php
Route::get('/', action: [HomeController::class, 'home'])->name('home');

// Contact form
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');


Route::view('/Contactez_Nous', 'user/contactezNous/contactezNous');
Route::get('/Contactez_Nous/success', function () {
    return view('user.contactezNous.success');
})->name('contactezNous.success');




Route::get('/service/entretien/activer-ma-garantie', [GarantieController::class, 'create'])->name('user.service.entretien.activer-ma-garantie.create');
Route::post('/service/entretien/activer-ma-garantie/store', [GarantieController::class, 'store'])->name('user.service.entretien.activer-ma-garantie.store');


// Wizard for "entretien / entretenir-ma-maison"

Route::prefix('/service/entretien/entretenir-ma-maison')
    ->name('service.entretien.entretenir.')
    ->group(function () {

        // STEP 1
        Route::get('/', [ServiceEntretienController::class, 'step1'])->name('');
        Route::post('/', [ServiceEntretienController::class, 'step1Store'])->name('step1.store');

        // STEP 2
        Route::get('/step2', [ServiceEntretienController::class, 'step2'])->name('step2');
        Route::post('/step2/store', [ServiceEntretienController::class, 'step2Store'])->name('step2.store');

        // STEP 3
        Route::get('/step3', [ServiceEntretienController::class, 'step3'])->name('step3');
        Route::post('/step3', [ServiceEntretienController::class, 'step3Store'])->name('step3.store');

        // STEP 4
        Route::get('/step4', [ServiceEntretienController::class, 'step4'])->name('step4');
        Route::post('/step4/verify', [ServiceEntretienController::class, 'verifyStep4Sms'])->name('step4.verify');
        Route::post('/resend-sms', [ServiceEntretienController::class, 'resendSms'])->name('resendSms');

        // STEP 5 protégé
        Route::middleware('entretien.sms.verified')->group(function () {
            Route::get('/step5', [ServiceEntretienController::class, 'step5'])->name('step5');
            Route::post('/step5/{clientId}', [ServiceEntretienController::class, 'step5Store'])->name('step5.store');
        });

        // COMPLETE
        Route::get('/complete', [ServiceEntretienController::class, 'complete'])->name('complete');
    });

Route::post('/get-available-hours', [ServiceEntretienController::class, 'getAvailableHours'])->name('getAvailableHours');

// Dynamic service page
Route::get('/service/{category}/{item}', [ServiceEntretienController::class, 'show'])
    ->name('service.show');

Route::get('/service/entretien/activer-ma-garantie', [GarantieController::class, 'create'])
    ->name('garantie.create');

Route::post('/service/entretien/activer-ma-garantie/store', [GarantieController::class, 'store'])
    ->name('garantie.store');

Route::get('/get-marques/{machine}', function (Machine $machine) {
    return $machine->marques;
});

// Upload
Route::post('/machines/media/upload', [MachineMediaController::class, 'upload'])
    ->name('machines.media.upload');

// store new avis
Route::post('/avis-client/store', [AvisClientController::class, 'store'])->name('avis.client.store');

// update existing avis
Route::put('/avis-client/{avis}', [AvisClientController::class, 'update'])->name('avis.client.update');

Route::prefix('/remplacer')->name('service.remplacer.')->group(function () {

    Route::get('/', [ServiceRemplacerController::class, 'step1'])->name('step1');
    Route::post('/', [ServiceRemplacerController::class, 'step1Store'])->name('step1.store');

    Route::get('/step2', [ServiceRemplacerController::class, 'step2'])->name('step2');
    Route::post('/step2', [ServiceRemplacerController::class, 'step2Store'])->name('step2.store');

    Route::get('/step3', [ServiceRemplacerController::class, 'step3'])->name('step3');
    Route::post('/step3', [ServiceRemplacerController::class, 'step3Store'])->name('step3.store');

    // ✅ STEP 4 SMS
    Route::get('/step4', [ServiceRemplacerController::class, 'step4'])->name('step4');
    Route::post('/step4/verify', [ServiceRemplacerController::class, 'verifyStep4Sms'])->name('step4.verify');
    Route::post('/resend-sms', [ServiceRemplacerController::class, 'resendSms'])->name('resendSms');

    // ✅ STEP 5 protected
    Route::middleware('remplacer.sms.verified')->group(function () {
        Route::get('/step5', [ServiceRemplacerController::class, 'step5'])->name('step5');
        Route::post('/step5/{clientId}', [ServiceRemplacerController::class, 'step5Store'])->name('step5.store');
    });

    Route::post('/get-available-hours', [ServiceRemplacerController::class, 'getAvailableHours'])
        ->name('getAvailableHours');

    Route::get('/final/{client_id}/{token}/{reference}', [ServiceRemplacerController::class, 'final'])->name('final');
    Route::get('/devis/pdf/{client_id}/{token}/{reference}', [ServiceRemplacerController::class, 'downloadPdf'])
        ->name('devis.pdf');
});

// ------------------------------
// AUTH-PROTECTED ROUTES
// ------------------------------

// Show set-password form
Route::get('/client/set-password/{client}', [ClientDashboardController::class, 'showSetPasswordForm'])
    ->name('client.setPassword');

// Handle password submission
Route::post('/client/set-password/{client}', [ClientDashboardController::class, 'setPassword'])
    ->name('client.setPassword.store');


Route::middleware(['auth', 'role:admin|superadmin'])->group(function () {

    // ADMIN HOME
    Route::get('/admin', [AdminController::class, 'home'])->name('admin.home');

    // ADMIN CONTACT MESSAGES
    Route::get('/admin/clients_Message', [AdminController::class, 'clients_Message'])->name('admin.clientsMessage');

    // Mark message fixed / unfixed
    Route::post('/admin/clients_Message/{id}/fix', [ContactController::class, 'markFixed']);
    Route::post('/client/{id}/fix', [ContactController::class, 'markFixed']);
    Route::post('/client/{id}/unfix', [ContactController::class, 'unfix']);

    // TECHNICIAN HOME
    Route::get('/technicien', function () {
        return view('technicien.home');
    });

    // Redirect dashboard → admin
    Route::get('/admin/dashboard', function () {
        return redirect('/admin');
    })->name('dashboard');


    // ADMIN: List all client service requests
// Admin – list clients
// CLIENTS (GLOBAL)
    Route::get('/admin/clients', [AdminController::class, 'clientsIndex'])
        ->name('admin.clients');

    Route::get('/admin/clients/{id}', [AdminController::class, 'clientsShow'])
        ->name('admin.clients.show');


    Route::get('/admin/clients/commandes/entretien/{reference}', [AdminController::class, 'showClientCmdEntretien'])
        ->name('admin.clients.entretien');

    Route::get('/admin/clients/commandes/remplacer/{reference}', [AdminController::class, 'showClientCmdRemplacer'])
        ->name('admin.clients.remplacer');

    Route::get('/admin/clients/entretien/commandes', [AdminController::class, 'ClientEntretien'])
        ->name('admin.clientsentretien');



    // Admin – view client full details
    Route::get('/admin/clients/entretien/commandes/{reference}', [AdminController::class, 'showClientEntretien'])
        ->name('admin.clientsentretien.show');

    Route::get('/admin/clients/remplacer/commandes', [AdminController::class, 'ClientRemplacer'])
        ->name('admin.clientsremplacer');

    // Admin – view client full details
    Route::get('/admin/clients/remplacer/commandes/{reference}', [AdminController::class, 'showClientRemplacer'])
        ->name('admin.clientsremplacer.show');

    // TECHNICIAN ADMIN ROUTES
    Route::get('/admin/technicians', [AdminController::class, 'TechnicianIndex'])->name('admin.technicians');
    Route::get('/admin/technicians/create', [AdminController::class, 'TechnicianCreate'])->name('admin.technicians.create');
    Route::post('/admin/technicians/store', [AdminController::class, 'TechnicianStore'])->name('admin.technicians.store');
    Route::get('/admin/technicians/{technician}/edit', [AdminController::class, 'TechnicianEdit'])
        ->name('admin.technicians.edit');

    Route::put('/admin/technicians/{technician}', [AdminController::class, 'TechnicianUpdate'])
        ->name('admin.technicians.update');

    Route::get(
        '/admin/technicians/{technician}',
        [AdminController::class, 'TechnicianShow']
    )->name('admin.technicians.show');

    Route::delete(
        '/admin/technicians/{technician}',
        [AdminController::class, 'TechnicianDestroy']
    )->name('admin.technicians.destroy');


    Route::get('/admin/entretenir', function () {
        return view('admin.entretenir');
    })->name('admin.entretenir');
    Route::post('/admin/entretenir/store', [MachineentretenirController::class, 'store'])->name('entretenir.store');
    Route::post('/admin/entretenir/type/store', [TypeEquipementController::class, 'store'])->name('type.store');

    Route::get('/admin/entretenir/{id}/edit', [MachineentretenirController::class, 'edit'])->name('admin.entretenir.edit');
    Route::put('/admin/entretenir/{id}', [MachineentretenirController::class, 'update'])->name('entretenir.update');
    Route::delete('/admin/entretenir/{id}', [MachineentretenirController::class, 'destroy'])
        ->name('admin.entretenir.destroy');

    Route::get('/admin/entretenir/type/{id}/edit', [TypeEquipementController::class, 'edit'])->name('admin.entretenir.type.edit');
    Route::put('/admin/entretenir/type/{id}', [TypeEquipementController::class, 'update'])->name('type.update');
    Route::delete('/admin/entretenir/type/{id}', [TypeEquipementController::class, 'destroy'])
        ->name('admin.entretenir.type.destroy');

    Route::get('/admin/garanties', [AdminController::class, 'GarantieIndex'])
        ->name('admin.garanties');
    Route::get('/admin/garanties/{id}', [AdminController::class, 'GarantieShow'])
        ->name('admin.garanties.show');

    Route::get('/admin/avis_Clients', [AdminController::class, 'avis_Clients'])->name('admin.AvisClients');
    Route::get('/admin/avis_Clients/{id}', [AdminController::class, 'avis_ClientsSow'])
        ->name('admin.AvisClients.show');

    Route::delete('/admin/avis_Clients/{id}', [AvisClientController::class, 'destroy'])
        ->name('admin.AvisClients.destroy');



    Route::get('/admin/machines', [MachineController::class, 'index'])
        ->name('admin.machines');

    Route::post('/admin/machines', [MachineController::class, 'store'])
        ->name('admin.machines.store');

    Route::delete('/admin/machines/{id}', [MachineController::class, 'destroy'])
        ->name('admin.machines.destroy');


    Route::get('/admin/machines/{id}/edit', [MachineController::class, 'edit'])->name('admin.machines.edit');
    Route::put('/admin/machines/{id}', [MachineController::class, 'update'])->name('machines.update');

    Route::post('/admin/marques', [MarqueController::class, 'store'])
        ->name('admin.marques.store');

    Route::delete('/admin/marques/{id}', [MarqueController::class, 'destroy'])
        ->name('admin.marques.destroy');

    Route::get('/admin/marques/{id}/edit', [MarqueController::class, 'edit'])->name('admin.marques.edit');
    Route::put('/admin/marques/{id}', [MarqueController::class, 'update'])->name('marques.update');

    Route::get('/admin/commandes', [AdminController::class, 'commandesPlanning'])
        ->name('admin.commandes');

    Route::get('/admin/missions/{mission}', [AdminController::class, 'showMission'])
        ->name('admin.commandes.missions.show');


    Route::get('/admin/admins', [AdminUsersController::class, 'index'])


        ->name('admin.admins.index');

    Route::get('/admin/site-settings', [SiteSettingController::class, 'edit'])->name('admin.site-settings.edit');
     Route::get('/site-settings', [SiteSettingController::class, 'edit'])->name('admin.site-settings.edit');

     Route::get('/site-settings', [SiteSettingController::class, 'edit'])
        ->name('admin.site-settings.edit');

    Route::post('/site-settings/general', [SiteSettingController::class, 'updateGeneral'])
        ->name('admin.site-settings.update.general');

    Route::post('/site-settings/address', [SiteSettingController::class, 'updateAddress'])
        ->name('admin.site-settings.update.address');

    Route::post('/site-settings/contact', [SiteSettingController::class, 'updateContact'])
        ->name('admin.site-settings.update.contact');

    // ✅ Socials CRUD (NEW)
Route::post('/site-settings/socials', [SiteSettingController::class, 'storeSocial'])
    ->name('admin.site-settings.socials.store');

Route::put('/site-settings/socials/{social}', [SiteSettingController::class, 'updateSocial'])
    ->name('admin.site-settings.socials.update');

Route::delete('/site-settings/socials/{social}', [SiteSettingController::class, 'destroySocial'])
    ->name('admin.site-settings.socials.destroy');



    // Only superadmin can do actions
    Route::middleware(['role:superadmin'])->group(function () {
        Route::get('/admin/admins/create', [AdminUsersController::class, 'create'])->name('admin.admins.create');
        Route::post('/admin/admins', [AdminUsersController::class, 'store'])->name('admin.admins.store');
        Route::get('/admin/admins/{user}/edit', [AdminUsersController::class, 'edit'])->name('admin.admins.edit');
        Route::put('/admin/admins/{user}', [AdminUsersController::class, 'update'])->name('admin.admins.update');
        Route::delete('/admin/admins/{user}', [AdminUsersController::class, 'destroy'])->name('admin.admins.destroy');
    });

});


Route::middleware(['auth', 'role:client'])->group(function () {

    Route::get('/client', [ClientDashboardController::class, 'index'])->name('client.dashboard');

    Route::get('/client/profile/edit', [ClientDashboardController::class, 'modifier'])->name('client.profile.edit');
    Route::put('/client/profile/update', [ClientDashboardController::class, 'updateprofile'])->name('client.profile.update');


    // =======================
    // EDIT SELECTION ROUTES
    // =======================
    Route::get('/client/demandes/entretien', [ClientDashboardController::class, 'entretiens'])->name('client.entretiens');

    Route::patch(
        '/client/demandes/entretiens/{reference}/toggle',
        [ClientDashboardController::class, 'toggleEntretien']
    )->name('client.entretiens.toggle');


    Route::get(
        '/client/demandes/entretiens/{reference}',
        [ClientDashboardController::class, 'showEntretienCommande']
    )->name('client.entretiens.show');

    Route::get(
        '/client/commandes/entretiens/{reference}/edit',
        [ClientDashboardController::class, 'editentretien']
    )->name('client.entretiens.edit');

    Route::put(
        '/client/commandes/entretiens/{reference}',
        [ClientDashboardController::class, 'updateeditentretien']
    )->name('client.entretiens.update');


    Route::get('/client/commandes/remplacers', [ClientDashboardController::class, 'remplacers'])
        ->name('client.remplacers');

    Route::get('/client/commandes/remplacers/{reference}', [ClientDashboardController::class, 'showRemplacerCommande'])
        ->name('client.remplacers.show');

    Route::get('/client/commandes/remplacers/{reference}/edit', [ClientDashboardController::class, 'editRemplacer'])
        ->name('client.remplacers.edit');

    Route::put('/client/commandes/remplacers/{reference}', [ClientDashboardController::class, 'updateRemplacer'])
        ->name('client.remplacers.update');

    Route::patch(
        '/client/commandes/remplacers/{reference}/toggle',
        [ClientDashboardController::class, 'toggleRemplacer']
    )->name('client.remplacers.toggle');


    Route::get('/client/garanties', [GarantieController::class, 'clientIndex'])
        ->name('client.garanties');

    Route::get('/client/garanties/{garantie}', [GarantieController::class, 'clientShow'])
        ->name('client.garanties.show');

    Route::get('/client/proposals/{token}', [ClientProposalController::class, 'show'])
        ->name('client.proposals.show');

    Route::post('/client/proposals/{token}/accept', [ClientProposalController::class, 'accept'])
        ->name('client.proposals.accept');

    Route::post('/client/proposals/{token}/reject', [ClientProposalController::class, 'reject'])
        ->name('client.proposals.reject');

    Route::get('/client/notifications', [ClientNotificationController::class, 'index'])
        ->name('client.notifications.index');

    Route::post('/client/notifications/{id}/read', [ClientNotificationController::class, 'read'])
        ->name('client.notifications.read');

    Route::post('/client/notifications/read-all', [ClientNotificationController::class, 'readAll'])
        ->name('client.notifications.readAll');

    Route::get('/client/avis', [ClientAvisController::class, 'index'])->name('client.avis.index');
    Route::post('/client/avis', [ClientAvisController::class, 'store'])->name('client.avis.store');
    Route::put('/client/avis/{avis}', [ClientAvisController::class, 'update'])->name('client.avis.update');
    Route::delete('/client/avis/{avis}', [ClientAvisController::class, 'destroy'])->name('client.avis.destroy');

});



Route::middleware(['auth', 'role:technicien'])->prefix('technicien')->group(function () {

    // Dashboard
    Route::get('/', [TechnicianController::class, 'index'])
        ->name('technicien.profile');

    // Profile
    Route::get('/profile', [TechnicianController::class, 'profile'])
        ->name('technicien.profile.edit');

    Route::post('/profile', [TechnicianController::class, 'updateProfile'])
        ->name('technicien.profile.update');

    Route::get('/planning', [TechnicianController::class, 'planning'])
        ->name('technicien.commandes');

    Route::get('/commandes/{type}/{reference}', [TechnicianController::class, 'showCommande'])
        ->name('technicien.commandes.show');

    Route::get('/missions/start/{type}/{reference}', [TechnicianMissionController::class, 'start'])
        ->name('technicien.missions.start');

    Route::get('/missions/{mission}', [TechnicianMissionController::class, 'show'])
        ->name('technicien.missions.show');

    Route::post('/missions/{mission}/step1', [TechnicianMissionController::class, 'saveStep1'])
        ->name('technicien.missions.step1');

    Route::post('/missions/{mission}/step2', [TechnicianMissionController::class, 'saveStep2'])
        ->name('technicien.missions.step2');

    Route::post('/missions/{mission}/proposal', [TechnicianMissionController::class, 'sendRemplacerProposal'])
        ->name('technicien.missions.proposal');

    // Route::post('/missions/{mission}/step3', [TechnicianMissionController::class, 'saveStep3'])
    //     ->name('technicien.missions.step3');

    // routes/web.php (inside your technicien group)
    Route::get('/missions/{mission}/entretien/remplacer-marques', [TechnicianMissionController::class, 'entretienRemplacerMarques'])
        ->name('technicien.missions.entretien.remplacer.marques');

    Route::post('/missions/{mission}/entretien/remplacer-marques', [TechnicianMissionController::class, 'sendEntretienRemplacerProposal'])
        ->name('technicien.missions.entretien.remplacer.marques.send');
    // ✅ Edit Step (GET form) + Update Step (POST)
    Route::get('/missions/{mission}/steps/{stepNo}/edit', [TechnicianMissionController::class, 'editStep'])
        ->whereNumber('stepNo')
        ->name('technicien.missions.steps.edit');

    Route::post('/missions/{mission}/steps/{stepNo}', [TechnicianMissionController::class, 'updateStep'])
        ->whereNumber('stepNo')
        ->name('technicien.missions.steps.update');

    Route::delete('/missions/{mission}/steps/{stepNo}/media', [TechnicianMissionController::class, 'deleteStepMedia'])
        ->whereNumber('stepNo')
        ->name('technicien.missions.steps.media.delete');
    Route::get('/missions/{mission}/details', [TechnicianMissionController::class, 'details'])
        ->name('technicien.missions.details');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationCenterController::class, 'index'])->name('notifications.index');

    Route::get('/notifications/unread-count', [NotificationCenterController::class, 'unreadCount'])
        ->name('notifications.unread_count');
    Route::get('/notifications/{id}', [NotificationCenterController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/read-all', [NotificationCenterController::class, 'markAllRead'])->name('notifications.read_all');
    Route::get('/notifications/poll', [NotificationCenterController::class, 'poll'])
        ->name('notifications.poll');

    Route::get('/client/commandes/{type}/{reference}/payer', [PaymentController::class, 'create'])
        ->name('client.payments.create');

    Route::post('/client/commandes/{type}/{reference}/payer', [PaymentController::class, 'store'])
        ->name('client.payments.store');


});


// routes/web.php

// LOGOUT

Route::post('/logout', function () {
    $user = auth()->user(); // get user BEFORE logout

    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    if ($user && $user->role === 'client') {
        return redirect('/'); // 👤 client → homepage
    }

    return redirect('/login'); // 👑 admin & others → login
})->name('logout');

// Show registration form
// Route::get('/register', [HomeController::class, 'showRegisterForm'])->name('register');

// Handle registration form submission
// Route::post('/register', [HomeController::class, 'register']);

use App\Http\Controllers\Auth\AuthPageController;

Route::get('/AUTH', [AuthPageController::class, 'index'])->name('auth.page');
Route::post('/AUTH', [AuthPageController::class, 'submit'])->name('auth.submit');

/*
|----------------------------------------------------------
| Keep these only if some middleware / old links still use them
| They will always redirect to /AUTH
|----------------------------------------------------------
*/
Route::redirect('/login', '/AUTH')->name('login');
Route::redirect('/register', '/AUTH')->name('register');