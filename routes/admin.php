<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// use Illuminate\Support\Facades\Mail;
// use App\Mail\Invoice;
// use App\Mail\AbstractSubmission\ThankYou;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'password'], function() use ($router) {
    $router->get('reset', 'ResetPasswordController@showResetForm');
    $router->post('reset', ['as' => 'password.reset', 'uses' => 'ResetPasswordController@reset']);
});

$router->group(['prefix' => 'admin'], function() use ($router) {
    $router->post('login', 'AuthController@loginAdmin');

    // For testing the invoice in Local
    // $router->get('/payment', function () {
        // $user = App\Models\User::where('id', 14)->with('member.registration_type')->first();
        // $payment = App\Models\Payment::where('id', 3)->first();
        // Mail::to($user->email)->send(new Invoice($user, $payment));
        // return new App\Mail\Invoice($user, $payment);
    // });

    // For testing the abstract submission email in Local
    // $router->get('/abstract/thank-you', function () {
    //     $user = App\Models\User::where('id', 10)->with('member')->first();
    //     $abstract_submisssion = App\Models\Abstracts::where('id', 3)->first();
    //     Mail::to($user->email)->send(new ThankYou($user, $abstract_submisssion));
    //     return new ThankYou($user, $abstract_submisssion);
    // });

    $router->group(['prefix' => 'password'], function() use ($router) {
        $router->post('reset-request', 'RequestPasswordController@sendResetLinkEmail');
    });

    $router->group(['middleware' => 'auth'], function() use ($router) {
        $router->post('logout', 'AuthController@logout');

        $router->group(['prefix' => 'user'], function() use ($router) {
            $router->get('', 'AuthController@getAdminUser');
        });

        $router->group(['prefix' => 'members'], function() use ($router) {
            $router->get('', 'ConventionMemberController@getConventionMembers');
            $router->get('all', 'ConventionMemberController@getAllMembers');
            $router->get('pending', 'ConventionMemberController@getPending');
            $router->get('active', 'ConventionMemberController@getActive');
            $router->get('paid', 'ConventionMemberController@getPaid');            
            $router->post('setDefaultPassword', 'AuthController@setDefaultPassword');

            $router->group(['prefix' => '{id}'], function() use ($router) {
                $router->group(['prefix' => 'profile'], function() use ($router) {
                    $router->get('', 'ConventionMemberController@getConventionMember');
                    $router->post('', 'ConventionMemberController@update');
                    $router->delete('', 'ConventionMemberController@delete');
                    $router->post('field', 'ConventionMemberController@updateField'); 
                });
                $router->post('resendPaymentEmail', 'PaymentController@resendPaymentEmail');
            });
        });

        $router->group(['prefix' => 'delegates'], function() use ($router) {
            $router->get('', 'DelegateController@getDelegates');
            $router->get('types', 'DelegateController@getDelegateTypes');

            $router->group(['prefix' => '{id}'], function() use ($router) {
                $router->group(['prefix' => 'profile'], function() use ($router) {
                    $router->get('', 'DelegateController@getDelegate');
                    $router->post('', 'DelegateController@update');
                    $router->delete('', 'DelegateController@delete');
                });
            });

            $router->post('import', 'DelegateController@import');
            $router->group(['prefix' => 'export'], function() use ($router) {
                $router->get('template', 'DelegateController@exportTemplate');
            });
        });

        $router->group(['prefix' => 'abstracts'], function() use ($router) {
            $router->post('admin', 'AbstractController@createFromAdmin');
            $router->get('e-posters', 'AbstractController@getEPosterAbstracts');
            $router->get('free-papers', 'AbstractController@getFreePaperAbstracts');
            $router->get('category', 'CategoryController@getCategories');
            $router->get('authors', 'AbstractController@getAuthors');
            $router->post('', 'AbstractController@create');
            $router->get('', 'AbstractController@search_Authors');

            $router->group(['prefix' => 'categories'], function() use ($router) {
                $router->get('', 'CategoryController@getCategories');
                $router->post('', 'CategoryController@create');

                $router->group(['prefix' => '{id}'], function() use ($router) {
                    $router->get('', 'CategoryController@getCategory');
                    $router->post('', 'CategoryController@update');
                });
            });

            $router->group(['prefix' => '{id}'], function() use ($router) {
                $router->get('', 'AbstractController@getAbstract');
                $router->delete('', 'AbstractController@delete');
                $router->post('', 'AbstractController@update');
                $router->post('resendThankYouEmail', 'AbstractController@resendThankYouEmail');
            });
        });

        $router->group(['prefix' => 'vips'], function() use ($router) {
            $router->get('', 'VIPController@getVIPs');

            $router->group(['prefix' => '{id}'], function() use ($router) {
                $router->group(['prefix' => 'profile'], function() use ($router) {
                    $router->get('', 'VIPController@getVIP');
                    $router->post('', 'VIPController@update');
                    $router->delete('', 'VIPController@delete');
                });
            });

            $router->post('import', 'VIPController@import');
            $router->group(['prefix' => 'export'], function() use ($router) {
                $router->get('template', 'VIPController@exportTemplate');
            });
        });

        $router->group(['prefix' => 'fees'], function() use ($router) {
            $router->get('', 'FeeController@getFees');
            $router->post('', 'FeeController@create');

            $router->group(['prefix' => '{id}'], function() use ($router) {
                $router->get('', 'FeeController@getFee');
                $router->post('', 'FeeController@update');
                $router->delete('', 'FeeController@delete');
            });
        });

        $router->group(['prefix' => 'payments'], function () use ($router) {
            $router->get('export', 'PaymentController@export');
            $router->get('', 'PaymentController@getPaymentLedger');
            $router->get('{member_id}', 'PaymentController@getPaymentHistory');

            $router->group(['prefix' => '{id}'], function () use ($router) {
                $router->delete('', 'PaymentController@delete');
            });
        });

        $router->group(['prefix' => 'orders'], function () use ($router) {
            $router->get('',  'OrderController@getOrders');
            $router->group(['prefix' => 'user'], function () use ($router) {
                $router->get('',  'OrderController@getUserOrders');
                $router->post('update',  'OrderController@update');
                $router->get('{id}', 'DelegateController@getDelegateOrdersAndFees');
                $router->post('updateStatus',  'OrderController@updateStatusAndPayment');
            });
        });

        $router->group(['prefix' => 'countries'], function() use ($router) {
            $router->get('', 'CountryController@getCountries');
        });

        $router->group(['prefix' => 'sponsors'], function() use ($router) {
            $router->get('', 'SponsorController@getSponsors');
            $router->post('', 'SponsorController@create');
            $router->get('types', 'SponsorController@getSponsorTypes');

            $router->group(['prefix' => 'booth-designs'], function() use ($router) {
                $router->get('', 'BoothDesignController@getBoothDesigns');
                $router->post('', 'BoothDesignController@create');
                $router->group(['prefix' => '{id}'], function() use ($router) {
                    $router->get('', 'BoothDesignController@getBoothDesign');
                    $router->post('', 'BoothDesignController@update');
                    $router->delete('', 'BoothDesignController@delete');
                });
            });

            $router->group(['prefix' => 'stamps'], function() use ($router) {
                $router->get('', 'SponsorStampController@getSponsorStamps');
            });

            $router->group(['prefix' => '{id}'], function() use ($router) {
                $router->get('', 'SponsorController@getSponsorByID');
                $router->post('', 'SponsorController@update');
                $router->delete('', 'SponsorController@delete');

                $router->group(['prefix' => 'brochures'], function() use ($router) {
                    $router->get('', 'SponsorBrochureController@getBrochures');
                    $router->post('', 'SponsorBrochureController@create');

                    $router->group(['prefix' => '{brochure_id}'], function() use ($router) {
                        $router->get('', 'SponsorBrochureController@getBrochure');
                        $router->post('', 'SponsorBrochureController@update');
                        $router->delete('', 'SponsorBrochureController@delete');
                    });
                });

                $router->group(['prefix' => 'videos'], function() use ($router) {
                    $router->get('', 'SponsorVideoController@getVideos');
                    $router->post('', 'SponsorVideoController@create');

                    $router->group(['prefix' => '{video_id}'], function() use ($router) {
                        $router->get('', 'SponsorVideoController@getVideo');
                        $router->post('', 'SponsorVideoController@update');
                        $router->delete('', 'SponsorVideoController@delete');
                    });
                });

                $router->group(['prefix' => 'product-catalogue'], function() use ($router) {
                    $router->get('', 'SponsorProductCatalogueController@getProductCatalogues');
                    $router->post('', 'SponsorProductCatalogueController@create');

                    $router->group(['prefix' => '{product_catalogue_id}'], function() use ($router) {
                        $router->get('', 'SponsorProductCatalogueController@getProductCatalogue');
                        $router->post('', 'SponsorProductCatalogueController@update');
                        $router->delete('', 'SponsorProductCatalogueController@delete');
                    });
                });
            });
        });

        $router->group(['prefix' => 'plenaries'], function() use ($router) {
            $router->get('', 'PlenaryEventController@getPlenaryEvents');
            $router->post('', 'PlenaryEventController@create');
            $router->get('days', 'PlenaryEventController@getPlenaryDays');

            $router->group(['prefix' => 'categories'], function() use ($router) {
                $router->get('', 'PlenaryEventController@getCategories');
                $router->post('', 'PlenaryEventController@create');
                $router->group(['prefix' => '{id}'], function() use ($router) {
                    $router->get('', 'PlenaryEventController@getCategory');
                    $router->post('', 'PlenaryEventController@update');
                    $router->delete('', 'PlenaryEventController@delete');
                });
            });

            $router->group(['prefix' => '{id}'], function() use ($router) {
                $router->get('', 'PlenaryEventController@getPlenaryEvent');
                $router->post('', 'PlenaryEventController@update');
                $router->delete('', 'PlenaryEventController@delete');
            });
        });

        $router->group(['prefix' => 'symposia'], function() use ($router) {
            $router->get('', 'SymposiaController@getEvents');
            $router->post('', 'SymposiaController@create');

            $router->group(['prefix' => 'categories'], function() use ($router) {
                $router->get('', 'SymposiaCategoryController@getCategories');
                $router->post('', 'SymposiaCategoryController@create');
                $router->group(['prefix' => '{id}'], function() use ($router) {
                    $router->get('', 'SymposiaCategoryController@getCategory');
                    $router->post('', 'SymposiaCategoryController@update');
                    $router->delete('', 'SymposiaCategoryController@delete');
                });
            });

            $router->group(['prefix' => 'views'], function() use ($router) {
                $router->get('', 'SymposiaViewController@getSymposiaViews');
                $router->get('export', 'SymposiaViewController@export');
            });

            $router->group(['prefix' => '{id}'], function() use ($router) {
                $router->get('', 'SymposiaController@getEvent');
                $router->post('', 'SymposiaController@update');
                $router->delete('', 'SymposiaController@delete');
            });
        });

        $router->group(['prefix' => 'industry-lectures'], function () use ($router) {
            $router->get('',  'SponsorIndustryLectureController@getSponsorIndustryLectures');

            $router->group(['prefix' => '{id}'], function () use ($router) {
                $router->get('', 'SponsorIndustryLectureController@getSponsorIndustryLectureByID');
                $router->post('', 'SponsorIndustryLectureController@update');
            });
        });

        $router->group(['prefix' => 'attendance'], function () use ($router) {
            $router->get('plenary', 'PlenaryAttendanceController@getPlenaryAttendanceList');
            $router->get('workshop', 'WorkshopController@getWorkshopAttendanceList');
            $router->get('export-plenary', 'PlenaryAttendanceController@export');
            $router->get('export-workshop', 'WorkshopController@export');
        });

        $router->group(['prefix' => 'ondemand-logs'], function () use ($router) {
            $router->group(['prefix' => 'plenary'], function () use ($router) {
                $router->get('', 'OnDemandLogsController@getPlenaryLogs');
                $router->get('export', 'OnDemandLogsController@exportPlenary');
            });

            $router->group(['prefix' => 'workshop'], function () use ($router) {
                $router->get('', 'OnDemandLogsController@getWorkshopLogs');
                $router->get('export', 'OnDemandLogsController@exportWorkshop');
            });

            $router->group(['prefix' => 'symposium'], function () use ($router) {
                $router->get('', 'OnDemandLogsController@getSymposiumLogs');
                $router->get('export', 'OnDemandLogsController@exportSymposium');
            });

            $router->group(['prefix' => 'industry'], function () use ($router) {
                $router->get('', 'OnDemandLogsController@getIndustryLogs');
                $router->get('export', 'OnDemandLogsController@exportIndustry');
            });
        });

        $router->group(['prefix' => 'settings'], function () use ($router) {
            $router->group(['prefix' => 'ideapay'], function () use ($router) {
                $router->get('',  'ConfigController@getIdeapayFee');
                $router->post('',  'ConfigController@updateIdeapayFee');
                $router->group(['prefix' => 'rate'], function () use ($router) {
                    $router->get('',  'ConfigController@getIdeapayRate');
                    $router->get('php-usd',  'ConfigController@getPHPRateForUSD');
                });
            });

            $router->group(['prefix' => 'forex'], function () use ($router) {
                $router->get('active',  'ForExRateController@getActivePHPRate');
                $router->post('',  'ForExRateController@create');
            });

            $router->group(['prefix' => 'privacy-policy'], function () use ($router) {
                $router->get('',  'ConfigController@getPrivacyPolicy');
                $router->post('',  'ConfigController@updatePrivacyPolicy');
            });

            $router->group(['prefix' => 'terms-and-conditions'], function () use ($router) {
                $router->get('',  'ConfigController@getTerms');
                $router->post('',  'ConfigController@updateTerms');
            });

            $router->group(['prefix' => 'registration'], function () use ($router) {
                $router->get('',  'ConfigController@getRegistrationSwitch');
                $router->post('',  'ConfigController@updateRegistrationSwitch');
            });

            $router->group(['prefix' => 'abstract-switch'], function () use ($router) {
                $router->get('',  'ConfigController@getAbstactSwitch');
                $router->post('',  'ConfigController@updateAbstractSwitch');
            });

            $router->group(['prefix' => 'payment-switch'], function () use ($router) {
                $router->get('workshop',  'ConfigController@getWorkshopPaymentSwitch');
                $router->post('workshop',  'ConfigController@updateWorkshopPaymentSwitch');
            });

            $router->group(['prefix' => 'vcc'], function () use ($router) {
                $router->group(['prefix' => 'opening-date'], function () use ($router) {
                    $router->get('', 'VirtualConventionController@getOpeningDate');
                    $router->post('', 'VirtualConventionController@updateOpeningDate');
                });

                $router->group(['prefix' => 'workshop'], function () use ($router) {
                    $router->post('{id}',  'WorkshopScheduleController@updateOpeningDateWorkshop');
                    $router->get('', 'WorkshopScheduleController@getOpeningDateWorkshop');
                });
            });
        });
    });
});
