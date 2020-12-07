/*!
 * Modificado por José Javier Fernández Mendoza
 * Versin 1.0.2
 * Original:
 * Ambiance - Notification Plugin for jQuery
 * Version 1.0.1
 * @requires jQuery v1.7.2
 *
 * Copyright (c) 2012 Richard Hsu
 * Documentation: http://www.github.com/richardhsu/jquery.ambiance
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 */

(function($) {
  $.fn.ambiance = function(options) {

    var defaults = {
      title: "",
      message: "",
      link: "",
      linkName: "",
      linkBlank: false,
      linkColor: "",
      type: "default",
      permanent: false,
      timeout: 2,
      fade: true,
      width: 300,
      // Additional user-defined class for future reference
      extraClass: null
    };

    var options = $.extend(defaults, options);
    var note_area = $("#ambiance-notification");

    // Construct the new notification.
    var note = $(window.document.createElement('div'))
                 .addClass("ambiance")
                 .addClass("ambiance-" + options['type']);

    //Add additional custom class for future selection of the new note from outside.
    if (options['extraClass'] != null) {
      note.addClass(options['extraClass']);
    }

    note.css({width: options['width']});

    // Deal with adding the close feature or not.
    if (!options['permanent']) {
      note.prepend($(window.document.createElement('a'))
          .addClass("ambiance-close")
          .attr("href", "#_")
          .html("&times;"));
    }

    // Deal with adding the title if given.
    if (options['title'] !== "") {
      note.append($(window.document.createElement('div'))
          .addClass("ambiance-title")
          .append(options['title']));
    }


    // Append the message (this can also be HTML or even an object!).
    note.append(options['message']);
    var link = options['link'].trim();
    if (link.length > 0) {
      var target = options['linkBlank'] ? ' target="_blank"' : '';
      var linkName = options['linkName'].trim() || "Link";
      var linkColor = options['linkColor'].trim() != "" ? ' style="color:' + options['linkColor'] + '"' : '';
      note.append($(window.document.createElement('div'))
          .addClass("ambiance-link")
          .append("<a href='" + link + "'" + target + " " + linkColor + ">" + linkName + "</a>"));
    }

    // Add the notification to the notification area.
    note_area.append(note);

    // Deal with non-permanent note.
    if (!options['permanent']) {
      if (options['timeout'] != 0) {
        if (options['fade']) {
          note.delay(options['timeout'] * 1000).fadeOut('slow');
          note.queue(function() { $(this).remove(); });
        } else {
          note.delay(options['timeout'] * 1000)
              .queue(function() { $(this).remove(); });
        }
      }
    }
  };
  $.ambiance = $.fn.ambiance; // Rename for easier calling.
})(jQuery);

jQuery(document).ready(function() {
  // Deal with adding the notification area to the page.
  if (jQuery("#ambiance-notification").length == 0) {
    var note_area = jQuery(window.document.createElement('div'))
                     .attr("id", "ambiance-notification");
    jQuery('body').append(note_area);
  }
});

// Deal with close event on a note.
jQuery(document).on("click", ".ambiance-close", function () {
  jQuery(this).parent().remove();
  return false;
});