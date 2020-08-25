(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.hideParagraphsElements = {
    attach: function (context, settings) {
      $(context).find("table[id^=field-paragraph-keydates-values--] h4").hide()
    }
  };
})(jQuery, Drupal);
