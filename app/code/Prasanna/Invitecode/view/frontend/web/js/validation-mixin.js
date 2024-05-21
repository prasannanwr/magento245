define([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'mage/translate'
], function($) {
    'use strict';

    return function(params) {
        var age_limit = params.param;
        $.validator.addMethod(
            'validate-dob',
            function(value, element) {
                //console.log(age_limit);
                var birthday = new Date(value);
                var now = new Date();
                var age = now.getFullYear() - birthday.getFullYear();
                if (now.getMonth() < birthday.getMonth() || (now.getMonth() == birthday.getMonth() && now.getDate() < birthday.getDate())) {
                    age--;
                }
                return age >= age_limit;
            },
            $.mage.__('You must be at least %1 years old.').replace('%1', age_limit)
        )
        //return targetWidget;
    }
});
