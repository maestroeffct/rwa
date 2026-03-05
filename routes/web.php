<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AfpmanagerController;
use App\Http\Controllers\Web\PurchaseCodeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => 'my_api', 'namespace' => 'Api\Panel', 'middleware' => ['signed', 'x_frame_headers'], 'as' => 'my_api.web.'], function () {
    Route::get('checkout/{user}', 'CartController@webCheckoutRender')->name('checkout');
    Route::get('/charge/{user}', 'PaymentsController@webChargeRender')->name('charge');
    Route::get('/subscribe/{user}/{subscribe}', 'SubscribesController@webPayRender')->name('subscribe');
    Route::get('/registration_packages/{user}/{package}', 'RegistrationPackagesController@webPayRender')->name('registration_packages');
    Route::get('/courses/learning_file/{user}', 'CoursesLearningContent@renderWebUrl')->name('courses_learning_file');
});

Route::group(['prefix' => 'api_sessions'], function () {
    Route::get('/{session_id}/big_blue_button', ['uses' => 'Api\Panel\SessionController@BigBlueButton'])->name('big_blue_button');
    Route::get('/agora', ['uses' => 'Api\Panel\SessionController@agora'])->name('agora');

});

Route::get('/mobile-app', 'Web\MobileAppController@index')->middleware(['share', 'impersonate'])->name('mobileAppRoute');
Route::get('/maintenance', 'Web\MaintenanceController@index')->middleware(['share', 'impersonate'])->name('maintenanceRoute');
Route::get('/restriction', 'Web\RestrictionController@index')->middleware(['share', 'impersonate'])->name('restrictionRoute');

Route::group(['prefix' => 'cookie-security', 'middleware' => ['share', 'impersonate']], function () {
    Route::post('/all', 'Web\CookieSecurityController@setAll');
    Route::get('/customize-modal', 'Web\CookieSecurityController@getCustomizeModal');
    Route::post('/customize', 'Web\CookieSecurityController@setCustomize');
});

// Captcha
Route::group(['prefix' => 'captcha'], function () {
    Route::post('create', function () {
        $response = ['status' => 'success', 'captcha_src' => captcha_src('flat')];

        return response()->json($response);
    });
    Route::get('{config?}', '\Mews\Captcha\CaptchaController@getCaptcha');
});

Route::group(['middleware' => ['admin']], function () {
    Route::group(['prefix' => 'admin/lfm'], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
});


/* Emergency Database Update */
Route::get('/emergencyDatabaseUpdate', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate', [
        '--force' => true
    ]);
    $msg1 = \Illuminate\Support\Facades\Artisan::output();

    \Illuminate\Support\Facades\Artisan::call('db:seed', [
        '--force' => true
    ]);
    $msg2 = \Illuminate\Support\Facades\Artisan::output();

    \Illuminate\Support\Facades\Artisan::call('clear:all', [
        '--force' => true
    ]);

    return response()->json([
        'migrations' => $msg1,
        'sections' => $msg2,
    ]);
});

Route::group(['namespace' => 'Auth', 'middleware' => ['check_mobile_app', 'share', 'check_maintenance', 'check_restriction']], function () {
    Route::get('/login', 'LoginController@showLoginForm');
    Route::post('/login', 'LoginController@login');
    Route::get('/logout', 'LoginController@logout');
    Route::get('/register', 'RegisterController@showRegistrationForm');
    Route::post('/register', 'RegisterController@register');
    Route::post('/register/form-fields', 'RegisterController@getFormFieldsByUserType');
    Route::get('/verification', 'VerificationController@index');
    Route::post('/verification', 'VerificationController@confirmCode');
    Route::get('/verification/resend', 'VerificationController@resendCode');
    Route::get('/forget-password', 'ForgotPasswordController@showLinkRequestForm');
    Route::post('/forget-password', 'ForgotPasswordController@forgot');
    Route::get('reset-password/{token}', 'ResetPasswordController@showResetForm');
    Route::post('/reset-password', 'ResetPasswordController@updatePassword');
    Route::get('/google', 'SocialiteController@redirectToGoogle');
    Route::get('/google/callback', 'SocialiteController@handleGoogleCallback');
    Route::get('/facebook/redirect', 'SocialiteController@redirectToFacebook');
    Route::get('/facebook/callback', 'SocialiteController@handleFacebookCallback');
    Route::get('/reff/{code}', 'ReferralController@referral');
});

Route::group(['namespace' => 'Web', 'middleware' => ['check_mobile_app', 'impersonate', 'share', 'check_maintenance', 'check_restriction']], function () {
    Route::get('/stripe', function () {
        return view('design_1.web.cart.payment.channels.stripe');
    });

    // set Locale
    Route::post('/locale', 'LocaleController@setLocale')->name('appLocaleRoute');

    // set Currency
    Route::post('/set-currency', 'SetCurrencyController@setCurrency');

    // set Theme Color Mode
    Route::post('/set-theme-color-mode', 'SetThemeColorModeController@setColorMode');

    Route::get('/', 'HomeController@index');

    Route::get('/getDefaultAvatar', 'DefaultAvatarController@make');

    Route::post('/get-advertising-modal', 'AdvertisingModalController@getModal');

    Route::group(['prefix' => 'course'], function () {
        Route::get('/{slug}', 'WebinarController@course');
        Route::get('/{slug}/file/{file_id}/download', 'WebinarController@downloadFile');
        Route::get('/{slug}/file/{file_id}/showHtml', 'WebinarController@showHtmlFile');
        Route::get('/{slug}/lessons/{lesson_id}/read', 'WebinarController@getLesson');
        Route::post('/getFilePath', 'WebinarController@getFilePath');
        Route::get('/{slug}/file/{file_id}/play', 'WebinarController@playFile');
        Route::get('/{slug}/free', 'WebinarController@free');
        Route::post('/{id}/report', 'WebinarController@reportWebinar');
        Route::post('/{slug}/learningStatus', 'WebinarController@learningStatus');
        Route::get('/{slug}/learning-status-completed-modal', 'WebinarController@learningStatusCompletedModal');
        Route::get('/{slug}/share-modal', 'WebinarController@getShareModal');
        Route::get('/{slug}/report-modal', 'WebinarController@getReportModal');

        /* Course Points */
        Route::group(['prefix' => '/{slug}/points'], function () {
            Route::get('/apply', 'WebinarController@buyWithPoint');
            Route::get('/get-modal', 'WebinarController@getBuyWithPointModal');
        });

        /* Course waitlist */
        Route::group(['prefix' => '/{slug}/waitlists'], function () {
            Route::post('/join', 'WaitlistController@store');
            Route::get('/get-modal', 'WaitlistController@getWaitlistModal');
        });

        /* Review Load More */
        Route::post('/{slug}/reviews/load-more', 'WebinarReviewController@getReviewsByCourseSlug');

        Route::group(['middleware' => 'web.auth'], function () {
            Route::get('/{slug}/installments', 'WebinarController@getInstallmentsByCourse');

            Route::post('/learning/{slug}/itemInfo', 'LearningPageController@getItemInfo');
            Route::get('/learning/{slug}/itemSequenceContentInfo', 'LearningPageController@getItemSequenceContentInfo');
            Route::get('/learning/{slug}/noticeboards', 'LearningPageController@noticeboards');
            Route::post('/learning/{slug}/track-time', 'LearningPageController@trackSpentTime');
            Route::get('/learning/{slug}', 'LearningPageController@index');


            /* Assignment */
            Route::group(['prefix' => '/assignment/{assignmentId}'], function () {
                Route::get('/download/{id}/attach', 'LearningPageController@downloadAssignment');
                Route::post('/history/{historyId}/message', 'AssignmentHistoryController@storeMessage');
                Route::get('/history/{historyId}/grade-modal', 'AssignmentHistoryController@getGradeModal');
                Route::post('/history/{historyId}/setGrade', 'AssignmentHistoryController@setGrade');
                Route::get('/history/{historyId}/message/{messageId}/downloadAttach', 'AssignmentHistoryController@downloadAttach');
            });

            Route::group(['prefix' => '/learning/{slug}/forum'], function () { // LearningPageForumTrait
                Route::get('/', 'LearningPageController@forum');
                Route::get('/create', 'LearningPageController@getAskQuestionModal');
                Route::post('/store', 'LearningPageController@forumStoreNewQuestion');
                Route::get('/{forumId}/edit', 'LearningPageController@getForumForEdit');
                Route::post('/{forumId}/update', 'LearningPageController@updateForum');
                Route::post('/{forumId}/pinToggle', 'LearningPageController@forumPinToggle');
                Route::get('/{forumId}/downloadAttach', 'LearningPageController@forumDownloadAttach');

                Route::group(['prefix' => '/{forumId}/answers'], function () {
                    Route::get('/', 'LearningPageController@getForumAnswers');
                    Route::post('/', 'LearningPageController@storeForumAnswers');
                    Route::get('/{answerId}/edit', 'LearningPageController@answerEdit');
                    Route::post('/{answerId}/update', 'LearningPageController@answerUpdate');
                    Route::get('/{answerId}/mark-as-resolved', 'LearningPageController@answerMarkAsResolvedModal');
                    Route::post('/{answerId}/mark-as-resolved', 'LearningPageController@answerMarkAsResolved');
                    Route::post('/{answerId}/{togglePinOrResolved}', 'LearningPageController@answerTogglePinOrResolved');
                });
            });

            Route::group(['prefix' => '/learning/{slug}/personal-note'], function () {
                Route::get('/get-form', 'LearningPageController@getPersonalNoteForm');
                Route::get('/get-details', 'LearningPageController@getPersonalNoteDetails');
                Route::post('/store', 'LearningPageController@storePersonalNote');
            });

            Route::post('/direct-payment', 'WebinarController@directPayment');

            Route::group(['prefix' => 'personal-notes'], function () {
                Route::get('/{id}/delete', 'CoursePersonalNotesController@deleteAttachment');
                Route::get('/{id}/download-attachment', 'CoursePersonalNotesController@downloadAttachment');
            });
        });
    });

    Route::group(['prefix' => 'certificate_validation'], function () {
        Route::get('/', 'CertificateValidationController@index');
        Route::post('/validate', 'CertificateValidationController@checkValidate');
    });


    Route::group(['prefix' => 'cart'], function () {
        Route::post('/store', 'CartManagerController@store');
        Route::post('/{id}/quantity', 'CartManagerController@quantity');
        Route::get('/{id}/delete', 'CartManagerController@destroy');
        Route::get('/get-drawer-info', 'CartManagerController@getDrawerInfo');
    });

    Route::group(['middleware' => 'web.auth'], function () {
        Route::group(['prefix' => 'reviews'], function () {
            Route::post('/store', 'WebinarReviewController@store');
            Route::post('/store-reply-comment', 'WebinarReviewController@storeReplyComment');
            Route::get('/{id}/delete', 'WebinarReviewController@destroy');
            Route::get('/{id}/delete-comment/{commentId}', 'WebinarReviewController@destroy');
        });

        Route::group(['prefix' => 'favorites'], function () {
            Route::get('{slug}/toggle', 'FavoriteController@toggle');
            Route::post('/{id}/update', 'FavoriteController@update');
            Route::get('/{id}/delete', 'FavoriteController@destroy');
        });

        Route::group(['prefix' => 'comments'], function () {
            Route::post("/lists/{itemType}/{itemId}", 'CommentController@getComments');
            Route::post('/store', 'CommentController@store');
            Route::post('/{id}/reply', 'CommentController@storeReply');
            Route::post('/{id}/update', 'CommentController@update');
            Route::post('/{id}/report', 'CommentController@report');
            Route::get('/{id}/delete', 'CommentController@destroy');
            Route::get('/get-report-modal', 'CommentController@getReportModal');
        });

        Route::group(['prefix' => 'cart'], function () {
            Route::get('/', 'CartController@index');

            Route::post('/coupon/validate', 'CartController@couponValidate');
            Route::post('/checkout', 'CartController@checkout')->name('checkout');
        });

        Route::group(['prefix' => 'users'], function () {
            Route::get('/{username}/follow', 'UserProfileController@followToggle');
        });

        Route::group(['prefix' => 'become-instructor'], function () {
            Route::get('/', 'BecomeInstructorController@index')->name('becomeInstructor');
            Route::get('/packages', 'BecomeInstructorController@packages')->name('becomeInstructorPackages');
            Route::get('/packages/{id}/checkHasInstallment', 'BecomeInstructorController@checkPackageHasInstallment');
            Route::get('/packages/{id}/installments', 'BecomeInstructorController@getInstallmentsByRegistrationPackage');
            Route::post('/store', 'BecomeInstructorController@store');
            Route::post('/form-fields', 'BecomeInstructorController@getFormFieldsByUserType');
        });

    });

    /*********
     * Profile Routes
     ******* */
    Route::group(['prefix' => 'users'], function () {
        Route::get('/{username}/profile', 'UserProfileController@profile');
        Route::post('/{username}/get-courses', 'UserProfileController@getUserCourses');
        Route::post('/{username}/get-products', 'UserProfileController@getUserProducts');
        Route::post('/{username}/get-posts', 'UserProfileController@getUserPosts');
        Route::post('/{username}/get-topics', 'UserProfileController@getUserForumTopics');
        Route::post('/{username}/get-instructors', 'UserProfileController@getOrganizationInstructors');
        Route::post('/{username}/availableTimes', 'UserProfileController@availableTimes');
        Route::get('/{username}/get-send-message-form', 'UserProfileController@getSendMessageForm');
        Route::post('/{username}/send-message', 'UserProfileController@sendMessage');

        Route::post('/search', 'UserController@search');

        /*********
         * Meeting Routes
         ******* */
        Route::group(['prefix' => '{username}/meetings'], function () {
            Route::get('/', 'MeetingController@index');
            Route::get('/overview', 'MeetingController@overview');
            Route::post('/reserve', 'MeetingController@reserve');
            Route::post('/get-amount', 'MeetingController@getMeetingAmount');
        });

    });

    Route::group(['prefix' => 'payments'], function () {
        Route::post('/payment-request', 'PaymentController@paymentRequest');
        Route::get('/verify/{gateway}', ['as' => 'payment_verify', 'uses' => 'PaymentController@paymentVerify']);
        Route::post('/verify/{gateway}', ['as' => 'payment_verify_post', 'uses' => 'PaymentController@paymentVerify']);
        Route::get('/status', 'PaymentController@payStatus');
        Route::get('/payku/callback/{id}', 'PaymentController@paykuPaymentVerify')->name('payku.result');
        Route::get('/chapa/callback/{reference}', 'PaymentController@chapaPaymentVerify')->name('chapa.callback');
    });

    Route::group(['prefix' => 'subscribes'], function () {
        Route::get('/{id}/details', 'SubscribeController@details');
        Route::get('/apply/bundle/{bundleSlug}', 'SubscribeController@bundleApply');
        Route::get('/apply/{webinarSlug}', 'SubscribeController@apply');
    });

    Route::group(['prefix' => 'search'], function () {
        Route::get('/', 'SearchController@index');
    });

    Route::group(['prefix' => 'tags'], function () {
        Route::get('/{type}/{tag}', 'TagsController@index');
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/{categoryTitle}/{subCategoryTitle?}', 'CategoriesController@index');
    });

    Route::get('/classes', 'ClassesController@index');

    Route::get('/reward-courses', 'RewardCoursesController@index');

    Route::group(['prefix' => 'blog'], function () {
        Route::get('/', 'BlogController@index');
        Route::get('/categories/{category}', 'BlogController@index');
        Route::get('/{slug}', 'BlogController@show');
        Route::get('/{slug}/share-modal', 'BlogController@getShareModal');
    });

    Route::group(['prefix' => 'contact'], function () {
        Route::get('/', 'ContactController@index');
        Route::post('/store', 'ContactController@store');
    });

    Route::group(['prefix' => 'instructors'], function () {
        Route::get('/', 'InstructorsController@instructors');
    });

    Route::group(['prefix' => 'organizations'], function () {
        Route::get('/', 'InstructorsController@organizations');
    });

    Route::group(['prefix' => 'pages'], function () {
        Route::get('/{link}', 'PagesController@index');
    });

    Route::post('/newsletters', 'UserController@makeNewsletter');

    /* Cron Jobs Routes */
    Route::group(['prefix' => 'cron-jobs'], function () {
        Route::get('/{methodName}', 'CronJobsController@index');
        Route::post('/{methodName}', 'CronJobsController@index');
    });

    Route::group(['prefix' => 'regions'], function () {
        Route::get('/countries', 'RegionController@allCountries');
        Route::get('/provincesByCountry/{countryId}', 'RegionController@provincesByCountry');
        Route::get('/citiesByProvince/{provinceId}', 'RegionController@citiesByProvince');
        Route::get('/districtsByCity/{cityId}', 'RegionController@districtsByCity');
    });

    Route::group(['prefix' => 'instructor-finder'], function () {
        Route::get('/', 'InstructorFinderController@index');
        Route::get('/wizard', 'InstructorFinderController@wizard');
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('/', 'ProductController@index');
        Route::get('/{slug}', 'ProductController@show');
        Route::post('/{slug}/points/apply', 'ProductController@buyWithPoint');
        Route::get('/{slug}/files', 'ProductController@showFiles');

        /* Review Load More */
        Route::post('/{slug}/reviews/load-more', 'ProductReviewController@getReviewsByCourseSlug');

        Route::group(['prefix' => 'reviews'], function () {
            Route::post('/store', 'ProductReviewController@store');
            Route::post('/store-reply-comment', 'ProductReviewController@storeReplyComment');
            Route::get('/{id}/delete', 'ProductReviewController@destroy');
            Route::get('/{id}/delete-comment/{commentId}', 'ProductReviewController@destroy');
        });

        Route::group(['middleware' => 'web.auth'], function () {
            Route::get('/{slug}/installments', 'ProductController@getInstallmentsByProduct');
            Route::post('/direct-payment', 'ProductController@directPayment');
        });
    });

    Route::get('/reward-products', 'RewardProductsController@index');

    Route::group(['prefix' => 'bundles'], function () {
        Route::get('/', 'BundleController@index');
        Route::get('/{slug}', 'BundleController@show');
        Route::get('/{slug}/free', 'BundleController@free');

        /* Course Points */
        Route::group(['prefix' => '/{slug}/points'], function () {
            Route::get('/apply', 'BundleController@buyWithPoint');
            Route::get('/get-modal', 'BundleController@getBuyWithPointModal');
        });

        Route::get('/{slug}/share-modal', 'BundleController@getShareModal');

        /* Review Load More */
        Route::post('/{slug}/reviews/load-more', 'BundleReviewController@getReviewsByBundleSlug');

        Route::group(['middleware' => 'web.auth'], function () {
            Route::get('/{slug}/favorite', 'BundleController@favoriteToggle');

            Route::group(['prefix' => 'reviews'], function () {
                Route::post('/store', 'BundleReviewController@store');
                Route::post('/store-reply-comment', 'BundleReviewController@storeReplyComment');
                Route::get('/{id}/delete', 'BundleReviewController@destroy');
                Route::get('/{id}/delete-comment/{commentId}', 'BundleReviewController@destroy');
            });

            Route::post('/direct-payment', 'BundleController@directPayment');
        });
    });

    Route::group(['prefix' => 'forums'], function () {
        Route::get('/', 'ForumController@index');
        Route::get('/create-topic', 'ForumController@createTopic');
        Route::post('/create-topic', 'ForumController@storeTopic');
        Route::get('/search', 'ForumController@search');
        Route::get('/attachments/{attachment_id}/delete', 'ForumController@deleteTopicAttachment');

        Route::group(['prefix' => '/{slug}/topics'], function () {
            Route::get('/', 'ForumController@topics');
            Route::post('/{topic_slug}/likeToggle', 'ForumController@topicLikeToggle');
            Route::get('/{topic_slug}/edit', 'ForumController@topicEdit');
            Route::post('/{topic_slug}/edit', 'ForumController@topicUpdate');
            Route::post('/{topic_slug}/bookmark', 'ForumController@topicBookmarkToggle');
            Route::get('/{topic_slug}/downloadAttachment/{attachment_id}', 'ForumController@topicDownloadAttachment');

            Route::group(['prefix' => '/{topic_slug}/posts'], function () {
                Route::get('/', 'ForumTopicPostsController@posts');
                Route::post('/', 'ForumTopicPostsController@storePost');
                Route::get('/report-modal', 'ForumTopicPostsController@getReportModal');
                Route::post('/report', 'ForumTopicPostsController@storeTopicReport');
                Route::get('/{post_id}/edit', 'ForumTopicPostsController@postEdit');
                Route::post('/{post_id}/edit', 'ForumTopicPostsController@postUpdate');
                Route::post('/{post_id}/likeToggle', 'ForumTopicPostsController@postLikeToggle');
                Route::post('/{post_id}/pin-toggle', 'ForumTopicPostsController@postPinToggle');
                Route::get('/{post_id}/downloadAttachment', 'ForumTopicPostsController@postDownloadAttachment');
            });
        });
    });


    Route::group(['prefix' => 'upcoming_courses'], function () {
        Route::get('/', 'UpcomingCoursesController@index');
        Route::get('{slug}', 'UpcomingCoursesController@show');
        Route::get('{slug}/toggleFollow', 'UpcomingCoursesController@toggleFollow');
        Route::get('{slug}/favorite', 'UpcomingCoursesController@favorite');
        Route::post('{id}/report', 'UpcomingCoursesController@report');
        Route::get('/{slug}/share-modal', 'UpcomingCoursesController@getShareModal');
        Route::get('/{slug}/report-modal', 'UpcomingCoursesController@getReportModal');
    });

    Route::group(['prefix' => 'installments'], function () {
        Route::group(['middleware' => 'web.auth'], function () {
            Route::get('/request_submitted', 'InstallmentsController@requestSubmitted');
            Route::get('/request_rejected', 'InstallmentsController@requestRejected');
            Route::get('/{id}', 'InstallmentsController@index');
            Route::post('/{id}/store', 'InstallmentsController@store');
        });
    });

    Route::group(['prefix' => 'gift'], function () {
        Route::group(['middleware' => 'web.auth'], function () {
            Route::get('/{item_type}/{item_slug}', 'GiftController@index');
            Route::post('/{item_type}/{item_slug}', 'GiftController@store');
        });
    });

    /* Forms */
    Route::get('/forms/{url}', 'FormsController@index');
    Route::post('/forms/{url}/store', 'FormsController@store');

    // Get Iconsax
    Route::group(['prefix' => '/iconsax'], function () {
        Route::post("/search", "IconsaxController@search");
    });

    /* Landings */
    Route::group(['prefix' => 'landings'], function () {
        Route::get('/{landing_url}', 'LandingController@index');
    });

    /* Events */
    Route::group(['prefix' => 'events', 'middleware' => 'check_event_feature_status'], function () {

        /* Validation */
        Route::group(['prefix' => 'validation'], function () {
            Route::get('/', 'EventTicketValidationController@index');
            Route::post('/check', 'EventTicketValidationController@checkValidate');
        });

        // Lists & Show
        Route::get('/', 'EventsController@index');
        Route::get('{slug}', 'EventsController@show');
        Route::get('/{slug}/share-modal', 'EventsController@getShareModal');
        Route::get('/{slug}/report-modal', 'EventsController@getReportModal');
        Route::post('{id}/report', 'EventsController@report');

        /* Free Ticket */
        Route::post('/{slug}/tickets/{id}/free', 'EventsController@getFreeTicket');

        /* Reviews */
        Route::group(['prefix' => '{slug}/reviews'], function () {
            Route::post('/load-more', 'EventReviewController@getReviewsByEventSlug');
            Route::post('/store', 'EventReviewController@store');
            Route::post('/store-reply-comment', 'EventReviewController@storeReplyComment');
            Route::get('/{id}/delete', 'EventReviewController@destroy');
            Route::get('/{id}/delete-comment/{commentId}', 'EventReviewController@destroy');
        });

    });

    /* Events */
    Route::group(['prefix' => 'meeting-packages'], function () {
        Route::get('/', 'MeetingPackagesController@index');
        Route::get('/{id}/free', 'MeetingPackagesController@buyFree');
    });
    
    // Afpmanager
    
    //Afpmanager Routes
    Route::post('/afpay-payment-link', [AfpmanagerController::class, 'redirectToPayment'])->name('afpay-payment-link');
    Route::get('/afpay-callback-success', [AfpmanagerController::class, 'paymentSuccessfull'])->name('afpay-callback-success');
    Route::get('/afpay-callback-failed', [AfpmanagerController::class, 'transactionFailed'])->name('afpay-callback-failed');

});

// Purchase Code Routes
Route::get('/purchase-code', [PurchaseCodeController::class, 'show'])->name('purchase.code.show');
Route::post('/purchase-code', [PurchaseCodeController::class, 'store'])->name('purchase.code.store');
