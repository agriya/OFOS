'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:SearchController
 * @description
 * # SearchController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Common.Translation')
    .factory('languageList', function ($resource) {
        return $resource('/api/v1/languages', { filter: '@filter' }, {
            get: {
                method: 'GET',
            }
        });
    })
    .factory('LocaleService', function ($translate, $rootScope, tmhDynamicLocale, languageList, $cookies, $document, $log) {
        /*jshint -W117 */
        var localesObj;
        var localesObj1 = {};
        localesObj1.locales = {};
        localesObj1.preferredLocale = {};
        var _LOCALES_DISPLAY_NAMES = [];
        var _LOCALES;
        var params = {};
        params.filter = '{"where":{"is_active":1},"order":"name asc","skip":"0","limit":"500"}';
        languageList.get(params, function (response) {
            $.each(response.data, function (i, data) {
                localesObj1.locales[data.iso2] = data.name;
            });
            localesObj1.preferredLocale = response.data[0].iso2;
            localesObj = localesObj1.locales;
            _LOCALES = Object.keys(localesObj);
            if (!_LOCALES || _LOCALES.length === 0) {
                $log.error('There are no _LOCALES provided');
            }
            _LOCALES.forEach(function (locale) {
                _LOCALES_DISPLAY_NAMES.push(localesObj[locale]);
            });
        });
        var currentLocale = $translate.use() || $translate.preferredLanguage(); // because of async loading
        $cookies.put('currentLocale', currentLocale, {
            path: '/'
        });
        var checkLocaleIsValid = function (locale) {
            return _LOCALES.indexOf(locale) !== -1;
        };
        var setLocale = function (locale) {
            if (!checkLocaleIsValid(locale)) {
                $log.error('Locale name "' + locale + '" is invalid');
                return;
            }
            currentLocale = locale;
            $cookies.put('currentLocale', currentLocale, {
                path: '/'
            });
            $translate.use(locale);
        };
        var unregisterTranslateChangeSuccess = $rootScope.$on('translateChangeSuccess', function (event, data) {
            $document[0].documentElement.setAttribute('lang', data.language);
            $rootScope.$emit('changeLanguage', {
                currentLocale: data.language,
            });
            tmhDynamicLocale.set(data.language.toLowerCase()
                .replace(/_/g, '-'));
        });
        $rootScope.$on("$destroy", function () {
            unregisterTranslateChangeSuccess();
        });
        return {
            getLocaleDisplayName: function () {
                if (angular.isDefined(localesObj)) {
                    return localesObj[currentLocale];
                }
            },
            setLocaleByDisplayName: function (localeDisplayName) {
                setLocale(_LOCALES[_LOCALES_DISPLAY_NAMES.indexOf(localeDisplayName)]);
            },
            getLocalesDisplayNames: function () {
                return _LOCALES_DISPLAY_NAMES;
            }
        };
    })
    .directive('ngTranslateLanguageSelect', function (LocaleService) {
        return {
            restrict: 'AE',
            templateUrl: 'scripts/plugins/Common/Translation/views/default/language_translate.html',
            controller: function ($scope, $rootScope, $timeout, languageList) {
                var params = {};
                params.filter = '{"where":{"is_active":1},"order":"name asc","skip":"0","limit":"500"}';
                languageList.get(params, function (response) {
                    if (response.error.code === 0) {
                        $scope.currentLocaleDisplayName = LocaleService.getLocaleDisplayName();
                        $scope.localesDisplayNames = LocaleService.getLocalesDisplayNames();
                        $scope.visible = $scope.localesDisplayNames && $scope.localesDisplayNames.length > 1;
                           if ($rootScope.settings.SITE_LANGUAGE !=="") {
                               response.data.forEach(function (sitelanguage) {
                                    if(sitelanguage.iso2===$rootScope.settings.SITE_LANGUAGE){
                                         $scope.language=sitelanguage.name;
                                     }
                                });
                              LocaleService.setLocaleByDisplayName($scope.language);
                              $scope.currentLocaleDisplayName = LocaleService.getLocaleDisplayName();
                           }
                    }
                });
                $scope.changeLanguage = function (locale) {
                    LocaleService.setLocaleByDisplayName(locale);
                    $scope.currentLocaleDisplayName = LocaleService.getLocaleDisplayName();
                };
            }
        };
    });