jQuery(window).on('elementor/frontend/init', function () {

   var DDDynaic_Tabs = function ($scope, $) {
        // Tab switching logic
         $scope.find('.dd-tab-button').on('click', function () {
         var index = $(this).index();
         $scope.find('.dd-tab-button').removeClass('active');
         $(this).addClass('active');
         $scope.find('.dd-tab-content').hide();
         $scope.find('.dd-tab-content').eq(index).show();
     });
   }

   elementorFrontend.hooks.addAction('frontend/element_ready/dd-dynamic-tabs.default', DDDynaic_Tabs);
});