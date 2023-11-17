jQuery(window).on('elementor/frontend/init', function () {

   var DD_Nav_Tree = function ($scope, $) {
      var toggler = $scope.find('.dd-navigation-tree li .sub-toggler');
      $(toggler).on('click', function(e){
         var $this = $(this);
         $this.parent().find('ul').first().slideToggle(200);
         $this.toggleClass('opened');
      })

      // auto open current item on menu
      if ($scope.hasClass('dd-unfold-current')) {
         var to_toggle_li = $scope.find('li.current-menu-ancestor');
         $(to_toggle_li).each(function(){
            $(this).find('.sub-toggler').first().click();
         });
      }
   }

   elementorFrontend.hooks.addAction('frontend/element_ready/dd-navigation-menu-tree.default', DD_Nav_Tree);
});