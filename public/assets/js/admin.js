$(function() {
  // Highlighting
  $('[data-highlight]').each(function() {
    var $this = $(this),
        what  = $this.data('highlight');
    $this.highlight(what);
  });
});