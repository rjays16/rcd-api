<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'convention'], function() use ($router) {
    $router->post('login', 'AuthController@loginMember');

     $router->get('/registration-success', function () {
        return view('ideapay.success');
    });

    $router->get('privacy-policy',  'ConfigController@getPrivacyPolicy');
    $router->get('terms-and-conditions',  'ConfigController@getTerms');
    $router->get('registration-switch', 'ConfigController@getRegistrationSwitch');

    $router->group(['prefix' => 'register'], function() use ($router) {
        $router->post('', 'RegistrationController@register');
        $router->post('vip', 'VIPRegistrationController@register');
        $router->post('ideapay', 'IdeapayController@create');

        $router->group(['prefix' => 'delegate'], function() use ($router) {
            $router->post('walkin', 'DelegateRegistrationController@registerWalkin');
            $router->post('existing', 'DelegateRegistrationController@registerExisting');
        });

        $router->group(['prefix' => 'payment'], function() use ($router) {
            $router->post('', 'PaymentController@create');
            $router->post('free', 'PaymentController@createFree');
        });
    });

    $router->group(['prefix' => 'orders'], function() use ($router) {
        $router->post('rates', 'OrderController@calculateRates');
    });

    $router->group(['prefix' => 'rates'], function() use ($router) {
        $router->post('convert', 'OrderController@convertAmount');
    });

    $router->group(['prefix' => 'password'], function() use ($router) {
        $router->post('reset-request', 'RequestPasswordController@sendResetLinkEmail');
    });

    $router->group(['prefix' => 'countries'], function() use ($router) {
        $router->get('', 'CountryController@getCountries');
        $router->get('specific_country', 'CountryController@getCountries_specific');
        $router->get('customed_country', 'CountryController@getCountries_customed');
    });

    $router->group(['prefix' => 'sponsors'], function () use ($router) {
        $router->get('',  'SponsorController@getSponsors');
        $router->get('type',  'SponsorController@getSponsorsByType');
        $router->post('stamps', 'SponsorStampController@create');
        $router->post('visit-logs', 'SponsorVisitLogController@create');

        $router->group(['prefix' => 'chat'], function() use ($router) {
            $router->post('messages', 'AttendeeChatController@getMessages');
            $router->post('send-message', 'AttendeeChatController@sendMessage');
        });

        $router->group(['prefix' => '{slug}'], function () use ($router) {
            $router->get('', 'SponsorController@getSponsorBySlug');
            $router->get('brochures', 'SponsorBrochureController@getBrochures');
            $router->get('videos', 'SponsorVideoController@getVideos');
        });
        $router->group(['prefix' => '{id}'], function () use ($router) {
            $router->group(['prefix' => 'analytics'], function () use ($router) {
                $router->post('asset', 'SponsorAnalyticsController@updateAssetStatistic');
                $router->post('kuula', 'SponsorAnalyticsController@updateNumCompanyProfileViews');
                $router->post('company-profile', 'SponsorAnalyticsController@updateNum360Views');
//                $router->post('updateName', 'SponsorAnalyticsController@updateName');
            });
        });
    });

    $router->group(['prefix' => 'training-institutions'], function() use ($router) {
        $router->get('', 'TrainingInstitutionController@getTrainingInstitutions');
    });

    $router->get('abstract-switch',  'ConfigController@getAbstactSwitch');
    $router->group(['middleware' => 'auth'], function() use ($router) {
        $router->post('logout', 'AuthController@logout');

        $router->group(['prefix' => 'user'], function() use ($router) {
            $router->get('', 'AuthController@getUser');
            $router->post('update-password', 'AuthController@updatePassword');
        });

        $router->group(['prefix' => 'payments'], function () use ($router) {
            $router->get('{member_id}', 'PaymentController@getPaymentHistory');
        });

        $router->group(['prefix' => 'orders'], function () use ($router) {
            $router->get('{id}', 'OrderController@getOrder');
        });

        $router->group(['prefix' => 'abstract'], function() use ($router) {
            $router->get('', 'AbstractController@getUserAbstracts');
            $router->post('', 'AbstractController@create');

            $router->group(['prefix' => 'e-posters'], function() use ($router) {
                $router->get('', 'AbstractController@getEPosterAbstractFinalists');
                $router->get('categories', 'CategoryController@getEPosterCategories');
            });

            $router->group(['prefix' => 'free-papers'], function() use ($router) {
                $router->get('', 'AbstractController@getFreepaperAbstractFinalists');
                $router->get('categories', 'CategoryController@getFreePaperCategories');
            });

            $router->get('categories', 'CategoryController@getCategories');
            $router->get('design', 'StudyDesignController@Study');
            $router->get('{id}', 'AbstractController@getAbstract');
        });

        $router->group(['prefix' => 'member'], function () use ($router) {
            $router->post('ideapay', 'IdeapayController@create');

            $router->group(['prefix' => '{id}/edit'], function () use ($router) {
                $router->post('', 'AuthController@update');
                $router->post('field', 'AuthController@updateField');
            });

            $router->group(['prefix' => 'order'], function () use ($router) {
                $router->post('', 'OrderController@create');
                $router->post('cancel', 'OrderController@cancel');
                $router->post('undo-cancellation', 'OrderController@undoCancellation');
                $router->group(['prefix' => 'fee'], function() use ($router) {
                    $router->get('{id}', 'DelegateController@getDelegateOrdersAndFees');
                });
            });
        });

        $router->group(['prefix' => 'vcc'], function () use ($router) {
            $router->group(['prefix' => 'opening-date'], function () use ($router) {
                $router->get('',  'VirtualConventionController@getOpeningDate');
                $router->post('',  'VirtualConventionController@updateOpeningDate');
            });
            $router->post('log', 'LogController@create');

            $router->get('stamps', 'SponsorStampController@getUserStamps');
            $router->get('', 'PlenaryAttendanceController@getPlenaryEvents');

            $router->group(['prefix' => 'workshop'], function () use ($router) {
                $router->get('', 'WorkshopScheduleController@getOpeningDateWorkshop');
                $router->get('{id}/{dates}', 'WorkshopScheduleController@getDateSchedule');
                $router->get('/user/{id}/attendance', 'WorkshopController@getWorkshopAttendanceByDelegate');
            });

            $router->group(['prefix' => 'plenary'], function () use ($router) {
                $router->get('/user/{id}/attendance', 'PlenaryAttendanceController@getPlenaryAttendanceByDelegate');
                $router->group(['prefix' => 'events'], function () use ($router) {
                    $router->get('', 'PlenaryEventController@getPlenaryEventsByDate');
                });
                $router->group(['prefix' => 'attendance'], function () use ($router) {
                    $router->get('', 'PlenaryAttendanceController@getPlenaryAttendance');
                    $router->post('', 'PlenaryAttendanceController@create');
                    $router->post('logout', 'PlenaryAttendanceController@logoutMember');
                });
            });

            $router->group(['prefix' => 'symposia'], function () use ($router) {
                $router->get('categorized-events', 'SymposiaController@getCategorizedEvents');
                $router->post('views', 'SymposiaViewController@create');
            });

            $router->group(['prefix' => 'industry-lectures'], function () use ($router) {
                $router->get('',  'SponsorIndustryLectureController@getSponsorIndustryLectures');

                $router->group(['prefix' => '{slug}'], function () use ($router) {
                    $router->get('', 'SponsorIndustryLectureController@getSponsorIndustryLectureBySlug');
                });
            });

            $router->group(['prefix' => 'payment-switch'], function () use ($router) {
                $router->get('workshop', 'ConfigController@getWorkshopPaymentSwitch');                
            });
        });
    });
});

$router->group(['prefix' => 'web'], function () use ($router) {
    $router->get('payment', 'IdeapayController@verifyOrderStatus');
});
