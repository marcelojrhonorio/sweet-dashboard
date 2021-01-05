(function () {

    var laroute = (function () {

        var routes = {

            absolute: false,
            rootUrl: 'http://sweetmedia.test',
            routes : [{"host":null,"methods":["GET","HEAD"],"uri":"api\/user","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"\/","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"relationship-rule-pixels\/{typeDispatch}\/{email}\/{value}\/{delay}","name":null,"action":"App\Http\Controllers\RelationshipRule\RelationshipRulePixelController@pixelDispatch"},{"host":null,"methods":["GET","HEAD"],"uri":"login","name":"login","action":"App\Http\Controllers\Auth\LoginController@showLoginForm"},{"host":null,"methods":["POST"],"uri":"login","name":null,"action":"App\Http\Controllers\Auth\LoginController@login"},{"host":null,"methods":["POST"],"uri":"logout","name":"logout","action":"App\Http\Controllers\Auth\LoginController@logout"},{"host":null,"methods":["GET","HEAD"],"uri":"register","name":"register","action":"App\Http\Controllers\Auth\RegisterController@showRegistrationForm"},{"host":null,"methods":["POST"],"uri":"register","name":null,"action":"App\Http\Controllers\Auth\RegisterController@register"},{"host":null,"methods":["GET","HEAD"],"uri":"password\/reset","name":"password.request","action":"App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm"},{"host":null,"methods":["POST"],"uri":"password\/email","name":"password.email","action":"App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail"},{"host":null,"methods":["GET","HEAD"],"uri":"password\/reset\/{token}","name":"password.reset","action":"App\Http\Controllers\Auth\ResetPasswordController@showResetForm"},{"host":null,"methods":["POST"],"uri":"password\/reset","name":null,"action":"App\Http\Controllers\Auth\ResetPasswordController@reset"},{"host":null,"methods":["POST"],"uri":"login-api","name":"login.api","action":"App\Http\Controllers\LoginApi@auth"},{"host":null,"methods":["GET","HEAD"],"uri":"logout","name":"logout.api","action":"App\Http\Controllers\LoginApi@logout"},{"host":null,"methods":["POST"],"uri":"saveclairvoyant","name":"clairvoyant.api.create","action":"App\Http\Controllers\ClairvoyantController@create"},{"host":null,"methods":["GET","HEAD"],"uri":"clairvoyant","name":null,"action":"App\Http\Controllers\ClairvoyantController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"clairvoyant\/cadastros","name":null,"action":"App\Http\Controllers\ClairvoyantController@cadastros"},{"host":null,"methods":["GET","HEAD"],"uri":"logs","name":null,"action":"\Rap2hpoutre\LaravelLogViewer\LogViewerController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"dashboard","name":"index.dashboad","action":"App\Http\Controllers\DashboardController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"companies","name":"index.companies","action":"App\Http\Controllers\CompaniesController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"companies\/search","name":"search.companies","action":"App\Http\Controllers\CompaniesController@search"},{"host":null,"methods":["GET","HEAD"],"uri":"companies\/create","name":"create.companies","action":"App\Http\Controllers\CompaniesController@create"},{"host":null,"methods":["GET","HEAD"],"uri":"companies\/edit\/{id}","name":"edit.companies","action":"App\Http\Controllers\CompaniesController@edit"},{"host":null,"methods":["GET","HEAD"],"uri":"companies\/delete","name":"delete.companies","action":"App\Http\Controllers\CompaniesController@delete"},{"host":null,"methods":["POST"],"uri":"companies\/save","name":"save.companies","action":"App\Http\Controllers\CompaniesController@save"},{"host":null,"methods":["POST"],"uri":"companies\/update","name":"update.companies","action":"App\Http\Controllers\CompaniesController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"campaigns","name":"index.campaigns","action":"App\Http\Controllers\CampaignsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"campaigns\/search","name":"search.campaigns","action":"App\Http\Controllers\CampaignsController@search"},{"host":null,"methods":["GET","HEAD"],"uri":"campaigns\/create","name":"create.campaigns","action":"App\Http\Controllers\CampaignsController@create"},{"host":null,"methods":["POST"],"uri":"campaigns\/store","name":"store.campaigns","action":"App\Http\Controllers\CampaignsController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"campaigns\/edit\/{id}","name":"edit.campaigns","action":"App\Http\Controllers\CampaignsController@edit"},{"host":null,"methods":["GET","HEAD"],"uri":"campaigns\/status\/{active}\/{id}","name":"edit.status.campaigns","action":"App\Http\Controllers\CampaignsController@status"},{"host":null,"methods":["POST"],"uri":"campaigns\/update","name":"update.campaigns","action":"App\Http\Controllers\CampaignsController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"campaigns\/clickout","name":"index.clickout.campaigns","action":"App\Http\Controllers\CampaignsClickoutController@index"},{"host":null,"methods":["POST"],"uri":"campaigns\/clickout\/delete","name":"delete.clickout.campaigns","action":"App\Http\Controllers\CampaignsClickoutController@delete"},{"host":null,"methods":["POST"],"uri":"campaigns\/clickout\/edit","name":"update.clickout.campaigns","action":"App\Http\Controllers\CampaignsClickoutController@update"},{"host":null,"methods":["POST"],"uri":"campaigns\/clickout\/save","name":"save.clickout.campaigns","action":"App\Http\Controllers\CampaignsClickoutController@create"},{"host":null,"methods":["POST"],"uri":"campaigns\/fields\/edit","name":"update.fields.campaigns","action":"App\Http\Controllers\CampaignFieldsController@update"},{"host":null,"methods":["POST"],"uri":"campaigns\/fields\/delete","name":"delete.fields.campaigns","action":"App\Http\Controllers\CampaignFieldsController@destroy"},{"host":null,"methods":["POST"],"uri":"campaigns\/fields\/save","name":"store.fields.campaigns","action":"App\Http\Controllers\CampaignFieldsController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"campaigns\/field-types","name":null,"action":"App\Http\Controllers\CampaignFieldTypesController@index"},{"host":null,"methods":["POST"],"uri":"campaigns\/images\/upload","name":"upload.campaigns","action":"App\Http\Controllers\ImagesController@upload"},{"host":null,"methods":["GET","HEAD"],"uri":"campaigns\/images\/delete","name":"delete.image.campaigns","action":"App\Http\Controllers\ImagesController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"domains","name":"index.domains","action":"App\Http\Controllers\DomainsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"domains\/search","name":"search.domains","action":"App\Http\Controllers\DomainsController@search"},{"host":null,"methods":["GET","HEAD"],"uri":"domains\/create","name":"create.domains","action":"App\Http\Controllers\DomainsController@create"},{"host":null,"methods":["GET","HEAD"],"uri":"domains\/edit\/{id}","name":"edit.domains","action":"App\Http\Controllers\DomainsController@edit"},{"host":null,"methods":["GET","HEAD"],"uri":"domains\/delete","name":"delete.domains","action":"App\Http\Controllers\DomainsController@delete"},{"host":null,"methods":["POST"],"uri":"domains\/save","name":"save.domains","action":"App\Http\Controllers\DomainsController@save"},{"host":null,"methods":["POST"],"uri":"domains\/update","name":"update.domains","action":"App\Http\Controllers\DomainsController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"actions","name":"index.actions","action":"App\Http\Controllers\ActionsController@index"},{"host":null,"methods":["POST"],"uri":"actions","name":"actions.store","action":"App\Http\Controllers\ActionsController@store"},{"host":null,"methods":["PUT"],"uri":"actions\/{id}","name":"actions.update","action":"App\Http\Controllers\ActionsController@update"},{"host":null,"methods":["DELETE"],"uri":"actions\/{id}","name":"actions.destroy","action":"App\Http\Controllers\ActionsController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"actions\/search","name":"actions.search","action":"App\Http\Controllers\ActionsController@search"},{"host":null,"methods":["POST"],"uri":"actions\/upload","name":"actions.upload","action":"App\Http\Controllers\ActionsImagesController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"researches","name":"index.researches","action":"App\Http\Controllers\ResearchesController@index"},{"host":null,"methods":["POST"],"uri":"researches","name":"store.researches","action":"App\Http\Controllers\ResearchesController@store"},{"host":null,"methods":["PUT"],"uri":"researches\/{id}","name":"update.researches","action":"App\Http\Controllers\ResearchesController@update"},{"host":null,"methods":["DELETE"],"uri":"researches\/{id}","name":"destroy.researches","action":"App\Http\Controllers\ResearchesController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"researches\/search","name":"search.researches","action":"App\Http\Controllers\ResearchesController@search"},{"host":null,"methods":["GET","HEAD"],"uri":"researches\/pixel","name":"index.pixels","action":"App\Http\Controllers\ResearchPixelsController@index"},{"host":null,"methods":["POST"],"uri":"researches\/pixel","name":"store.pixels","action":"App\Http\Controllers\ResearchPixelsController@store"},{"host":null,"methods":["PUT"],"uri":"researches\/pixel\/{id}","name":"update.pixels","action":"App\Http\Controllers\ResearchPixelsController@update"},{"host":null,"methods":["DELETE"],"uri":"researches\/pixel\/{id}","name":"destroy.pixels","action":"App\Http\Controllers\ResearchPixelsController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"researches\/pixel\/search\/{id}","name":"search.pixels","action":"App\Http\Controllers\ResearchPixelsController@search"},{"host":null,"methods":["GET","HEAD"],"uri":"incentive-emails","name":"index.incentive.emails","action":"App\Http\Controllers\IncentiveEmails\IncentiveEmailsController@index"},{"host":null,"methods":["POST"],"uri":"incentive-emails","name":"store.incentive.emails","action":"App\Http\Controllers\IncentiveEmails\IncentiveEmailsController@store"},{"host":null,"methods":["PUT"],"uri":"incentive-emails\/{id}","name":"update.incentive.emails","action":"App\Http\Controllers\IncentiveEmails\IncentiveEmailsController@update"},{"host":null,"methods":["DELETE"],"uri":"incentive-emails\/{id}","name":"destroy.incentive.emails","action":"App\Http\Controllers\IncentiveEmails\IncentiveEmailsController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"incentive-emails\/search","name":"search.incentive.emails","action":"App\Http\Controllers\IncentiveEmails\IncentiveEmailsController@search"},{"host":null,"methods":["GET","HEAD"],"uri":"relationship-rule","name":"index.relationship.rule","action":"App\Http\Controllers\RelationshipRule\RelationshipRulesController@index"},{"host":null,"methods":["POST"],"uri":"relationship-rule","name":"store.relationship.rule","action":"App\Http\Controllers\RelationshipRule\RelationshipRulesController@store"},{"host":null,"methods":["PUT"],"uri":"relationship-rule\/{id}","name":"update.relationship.rule","action":"App\Http\Controllers\RelationshipRule\RelationshipRulesController@update"},{"host":null,"methods":["DELETE"],"uri":"relationship-rule\/{id}","name":"destroy.relationship.rule","action":"App\Http\Controllers\RelationshipRule\RelationshipRulesController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"relationship-rule\/search","name":"search.relationship.rule","action":"App\Http\Controllers\RelationshipRule\RelationshipRulesController@search"},{"host":null,"methods":["POST"],"uri":"relationship-rule\/upload","name":"upload.relationship.rule","action":"App\Http\Controllers\RelationshipRule\RelationshipRuleFilesController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"relationship-rule\/download\/{fileName}","name":"download.relationship.rule","action":"App\Http\Controllers\RelationshipRule\RelationshipRuleFilesController@downloadFile"},{"host":null,"methods":["GET","HEAD"],"uri":"exchanges","name":"index.exchanges","action":"App\Http\Controllers\Exchanges\ExchangesController@index"},{"host":null,"methods":["POST"],"uri":"exchanges","name":"exchanges.store","action":"App\Http\Controllers\Exchanges\ExchangesController@store"},{"host":null,"methods":["PUT"],"uri":"exchanges\/{id}","name":"exchanges.update","action":"App\Http\Controllers\Exchanges\ExchangesController@update"},{"host":null,"methods":["DELETE"],"uri":"exchanges\/{id}","name":"exchanges.destroy","action":"App\Http\Controllers\Exchanges\ExchangesController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"exchanges\/search","name":"exchanges.search","action":"App\Http\Controllers\Exchanges\ExchangesController@search"},{"host":null,"methods":["POST"],"uri":"exchanges\/upload","name":"exchanges.upload","action":"App\Http\Controllers\Exchanges\ExchangesController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"exchanges\/edit\/{id}","name":"exchanges.edit","action":"App\Http\Controllers\Exchanges\ExchangesController@edit"},{"host":null,"methods":["GET","HEAD"],"uri":"stamps","name":"index.stamps","action":"App\Http\Controllers\Stamps\StampsController@index"},{"host":null,"methods":["POST"],"uri":"stamps","name":"stamps.store","action":"App\Http\Controllers\Stamps\StampsController@store"},{"host":null,"methods":["PUT"],"uri":"stamps\/{id}","name":"stamps.update","action":"App\Http\Controllers\Stamps\StampsController@update"},{"host":null,"methods":["DELETE"],"uri":"stamps\/{id}","name":"stamps.destroy","action":"App\Http\Controllers\Stamps\StampsController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"stamps\/search","name":"stamps.search","action":"App\Http\Controllers\Stamps\StampsController@search"},{"host":null,"methods":["POST"],"uri":"stamps\/upload","name":"stamps.upload","action":"App\Http\Controllers\Stamps\StampsImagesController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"products-services","name":"index.products.services","action":"App\Http\Controllers\ProductsServicesController@index"},{"host":null,"methods":["POST"],"uri":"products-services\/save","name":"save.products.services","action":"App\Http\Controllers\ProductsServicesController@save"},{"host":null,"methods":["GET","HEAD"],"uri":"products-services\/search","name":"search.products.services","action":"App\Http\Controllers\ProductsServicesController@search"},{"host":null,"methods":["POST"],"uri":"products-services\/update","name":"update.products.services","action":"App\Http\Controllers\ProductsServicesController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"products-services\/delete","name":"delete.products.services","action":"App\Http\Controllers\ProductsServicesController@delete"},{"host":null,"methods":["POST"],"uri":"products-services\/images\/upload","name":"upload.products.services","action":"App\Http\Controllers\ImagesController@uploadProductsServices"},{"host":null,"methods":["GET","HEAD"],"uri":"products-services\/images\/delete","name":"delete.products.services","action":"App\Http\Controllers\ImagesController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"customers","name":"index.customers","action":"App\Http\Controllers\CustomersController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"customers\/search","name":"search.customers","action":"App\Http\Controllers\CustomersController@search"},{"host":null,"methods":["GET","HEAD"],"uri":"customers\/export","name":"export.customers","action":"App\Http\Controllers\CustomersController@export"},{"host":null,"methods":["DELETE"],"uri":"customers\/{id}","name":"delete.customers","action":"App\Http\Controllers\CustomersController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"customers\/points\/list\/{id}","name":"points.customers","action":"App\Http\Controllers\CustomersController@getPoints"}],
            prefix: '',

            route : function (name, parameters, route) {
                route = route || this.getByName(name);

                if ( ! route ) {
                    return undefined;
                }

                return this.toRoute(route, parameters);
            },

            url: function (url, parameters) {
                parameters = parameters || [];

                var uri = url + '/' + parameters.join('/');

                return this.getCorrectUrl(uri);
            },

            toRoute : function (route, parameters) {
                var uri = this.replaceNamedParameters(route.uri, parameters);
                var qs  = this.getRouteQueryString(parameters);

                if (this.absolute && this.isOtherHost(route)){
                    return "//" + route.host + "/" + uri + qs;
                }

                return this.getCorrectUrl(uri + qs);
            },

            isOtherHost: function (route){
                return route.host && route.host != window.location.hostname;
            },

            replaceNamedParameters : function (uri, parameters) {
                uri = uri.replace(/\{(.*?)\??\}/g, function(match, key) {
                    if (parameters.hasOwnProperty(key)) {
                        var value = parameters[key];
                        delete parameters[key];
                        return value;
                    } else {
                        return match;
                    }
                });

                // Strip out any optional parameters that were not given
                uri = uri.replace(/\/\{.*?\?\}/g, '');

                return uri;
            },

            getRouteQueryString : function (parameters) {
                var qs = [];
                for (var key in parameters) {
                    if (parameters.hasOwnProperty(key)) {
                        qs.push(key + '=' + parameters[key]);
                    }
                }

                if (qs.length < 1) {
                    return '';
                }

                return '?' + qs.join('&');
            },

            getByName : function (name) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].name === name) {
                        return this.routes[key];
                    }
                }
            },

            getByAction : function(action) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].action === action) {
                        return this.routes[key];
                    }
                }
            },

            getCorrectUrl: function (uri) {
                var url = this.prefix + '/' + uri.replace(/^\/?/, '');

                if ( ! this.absolute) {
                    return url;
                }

                return this.rootUrl.replace('/\/?$/', '') + url;
            }
        };

        var getLinkAttributes = function(attributes) {
            if ( ! attributes) {
                return '';
            }

            var attrs = [];
            for (var key in attributes) {
                if (attributes.hasOwnProperty(key)) {
                    attrs.push(key + '="' + attributes[key] + '"');
                }
            }

            return attrs.join(' ');
        };

        var getHtmlLink = function (url, title, attributes) {
            title      = title || url;
            attributes = getLinkAttributes(attributes);

            return '<a href="' + url + '" ' + attributes + '>' + title + '</a>';
        };

        return {
            // Generate a url for a given controller action.
            // laroute.action('HomeController@getIndex', [params = {}])
            action : function (name, parameters) {
                parameters = parameters || {};

                return routes.route(name, parameters, routes.getByAction(name));
            },

            // Generate a url for a given named route.
            // laroute.route('routeName', [params = {}])
            route : function (route, parameters) {
                parameters = parameters || {};

                return routes.route(route, parameters);
            },

            // Generate a fully qualified URL to the given path.
            // laroute.route('url', [params = {}])
            url : function (route, parameters) {
                parameters = parameters || {};

                return routes.url(route, parameters);
            },

            // Generate a html link to the given url.
            // laroute.link_to('foo/bar', [title = url], [attributes = {}])
            link_to : function (url, title, attributes) {
                url = this.url(url);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given route.
            // laroute.link_to_route('route.name', [title=url], [parameters = {}], [attributes = {}])
            link_to_route : function (route, title, parameters, attributes) {
                var url = this.route(route, parameters);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given controller action.
            // laroute.link_to_action('HomeController@getIndex', [title=url], [parameters = {}], [attributes = {}])
            link_to_action : function(action, title, parameters, attributes) {
                var url = this.action(action, parameters);

                return getHtmlLink(url, title, attributes);
            }

        };

    }).call(this);

    /**
     * Expose the class either via AMD, CommonJS or the global object
     */
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return laroute;
        });
    }
    else if (typeof module === 'object' && module.exports){
        module.exports = laroute;
    }
    else {
        window.laroute = laroute;
    }

}).call(this);

