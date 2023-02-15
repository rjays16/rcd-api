<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'password'], function() use ($router) {
    $router->get('reset', 'ResetPasswordController@showResetForm');
    $router->post('reset', ['as' => 'password.reset', 'uses' => 'ResetPasswordController@reset']);
});

$router->group(['prefix' => 'sponsor'], function() use ($router) {
    $router->post('login', 'AuthController@loginSponsor');

    $router->group(['prefix' => 'password'], function() use ($router) {
        $router->post('reset-request', 'RequestPasswordController@sendResetLinkEmail');
    });

    $router->group(['middleware' => 'auth'], function() use ($router) {
        $router->post('logout', 'AuthController@logout');

        $router->group(['prefix' => 'user'], function() use ($router) {
            $router->get('', 'AuthController@getSponsorUser');
        });

        $router->group(['prefix' => 'chat'], function() use ($router) {
            $router->get('attendees', 'SponsorChatController@getAttendees');
            $router->get('attendee-sponsor', 'SponsorChatController@getAttendeeWithSponsorMessages');
            $router->post('send-message', 'SponsorChatController@sendMessage');
        });

        $router->group(['prefix' => '{id}'], function() use ($router) {
            $router->group(['prefix' => 'information'], function() use ($router) {
                $router->get('', 'SponsorController@getSponsorByID');
                $router->post('', 'SponsorController@update');
            });

            $router->group(['prefix' => 'stamps'], function() use ($router) {
                $router->get('', 'SponsorStampController@getStampsOfSponsor');
                $router->get('export', 'SponsorStampController@export');
            });

            $router->group(['prefix' => 'chat'], function() use ($router) {
                $router->group(['prefix' => 'export'], function() use ($router) {
                    $router->get('', 'SponsorChatController@export');
                    $router->get('visitors-only', 'SponsorChatController@exportVisitorsOnly');
                });
            });

            $router->group(['prefix' => 'visit-logs'], function() use ($router) {
                $router->get('', 'SponsorVisitLogController@getSponsorVisitLogs');
                $router->get('count', 'SponsorVisitLogController@getSponsorVisitLogCount');
                $router->get('export', 'SponsorVisitLogController@export');
            });

            $router->group(['prefix' => 'brochures'], function() use ($router) {
                $router->get('', 'SponsorBrochureController@getBrochures');
                $router->post('', 'SponsorBrochureController@create');
                $router->post('upload', 'SponsorBrochureController@uploadBrochure');

                $router->group(['prefix' => '{brochure_id}'], function() use ($router) {
                    $router->get('', 'SponsorBrochureController@getBrochure');
                    $router->post('', 'SponsorBrochureController@update');
                    $router->delete('', 'SponsorBrochureController@delete');
                });
            });

            $router->group(['prefix' => 'videos'], function() use ($router) {
                $router->get('', 'SponsorVideoController@getVideos');
                $router->post('', 'SponsorVideoController@create');
                $router->post('upload', 'SponsorVideoController@uploadVideo');

                $router->group(['prefix' => '{video_id}'], function() use ($router) {
                    $router->get('', 'SponsorVideoController@getVideo');
                    $router->post('', 'SponsorVideoController@update');
                    $router->delete('', 'SponsorVideoController@delete');
                });
            });

            $router->group(['prefix' => 'product-catalogue'], function() use ($router) {
                $router->get('', 'SponsorProductCatalogueController@getProductCatalogues');
                $router->post('', 'SponsorProductCatalogueController@create');
                $router->post('upload', 'SponsorProductCatalogueController@uploadProductCatalogue');

                $router->group(['prefix' => '{product_catalogue_id}'], function() use ($router) {
                    $router->get('', 'SponsorProductCatalogueController@getProductCatalogue');
                    $router->post('', 'SponsorProductCatalogueController@update');
                    $router->delete('', 'SponsorProductCatalogueController@delete');
                });
            });

            $router->group(['prefix' => 'analytics'], function() use ($router) {
                $router->get('', 'SponsorAnalyticsController@getAnalytics');
                $router->get('export', 'SponsorAnalyticsController@export');
            });
        });
    });
});
