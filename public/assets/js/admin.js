$(function() {
  // Highlighting
  $('[data-highlight]').each(function() {
    var $this = $(this),
        what  = $this.data('highlight');
    $this.highlight(what);
  });

  // Slugify
  $('[data-slugify]').each(function() {
    var $this = $(this),
        what  = $this.data('slugify');
    $this
      .slugify(what, {
        slugFunc: function(str, originalFunc) {
          return originalFunc(str.replace(/ +/g, '-'));
        }
      })
      .removeClass('slugify-locked');
  });

  $('[data-wysiwyg]').each(function() {
    $(this).wysihtml5();
  });
});