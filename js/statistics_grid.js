(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.hideParagraphs = {
    attach: function (context, settings) {
      const statisticsGridBlockCount = $('.paragraph-type--statistics-grid').find('tr[class*=draggable]').length;
      if(statisticsGridBlockCount) {
        $('.paragraph-type--statistics-grid').find('span[class*=paragraphs-badge]').html(statisticsGridBlockCount);
      }

      // Display a default of 2 statistics-grid blocks
      if(statisticsGridBlockCount === 1) {
        // Hide the first statistics-grid block while the second one loads
        $('.paragraph-type--statistics-grid').find('tr[class*=draggable]').hide()
        // Add another statistic grid block
        $(context).find("input[id*=field-statistic-block-add-more-add-more-button-statistic-block]").click();
      }
    }
  };
})(jQuery, Drupal);
