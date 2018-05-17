'use strict';

deferredBootstrapper.bootstrap({// jshint ignore:line
    element: document.body,
    module: 'ofosApp',
    resolve: {
        SiteSettings: function ($http) {
            var config = {
                headers: {
                    'x-ag-app-id': '4542632501382585',
                    'x-ag-app-secret': '3f7C4l1Y2b0S6a7L8c1E7B3Jo3'
                }
            };
            return $http.get('/api/v1/settings?filter={"where":{"is_front_end_access":"true"},"fields":{"name":"true","value":"true"},"limit":"all","skip":"0"}', config);
        }
    }
});