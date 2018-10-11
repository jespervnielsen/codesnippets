/**
 * Toggle filter
 */
function ToggleFilters($) {
  'use strict';
  console.log('jj');
  $(".widget_layered_nav").addClass('filter-toggler').each(function () {

    //close all, where there not is an chosen item. = keep all chosen filters open
    if ($(this).find('li.woocommerce-widget-layered-nav-list__item--chosen').length == 0) {
      $(this).find('.widgettitle').next().toggle(50).parent('.widget_layered_nav').toggleClass('toggle-closed');
    }


    //toogle on click
    $(this).on('click', '.widgettitle', function () {
      $(this).next().toggle(50).parent('.widget_layered_nav').toggleClass('toggle-closed');
    });


  });
}
