require([
    'jquery',
    'uiComponent'
], function (jQuery, Component) {
    jQuery(document).ready(function () {
        // Initially hide max usage
        //jQuery('input[name="code_usage_limit"]').closest('.admin__field').hide();
        // Show/Hide logic based on Reusable value
        jQuery(document).on('change','.admin__control-select',function(e){
            //jQuery(".admin__control-select").change(function (e) {
            if(jQuery(this).attr('name') === "reusable") {
                if (jQuery(this).val() == 1) {
                    jQuery("input[name='code_usage_limit']").closest('.admin__field').show();
                } else {
                    jQuery("input[name='code_usage_limit']").closest('.admin__field').hide();
                }
            }
        });
    });
});
