<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AoFormController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\FileSpecificationController;
use App\Http\Controllers\FileVerificationController;
use App\Http\Controllers\GdController;
use App\Http\Controllers\HistoryLogController;
use App\Http\Controllers\TrafficController;
use App\Models\Traffic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','check.system.access','force.change.password'])->group(function () {

    Route::middleware('can:is-admin')->group(function () {
        Route::get('/users-serverside',[\App\Http\Controllers\UserController::class,'getAllUsers'])->name('users.showall');
        Route::resources([
            'users' => App\Http\Controllers\UserController::class,
            
        ]);

        //added new profile route
        Route::get('/user/{user}', [\App\Http\Controllers\UserController::class, 'profile'])->name('users.profile');
        Route::patch('/users/{user}/passwordreset',[\App\Http\Controllers\UserController::class,'passwordReset'])->name('users.passwordreset');

        Route::get('/roles-serverside',[\App\Http\Controllers\RoleController::class,'getAllRoles'])->name('roles.showall');
        Route::resources([
            'roles' => App\Http\Controllers\RoleController::class,
        ]);

        Route::get('/access-modules-serverside',[\App\Http\Controllers\AccessModuleController::class,'getAllAccessModules'])->name('access-modules.showall');
        Route::resources([
            'access-modules' => App\Http\Controllers\AccessModuleController::class,
        ]);

        // from Fact Product // Bom Creation
        Route::get('/bom-creations/fact-item/search',[\App\Http\Controllers\HomeController::class,'searchFACTItem'])->name('bom-creations.item-search');
    
        //ao 
        Route::middleware([\App\Http\Middleware\CancelAbandonedRfa::class])->group(function () {
        Route::get('/ao-records', [App\Http\Controllers\AoFormController::class, 'ao_page'])->name('ao.sendFileLink');
     
        // Route::get('/ao-records', [App\Http\Controllers\AoFormController::class, 'index'])->name('ao.sendFileLink');
        Route::get('/getAllFileSend',[\App\Http\Controllers\AoFormController::class,'FileSendLinkShowAll'])->name('FileSendShowAll');
        Route::get('/showAllReadyForItemCode',[\App\Http\Controllers\AoFormController::class,'showAllReadyForItemCode'])->name('showAllReadyForItemCode');

        Route::get('/getArtworkFileDetail/{item_id}',[\App\Http\Controllers\ArtworkController::class,'getArtworkFileDetail'])->name('getArtworkFileDetail');
         Route::get('/toAddItemCode/{item_id}',[\App\Http\Controllers\ArtworkController::class,'toAddItemCode'])->name('toAddItemCode');

        Route::post('/fileLink', [App\Http\Controllers\AoFormController::class, 'store'])->name('ao_send_link');//send link to gd
        Route::post('/saveNewItem', [App\Http\Controllers\AoFormController::class, 'store_without_itemcode'])->name('saveNewItem');//send link to gd no item code yet
        
        Route::get('/getListRfaFile', [App\Http\Controllers\AoFormController::class, 'getRfaFileList'])->name('rfa_file_list');
        Route::post('/update-supplied-status', [FileVerificationController::class,'updateStatus'])->name('update-supplied-status'); //update file status if opened
        
        
        Route::get('/gd_sv_supplied_list', [App\Http\Controllers\AoFormController::class, 'gd_supervisor_supplied_file'])->name('gd_sv_supplied_list');

         Route::post('/aoCreateItemCode', [AoFormController::class,'aoAddedItemCode'])->name('aoCreateItemCode'); //update file status if opened
        }); 
        //ao end
    });

    Route::middleware('can:is-supervisor')->group(function () {
        // GD SuperVisor
        Route::get('/gd_supervisor', [App\Http\Controllers\GdController::class, 'gd_supervisor'])->name('gd_supervisor');
        Route::get('/gd_suppliedVerified/{id}', [FileVerificationController::class, 'showSuppliedFileVerified'])->name('gd_suppliedVerified.show');
        Route::get('/suppliedVerifiedNoItemCode/{id}', [FileVerificationController::class, 'showSuppliedFileVerified_NoItemCode'])->name('suppliedVerifiedNoItemCode.show');
        Route::get('/specifications/{id}', [FileVerificationController::class, 'showSuppliedFileVerified'])->name('specifications.show');
        
        Route::post('/saveSuppliedVerified', [FileVerificationController::class, 'saveFileVerification'])->name('suppliedVerified.save');
         Route::post('/suppliedVerified_no_item_code', [FileVerificationController::class, 'suppliedVerified_no_item_code'])->name('suppliedVerified_no_item_code.save');
       
        Route::post('/saveSpecification', [FileSpecificationController::class, 'saveSpecification'])->name('saveSpecification.save');

        Route::get('/getArtistList',[ArtistController::class, 'getAllArtist'])->name('getArtistList.all');

        // assign to artist
         Route::post('/assignToArtist', [GdController::class, 'assignToArtist'])->name('assignToArtist.save');
        Route::post('/reassign-artist', [GdController::class, 'reassignArtist'])->name('reassignArtist.save');
        
        // save supplied verify

        //update supplied status
        Route::post('/gd_update-supplied-status', [FileVerificationController::class,'updateStatus'])->name('gd_update-supplied-status'); //update file status if opened
      
        Route::post('/gd-action-taken', [GdController::class,'gdActionTaken'])->name('gd-action-taken');

    });

    // traffic
     Route::middleware('can:is-traffic')->group(function () {
        Route::get('/traffic_officer',[TrafficController::class, 'traffic_officer'])->name('traffic_officer.all');
          Route::get('/suppliedVerified/{id}', [FileVerificationController::class, 'showSuppliedFileVerified'])->name('suppliedVerified.show');
        Route::get('/traffic_suppliedVerifiedNoItemCode/{id}', [FileVerificationController::class, 'showSuppliedFileVerified_NoItemCode'])->name('suppliedVerifiedNoItemCode.show');
    Route::get('/getArtworkFileDetail/{item_id}',[\App\Http\Controllers\ArtworkController::class,'getArtworkFileDetail'])->name('getArtworkFileDetail');

          Route::get('/trafficFileRecievedLink',[\App\Http\Controllers\TrafficController::class,'trafficFileRecievedLink'])->name('trafficFileRecievedLink');
    Route::post('/traffic_update-supplied-status', [FileVerificationController::class,'updateStatus'])->name('update-supplied-status'); //update file status if opened
   
   Route::get('/getNotif', [App\Http\Controllers\NotificationController::class, 'getNotificationPerUser'])->name('Notifications');
    });
    
      Route::middleware('can:password-change')->group(function () {
        Route::get('/password_change',[\App\Http\Controllers\UserController::class,'indexChangePass'])->name('password.index');
        Route::patch('/password_change',[\App\Http\Controllers\UserController::class,'passwordChange'])->name('password.change');
        // Route::get('/password/logout',[\App\Http\Controllers\UserController::class,'logout'])->name('password.logout');
    });

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::post('/timeStart', [App\Http\Controllers\HomeController::class, 'timelineSave'])->name('timelineSave');
    Route::post('/timeStop', [App\Http\Controllers\HomeController::class, 'timeStop'])->name('timelineStop');


     Route::get('/getAllTimeLine',[\App\Http\Controllers\HomeController::class,'TimeLineShowAll'])->name('timeline.showAll');

    //  gd super visor 
    //  Route::get('/gd_supervisor', [App\Http\Controllers\GdController::class, 'gd_supervisor'])->name('gd_supervisor');
    //gd
    Route::get('/gd-records', [App\Http\Controllers\GdController::class, 'index'])->name('gd.recievedFileLink');
    Route::get('/gd-all-file-recieved',[\App\Http\Controllers\FileVerificationController::class,'FileRecievedLinkShowAll'])->name('FileRecievedLinkShowAll');
    Route::post('/update-file-status', [GdController::class,'updateFileStatus'])->name('update-file-status'); //update file status if opened

      
    Route::get('/gd-all-file-recieved_with_item_code',[\App\Http\Controllers\FileVerificationController::class,'FileRecievedLinkShowAllNoItemCode'])->name('FileRecievedLinkShowAllNoItemCode');
      Route::get('/getSuppliedVerified', [FileVerificationController::class, 'getSuppliedVeried'])->name('getSuppliedVeried');
      Route::get('/getSpecificationsAll', [FileSpecificationController::class, 'getSpecificationsAll'])->name('getSpecificationsAll');
      
    // notifications
    Route::get('/getNotif', [App\Http\Controllers\NotificationController::class, 'getNotificationPerUser'])->name('Notifications');


      Route::middleware('can:is-artist')->group(function () {
        Route::get('/artistPage', [ArtistController::class, 'gdArtistPage'])->name('artist');
        Route::get('/getAllMyArtworkList', [ArtistController::class,  'getAllMyArtworkList'])->name('getAllMyArtworkList');
        Route::get('/getAllMyArtworkCompanion', [ArtistController::class,  'getAllMyArtworkCompanion'])->name('getAllMyArtworkCompanion');
       
            //for gd timer
    Route::post('/artwork/{id}/timer', [ArtistController::class, 'toggleTimer'])->name('artwork.timer');
    Route::get('/getTimeShareDuration/{id}', [ArtistController::class, 'getShareTimeDuration'])->name('getShareTimeDuration');
    Route::get('/getTimeShareDurationCompanion/{id}', [ArtistController::class, 'getShareTimeDurationCompanion'])->name('getShareTimeDuration');

       
       
    });
    Route::get('/artwork/activity-logs', [ArtistController::class, 'getActivityLogs'])->name('artwork.activity_logs');//display activitylogs in artwork page
    Route::get('/getActivityLogs', [ActivityLogController::class, 'getActivityLogs'])->name('getActivityLogs');
    Route::get('/getHistoryLogs', [HistoryLogController::class, 'getHistoryLogs'])->name('getHistoryLogs');
    Route::get('/artwork/{id}', [ArtistController::class, 'getViewArtworkById'])->name('artwork');
    Route::post('/artwork/{item_code}/timer', [ArtistController::class, 'toggleTimer'])->name('artwork.timer');//start resume pause stop
    Route::get('/getTimeShareDuration/{id}', [ArtistController::class, 'getShareTimeDuration'])->name('getShareTimeDuration');
     Route::get('/getTimeShareDurationCompanion/{id}', [ArtistController::class, 'getShareTimeDurationCompanion'])->name('getShareTimeDuration');


    Route::get('/timeline/{item_code}', [AoFormController::class, 'timeline'])->name('timeline');

Route::get('/getArtworkFileDetail/{item_id}',[\App\Http\Controllers\ArtworkController::class,'getArtworkFileDetail'])->name('getArtworkFileDetail');
  Route::get('/toAddItemCode/{item_id}',[\App\Http\Controllers\ArtworkController::class,'toAddItemCode'])->name('toAddItemCode'); 
   
   Route::get('/getNewReferenceNum',[\App\Http\Controllers\AoFormController::class,'getNewLatestReference'])->name('getNewReferenceNum');

   Route::get('/getExistingReferenceNum',[\App\Http\Controllers\AoFormController::class,'getExistingReferenceNum'])->name('getExistingReferenceNum');
   Route::get('/getAddFormReferenceNum',[\App\Http\Controllers\AoFormController::class,'getAddFormReferenceNum'])->name('getAddFormReferenceNum');
 

 
 
   Route::get('/', function () {
        return redirect('/dashboard');
    });
});

Route::middleware(['auth','check.system.access'])->group(function () {
    Route::post('/password/logout',[\App\Http\Controllers\UserController::class,'logout'])->name('password.logout');
});
// Route::get('/artistPage', [ArtistController::class, 'gdArtistPage'])->name('artist');
  
Route::middleware('guest')->group(function () {
    // Route::get('/commonlogin',[\App\Http\Controllers\CommonLoginController::class,'showLoginForm'])->name('commonlogin.show');
    // Route::post('/commonlogin',[\App\Http\Controllers\CommonLoginController::class,'login'])->name('commonlogin.login');
});



Route::middleware(['auth'])->group(function () {
    
    // Endpoint A: Mark single row item as read on database execution
    Route::post('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()->unreadNotifications->find($id);
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    });

    // Endpoint B: COMPACT LIVE POLL (Compiles template updates on the fly)
    Route::get('/api/notifications/poll', function () {
        $user = auth()->user();
        $notifications = $user->notifications()->take(10)->get();
        
        $html = '';
        
        if ($notifications->isEmpty()) {
            $html = '<div class="dropdown-item text-center text-muted py-3">
                        <i class="fas fa-check-circle text-success mb-2 d-block"></i>
                        No notifications available
                     </div>';
        } else {
            foreach ($notifications as $notification) {
                $url = isset($notification->data['url']) && !is_array($notification->data['url']) ? $notification->data['url'] : '#';
                $icon = isset($notification->data['icon']) && !is_array($notification->data['icon']) ? $notification->data['icon'] : 'fas fa-envelope';
                $title = isset($notification->data['title']) && !is_array($notification->data['title']) ? $notification->data['title'] : 'Update';
                $message = isset($notification->data['message']) && !is_array($notification->data['message']) ? $notification->data['message'] : '';
                $isUnread = $notification->read_at === null;
                 $item_code = isset($notification->data['Item_code']) && !is_array($notification->data['Item_code']) ? $notification->data['Item_code'] : '';
                $bgColor = $isUnread ? '#f4f6f9' : '#ffffff';
                $fontWeight = $isUnread ? 'font-weight-bold' : 'text-muted';
                $timeStr = $notification->created_at->diffForHumans(null, true);

                $html .= "
                <a href='{$url}' 
                   class='dropdown-item d-flex align-items-center mark-as-read' 
                   data-id='{$notification->id}'
                   data-unread='" . ($isUnread ? 'true' : 'false') . "'
                   style='background-color: {$bgColor}; transition: background 0.3s;'
                   target='_blank' rel='noopener noreferrer'>
                    <i class='{$icon} mr-3 text-sm'></i> 
                    <div class='text-truncate mr-2' style='max-width: 180px;'>
                        <span class='d-block text-sm text-wrap notification-title {$fontWeight}'>{$title} </span>
                        <small class='text-muted d-block text-truncate'>{$item_code }</small>
                    </div>
                    <span class='ml-auto text-muted text-xs whitespace-nowrap'>{$timeStr}</span>
                </a>
                <div class='dropdown-divider'></div>";
            }
        }

        return response()->json([
            'unread_count' => $user->unreadNotifications->count(),
            'html' => $html
        ]);
    });
});

Auth::routes();

