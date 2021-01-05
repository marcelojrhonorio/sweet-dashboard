<?php

Route::get('/', function () {
    return response()->redirectTo('/login');
});
// Route to dispatch pixel
Route::get('/relationship-rule-pixels/{typeDispatch}/{email}/{value}/{delay}', 'RelationshipRule\RelationshipRulePixelController@pixelDispatch');

// Save BPC Leads
Route::get('/pixels/bpc', 'BpcPixelController@pixelDispatch');
Route::get('/pixels/bpc/approved', 'BpcPixelController@pixelDispatchApproved');

Auth::routes();

Route::post('/login-api', 'LoginApi@auth')->name('login.api');
Route::get('/logout', 'LoginApi@logout')->name('logout.api');

Route::group(['prefix' => 'saveclairvoyant'], function () {
    Route::post('/', 'ClairvoyantController@create')->name('clairvoyant.api.create');
});

Route::get('clairvoyant', 'ClairvoyantController@index');

Route::get('/clairvoyant/cadastros','ClairvoyantController@cadastros');

Route::group(['middleware' => 'access'], function () {
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');    
    Route::get('/dashboard', ['as' => 'index.dashboad', 'uses' => 'DashboardController@index']);

    Route::group(['prefix' => 'companies'], function () {
        Route::get('/', 'CompaniesController@index')->name('index.companies');
        Route::get('/search', 'CompaniesController@search')->name('search.companies');
        Route::get('/create', 'CompaniesController@create')->name('create.companies');
        Route::get('/edit/{id}', 'CompaniesController@edit')->name('edit.companies');
        Route::get('/delete', 'CompaniesController@delete')->name('delete.companies');
        Route::post('/save', 'CompaniesController@save')->name('save.companies');
        Route::post('/update', 'CompaniesController@update')->name('update.companies');
    });

    Route::group(['prefix' => 'campaigns'], function () {
        Route::get('/', 'CampaignsController@index')->name('index.campaigns');
        Route::get('/search', ['as' => 'search.campaigns', 'uses' => 'CampaignsController@search']);
        Route::get('/create', ['as' => 'create.campaigns', 'uses' => 'CampaignsController@create']);
        Route::post('/store', ['as' => 'store.campaigns', 'uses' => 'CampaignsController@store']);
        Route::get('/edit/{id}', ['as' => 'edit.campaigns', 'uses' => 'CampaignsController@edit']);
        Route::get('/status/{active}/{id}', ['as' => 'edit.status.campaigns', 'uses' => 'CampaignsController@status']);
        Route::post('/update', ['as' => 'update.campaigns', 'uses' => 'CampaignsController@update']);

        Route::group(['prefix' => 'clickout'], function () {
            Route::get('/', ['as' => 'index.clickout.campaigns', 'uses' => 'CampaignsClickoutController@index']);
            Route::post('/delete', ['as' => 'delete.clickout.campaigns', 'uses' => 'CampaignsClickoutController@delete']);
            Route::post('/edit', ['as' => 'update.clickout.campaigns', 'uses' => 'CampaignsClickoutController@update']);
            Route::post('/save', ['as' => 'save.clickout.campaigns', 'uses' => 'CampaignsClickoutController@create']);
        });

        Route::group(['prefix' => 'fields'], function () {
            Route::post('/edit', [
                'as'   => 'update.fields.campaigns',
                'uses' => 'CampaignFieldsController@update',
            ]);

            Route::post('/delete', [
                'as'   => 'delete.fields.campaigns',
                'uses' => 'CampaignFieldsController@destroy',
            ]);

            Route::post('/save', [
                'as'   => 'store.fields.campaigns',
                'uses' => 'CampaignFieldsController@store',
            ]);
        });

        Route::group(['prefix' => 'field-types'], function () {
            Route::get('/', 'CampaignFieldTypesController@index');
        });

        Route::group(['prefix' => 'images'], function () {
            Route::post('/upload', ['as' => 'upload.campaigns', 'uses' => 'ImagesController@upload']);
            Route::get('/delete', ['as' => 'delete.image.campaigns', 'uses' => 'ImagesController@delete']);
        });
    });

    Route::group(['prefix' => 'domains'], function () {
        Route::get('/', ['as' => 'index.domains', 'uses' => 'DomainsController@index']);
        Route::get('/search', ['as' => 'search.domains', 'uses' => 'DomainsController@search']);
        Route::get('/create', ['as' => 'create.domains', 'uses' => 'DomainsController@create']);
        Route::get('/edit/{id}', ['as' => 'edit.domains', 'uses' => 'DomainsController@edit']);
        Route::get('/delete', ['as' => 'delete.domains', 'uses' => 'DomainsController@delete']);
        Route::post('/save', ['as' => 'save.domains', 'uses' => 'DomainsController@save']);
        Route::post('/update', ['as' => 'update.domains', 'uses' => 'DomainsController@update']);
    });

    Route::group(['prefix' => 'actions'], function () {
        Route::get('/', [
            'as'   => 'index.actions',
            'uses' => 'ActionsController@index',
        ]);

        Route::post('/', [
            'as'   => 'actions.store',
            'uses' => 'ActionsController@store',
        ]);

        Route::put('/{id}', [
            'as'   => 'actions.update',
            'uses' => 'ActionsController@update',
        ]);

        Route::delete('/{id}', [
            'as'   => 'actions.destroy',
            'uses' => 'ActionsController@destroy',
        ]);

        Route::get('/search', [
            'as'   => 'actions.search',
            'uses' => 'ActionsController@search',
        ]);

        Route::get('/get/{id}', [
            'as'   => 'actions.get',
            'uses' => 'ActionsController@getById',
        ]);

        Route::post('/upload', [
            'as'   => 'actions.upload',
            'uses' => 'ActionsImagesController@store',
        ]);

        Route::post('/upload/image', [
            'as'   => 'actions.upload',
            'uses' => 'ActionsImagesController@uploadImage',
        ]);

        Route::post('/search-filter', [
            'as'   => 'actions.search-filter',
            'uses' => 'ActionsController@searchFilter',
        ]);
    });

    /**
     * Points validation
     */
    Route::group(['prefix' => 'points-validation'], function () {
        Route::group(['prefix' => 'email-forwarding'], function () {
            Route::get('/', [
                'as'   => 'index.email-forwarding',
                'uses' => 'PointsValidation\EmailForwardingController@index',
            ]);

            Route::get('/search', [
                'as'   => 'search.email-forwarding',
                'uses' => 'PointsValidation\EmailForwardingController@search',
            ]);

            Route::get('/edit/{id}', [
                'as'   => 'edit.email-forwarding',
                'uses' => 'PointsValidation\EmailForwardingController@edit',
            ]);

            Route::get('/edit/validation/{id}', [
                'as'   => 'edit.email-forwarding',
                'uses' => 'PointsValidation\EmailForwardingController@validation',
            ]);

            Route::post('/forwarding-ok', [
                'as'   => 'forwarding-ok.email-forwarding',
                'uses' => 'PointsValidation\EmailForwardingController@forwardingOk',
            ]);

            Route::post('/forwarding-not', [
                'as'   => 'forwarding-not.email-forwarding',
                'uses' => 'PointsValidation\EmailForwardingController@forwardingNot',
            ]);
        });
    });

    /**
     * Researches
     */
    Route::group(['prefix' => 'researches'], function () {
        Route::get('/', [
            'as'   => 'index.researches',
            'uses' => 'ResearchesController@index',
        ]);

        Route::post('/', [
            'as'   => 'store.researches',
            'uses' => 'ResearchesController@store',
        ]);

        Route::put('/{id}', [
            'as'   => 'update.researches',
            'uses' => 'ResearchesController@update',
        ]);

        Route::delete('/{id}', [
            'as'   => 'destroy.researches',
            'uses' => 'ResearchesController@destroy',
        ]);

        Route::get('/search', [
            'as'   => 'search.researches',
            'uses' => 'ResearchesController@search',
        ]);

        Route::group(['prefix' => 'pixel'], function () {
            Route::get('/', [
                'as'   => 'index.pixels',
                'uses' => 'ResearchPixelsController@index',
            ]);

            Route::post('/', [
                'as'   => 'store.pixels',
                'uses' => 'ResearchPixelsController@store',
            ]);

            Route::put('/{id}', [
                'as'   => 'update.pixels',
                'uses' => 'ResearchPixelsController@update',
            ]);

            Route::delete('/{id}', [
                'as'   => 'destroy.pixels',
                'uses' => 'ResearchPixelsController@destroy',
            ]);

            Route::get('/search/{id}', [
                'as'   => 'search.pixels',
                'uses' => 'ResearchPixelsController@search',
            ]);

        });

        Route::group(['prefix' => 'sponsored'], function () {
            Route::get('/', [
                'as'   => 'index.researches-sponsored',
                'uses' => 'ResearchesSponsored\ResearchesController@index',
            ]);

            Route::get('/edit/{id}', [
                'as'   => 'edit.researches-sponsored',
                'uses' => 'ResearchesSponsored\ResearchesController@edit',
            ]);

            Route::get('/create', [
                'as'   => 'create.researches-sponsored',
                'uses' => 'ResearchesSponsored\ResearchesController@create',
            ]);

            Route::get('/getResearcheId', [
                'as'   => 'getResearches.researches-sponsored',
                'uses' => 'ResearchesSponsored\ResearchesController@getResearcheId',
            ]);

            Route::get('/search', [
                'as'   => 'search.researches-sponsored',
                'uses' => 'ResearchesSponsored\ResearchesController@search',
            ]);

            Route::post('/store', [
                'as'   => 'store.researches-sponsored',
                'uses' => 'ResearchesSponsored\ResearchesController@store',
            ]);

            Route::post('/verify-url', [
                'as'   => 'verify-url.researches-sponsored',
                'uses' => 'ResearchesSponsored\ResearchesController@verifyUrl',
            ]);

            Route::put('/update/{id}', [
                'as'   => 'update.researches-sponsored',
                'uses' => 'ResearchesSponsored\ResearchesController@update',
            ]);

            Route::delete('/delete/{id}', [
                'as'   => 'delete.researches-sponsored',
                'uses' => 'ResearchesSponsored\ResearchesController@delete',
            ]);

            Route::group(['prefix' => 'question'], function () {
                Route::post('/store', [
                    'as'   => 'store.question-sponsored',
                    'uses' => 'ResearchesSponsored\QuestionsController@store',
                ]);

                Route::get('/getQuestionOptions', [
                    'as'   => 'getQuestionOptions.question-sponsored',
                    'uses' => 'ResearchesSponsored\QuestionsController@getQuestionOptionsFormat',
                ]);

                Route::get('/getQuestionOptionsByQuestion/{id}', [
                    'as'   => 'getQuestionOptions.question-sponsored',
                    'uses' => 'ResearchesSponsored\QuestionsController@getQuestionOptionsByQuestion',
                ]);

                  
            });

            Route::group(['prefix' => 'middle-page'], function () {
                
                Route::post('/icon', [
                    'as'   => 'icon.middle-page',
                    'uses' => 'ResearchesSponsored\MiddlePagesController@icon',
                ]);

                Route::post('/store', [
                    'as'   => 'store.middle-page',
                    'uses' => 'ResearchesSponsored\MiddlePagesController@store',
                ]);

                Route::put('/update/{id}', [
                    'as'   => 'update.middle-page',
                    'uses' => 'ResearchesSponsored\MiddlePagesController@update',
                ]);

                Route::post('/researches-middle-pages', [
                    'as'   => 'researches-middle-pages.middle-page',
                    'uses' => 'ResearchesSponsored\MiddlePagesController@researchesMiddlePages',
                ]);

                Route::post('/get-middle-pages', [
                    'as'   => 'get-middle-pages.middle-page',
                    'uses' => 'ResearchesSponsored\MiddlePagesController@getDataMiddlePage',
                ]);   
                Route::post('/verify-middle-pages', [
                    'as'   => 'verify-middle-pages.middle-page',
                    'uses' => 'ResearchesSponsored\MiddlePagesController@verifyMiddlePage',
                ]);               
                
            });

            Route::group(['prefix' => 'researches-questions'], function () {

                Route::get('/{id}', [
                    'as'   => 'id.researches-questions',
                    'uses' => 'ResearchesSponsored\ResearchesQuestionsController@getResearchesQuestions',
                ]);

                Route::get('/verifyOrdering/{id}', [
                    'as'   => 'verifyOrdering.researches-questions',
                    'uses' => 'ResearchesSponsored\ResearchesQuestionsController@verifyOrdering',
                ]);

                Route::post('/researchQuestions', [
                    'as'   => 'researchQuestions.question-sponsored',
                    'uses' => 'ResearchesSponsored\ResearchesQuestionsController@insertResearchQuestions',
                ]);    
                
                Route::put('/upResearchQuestions', [
                    'as'   => 'researchQuestions.question-sponsored',
                    'uses' => 'ResearchesSponsored\ResearchesQuestionsController@updateResearchQuestions',
                ]); 

                //Route::post('/{id}', [
                //    'as'   => 'update-order.question-sponsored',
                //    'uses' => 'ResearchesSponsored\ResearchesQuestionsController@updateOrderQuestions',
                //]); 

                Route::post('/getResearchQuestion', [
                    'as'   => 'get-research-question.question-sponsored',
                    'uses' => 'ResearchesSponsored\ResearchesQuestionsController@getResearchQuestion',
                ]); 

                Route::post('/deleteResearchQuestion', [
                    'as'   => 'delete-research-question.question-sponsored',
                    'uses' => 'ResearchesSponsored\ResearchesQuestionsController@removeResearchQuestion',
                ]); 

                

            });
            
        });

    });

    /**
     * App Notification
     */
    Route::group(['prefix' => 'app-notification'], function () {
        Route::get('/', [
            'as'   => 'index.app-notification',
            'uses' => 'MobileApp\AppNotificationController@index',
        ]);

        Route::get('/create', [
            'as'   => 'create.app-notification',
            'uses' => 'MobileApp\AppNotificationController@create',
        ]);

        Route::get('/search', [
            'as'   => 'search.app-notification',
            'uses' => 'MobileApp\AppNotificationController@search',
        ]);

        Route::post('/refresh', [
            'as'   => 'refresh.app-notification',
            'uses' => 'MobileApp\AppNotificationController@refresh',
        ]);

        Route::post('/send-message', [
            'as'   => 'send-message.app-notification',
            'uses' => 'MobileApp\AppNotificationController@sendMessage',
        ]);

        Route::post('/store', [
            'as'   => 'store.app-notification',
            'uses' => 'MobileApp\AppNotificationController@store',
        ]);

        Route::post('/create-notification', [
            'as'   => 'create-notification.app-notification',
            'uses' => 'MobileApp\AppNotificationController@createNotification',
        ]);

        Route::post('/cancel', [
            'as'   => 'cancel.app-notification',
            'uses' => 'MobileApp\AppNotificationController@cancel',
        ]);

    });


    

    /**
     * Incentive E-mails
     */
    Route::group(['prefix' => 'incentive-emails'], function () {
        Route::get('/', [
            'as'   => 'index.incentive.emails',
            'uses' => 'IncentiveEmails\IncentiveEmailsController@index',
        ]);

        Route::post('/', [
            'as'   => 'store.incentive.emails',
            'uses' => 'IncentiveEmails\IncentiveEmailsController@store',
        ]);        

        Route::put('/{id}', [
            'as'   => 'update.incentive.emails',
            'uses' => 'IncentiveEmails\IncentiveEmailsController@update',
        ]);

        Route::delete('/{id}', [
            'as'   => 'destroy.incentive.emails',
            'uses' => 'IncentiveEmails\IncentiveEmailsController@destroy',
        ]);

        Route::get('/search', [
            'as'   => 'search.incentive.emails',
            'uses' => 'IncentiveEmails\IncentiveEmailsController@search',
        ]);

    });

    /**
     * Relationship E-mails
     */
    Route::group(['prefix' => 'relationship-rule'], function () {
        Route::get('/', [
            'as'   => 'index.relationship.rule',
            'uses' => 'RelationshipRule\RelationshipRulesController@index',
        ]);

        Route::post('/', [
            'as'   => 'store.relationship.rule',
            'uses' => 'RelationshipRule\RelationshipRulesController@store',
        ]);        

        Route::put('/{id}', [
            'as'   => 'update.relationship.rule',
            'uses' => 'RelationshipRule\RelationshipRulesController@update',
        ]);

        Route::delete('/{id}', [
            'as'   => 'destroy.relationship.rule',
            'uses' => 'RelationshipRule\RelationshipRulesController@destroy',
        ]);

        Route::get('/search', [
            'as'   => 'search.relationship.rule',
            'uses' => 'RelationshipRule\RelationshipRulesController@search',
        ]);

        Route::post('/upload', [
            'as'   =>  'upload.relationship.rule',
            'uses' =>  'RelationshipRule\RelationshipRuleFilesController@store',
        ]);

        Route::get('/download/{fileName}', [
            'as'   =>  'download.relationship.rule',
            'uses' =>  'RelationshipRule\RelationshipRuleFilesController@downloadFile',
        ]);
        
    });

    /**
     * exchanges
     */
    Route::group(['prefix' => 'exchanges'], function () {
        Route::get('/', [
            'as'   => 'index.exchanges',
            'uses' => 'Exchanges\ExchangesController@index',
        ]);

        Route::post('/', [
            'as'   => 'exchanges.store',
            'uses' => 'Exchanges\ExchangesController@store',
        ]);
        
        Route::put('/{id}', [
            'as'   => 'exchanges.update',
            'uses' => 'Exchanges\ExchangesController@update',
        ]);

        Route::post('/update-status', [
            'as'   => 'exchanges.cancel',
            'uses' => 'Exchanges\ExchangesController@updateStatus',
        ]);        

        Route::get('/update-product', [
            'as'   => 'exchanges.update',
            'uses' => 'Exchanges\ExchangesController@updateProduct',
        ]);

        Route::delete('/{id}', [
            'as'   => 'exchanges.destroy',
            'uses' => 'Exchanges\ExchangesController@destroy',
        ]);

        Route::get('/search', [
            'as'   => 'exchanges.search',
            'uses' => 'Exchanges\ExchangesController@search',
        ]);        

        Route::post('/upload', [
            'as'   => 'exchanges.upload',
            'uses' => 'Exchanges\ExchangesController@store',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'exchanges.edit', 
            'uses' => 'Exchanges\ExchangesController@edit'
        ]);

        /**
         * Social network exchanges
         */

        Route::group(['prefix' => 'social-network'], function () {
            Route::get('/', [
                'as'   => 'index.social-network-exchanges',
                'uses' => 'Exchanges\SocialNetwork\SocialNetworkExchangesController@index',
            ]);

            Route::get('/search', [
                'as'   => 'exchanges.search',
                'uses' => 'Exchanges\SocialNetwork\SocialNetworkExchangesController@search',
            ]); 

            Route::post('/update', [
                'as'   => 'exchanges.update',
                'uses' => 'Exchanges\SocialNetwork\SocialNetworkExchangesController@update',
            ]); 
        });
    });

    /**
     * stamps
     */
    Route::group(['prefix' => 'stamps'], function () {
        Route::get('/', [
            'as'   => 'index.stamps',
            'uses' => 'Stamps\StampsController@index',
        ]);

        Route::post('/', [
            'as'   => 'stamps.store',
            'uses' => 'Stamps\StampsController@store',
        ]);

        Route::put('/{id}', [
            'as'   => 'stamps.update',
            'uses' => 'Stamps\StampsController@update',
        ]);

        Route::delete('/{id}', [
            'as'   => 'stamps.destroy',
            'uses' => 'Stamps\StampsController@destroy',
        ]);

        Route::get('/search', [
            'as'   => 'stamps.search',
            'uses' => 'Stamps\StampsController@search',
        ]);        

        Route::post('/upload', [
            'as'   => 'stamps.upload',
            'uses' => 'Stamps\StampsImagesController@store',
        ]);        
    });

    /**
     * products-services
     */
    Route::group(['prefix' => 'products-services'], function () {
        Route::get('/', ['as' => 'index.products.services', 'uses' => 'ProductsServicesController@index']);
        Route::post('/save', ['as' => 'save.products.services', 'uses' => 'ProductsServicesController@save']);
        Route::get('/search', ['as' => 'search.products.services', 'uses' => 'ProductsServicesController@search']);
        Route::post('/update', ['as' => 'update.products.services', 'uses' => 'ProductsServicesController@update']);
        Route::get('/delete', ['as' => 'delete.products.services', 'uses' => 'ProductsServicesController@delete']);

        Route::post('/stamps', ['as' => 'stamps.products.services', 'uses' => 'ProductsServicesController@getProductServiceStamps']);
        Route::get('/stamps/allStamps', ['as' => 'stamps.products.services', 'uses' => 'ProductsServicesController@getStamps']);
        Route::post('/stamps/getStampsById', ['as' => 'stamps.products.services', 'uses' => 'ProductsServicesController@getStampsById']);

        Route::group(['prefix' => 'images'], function () {
            Route::post('/upload', ['as' => 'upload.products.services', 'uses' => 'ImagesController@uploadProductsServices']);
            Route::get('/delete', ['as' => 'delete.products.services', 'uses' => 'ImagesController@delete']);
        });
    });

    Route::group(['prefix' => 'customers'], function () {
        Route::get('/', 'CustomersController@index')->name('index.customers');
        Route::get('/search', ['as' => 'search.customers', 'uses' => 'CustomersController@search']);
        Route::get('/export', ['as' => 'export.customers', 'uses' => 'CustomersController@export']);
        Route::delete('/{id}', ['as' => 'delete.customers', 'uses' => 'CustomersController@destroy']);
        Route::get('/points/list/{id}', ['as' => 'points.customers', 'uses' => 'CustomersController@getPoints']);
        Route::post('/reset-password/{id}', 'CustomersController@resetPassword');
        Route::get('/indications/{id}/{type}', 'CustomersController@getIndications');
        Route::post('/update-status-indications', 'CustomersController@updateStatusIndications');
    });
});
