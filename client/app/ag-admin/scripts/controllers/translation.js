/*globals $:false */
/* jshint latedef:nofunc */
'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:TranslationsController
 * @description
 * # TranslationsController
 * Controller of the getlancerv3
 */
angular.module('ofos')
    .controller('TranslationsController', function($scope, $http, $filter, $location, notification, $state, $window, $cookies, TranslationsFactory, TranslationFactory, LanguageFactory) {
       // $scope.translationLanguages = [];

       /* Translation get method */
       $scope.Translactions =function()
       {
        TranslationsFactory.get({file_list :'filelist'},function (response) {
            if (angular.isDefined(response)) {
                if (response.error.code === 0) {
                        $scope.translationLanguages = response.data;
                    }
            }
        });
       };
       $scope.Translactions();
      /* Update the translation text function */
        $scope.languageAction = function(code) {
             $state.go('translation_edit', {
                 lang_code : code 
                });
        };
          $scope.loader = true;
          TranslationsFactory.get({lang_code: $state.params.lang_code}, function (response) {
          if (angular.isDefined(response)) {
              if (response.error.code === 0) {
                  $scope.translations = response.data;  
                  }else{
                      $scope.NoRecordFound = true;
                  }
                }
                 $scope.loader = false;
            }); 
      /*  $scope.languageAction('en');*/

      /*  language text change function */
        $scope.UpdateLanuageText =function()
        {
            $scope.data = {};
            $scope.data.keyword = [];
            var datakey = '';
            var datavalue = '';
           /* angular.forEach(document.querySelectorAll('.js-translation'), function(value, key) {
                var ids = value.id; 
                    var id = ids.split('-');
                    datakey = $('#'+'datakey-'+id[1]).val();
                    datavalue = $('#'+'datavalue'+id[1]).val();
                    $scope.data.keyword.push({
                              label : datakey,
                              lang_text : datavalue
                    });
                   
    });*/
         /* update transltion factory*/
            TranslationFactory.put({lang_code : $state.params.lang_code}, $scope.translations, function(response) {
            if (angular.isDefined(response.error.code === 0)) {
                notification.log('Translations text updated successfully', {
                        addnCls: 'humane-flatty-success'
                    });
                     $scope.Translactions();
                     $state.go('translations');
                }else{
                     notification.log('Translations text could not be updated. Please, try again.', {
                        addnCls: 'humane-flatty-error'
                    });
                     $scope.Translactions();
                }
            }); 
          };
          /*languages get method*/
           LanguageFactory.get(function (response) {
               if (angular.isDefined(response)) {
                    if (response.error.code === 0) {
                        $scope.languageList = response.data;
                        }
                   }
            });

          /*  languages add function*/

            $scope.languagetranslationAdd = function(value)
            {
                $scope.data = {};
                $scope.data.lang_code = $scope.languageTo; 
                TranslationsFactory.post($scope.data, function (response) {
                if (angular.isDefined(response.error.code === 0)) {
                    notification.log('New Translation language has been added successfully.', {
                            addnCls: 'humane-flatty-success'
                        });
                        $location.path('/translations/all');
                    }else{
                        notification.log('Translation language could not be added. Please, try again.', {
                            addnCls: 'humane-flatty-error'
                        });
                    }
                }); 
            }
      });  