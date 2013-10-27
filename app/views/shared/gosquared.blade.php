<script type="text/javascript">
  var GoSquared = {};
  GoSquared.acct = "GSN-266572-G";
  (function(w){
    function gs(){
      w._gstc_lt = +new Date;
      var d = document, g = d.createElement("script");
      g.type = "text/javascript";
      g.src = "//d1l6p2sc9645hc.cloudfront.net/tracker.js";
      var s = d.getElementsByTagName("script")[0];
      s.parentNode.insertBefore(g, s);
    }
    w.addEventListener ?
      w.addEventListener("load", gs, false) :
      w.attachEvent("onload", gs);
  })(window);
</script>

@if (Auth::check())
<script type="text/javascript">
  GoSquared.VisitorName = '{{{ Auth::user()->name }}}';
  GoSquared.Visitor = {
    userID: {{ Auth::user()->id }},
    username: '{{{ Auth::user()->username }}}',
    email: '{{{ Auth::user()->email }}}',
    information: '{{{ Auth::user()->extras }}}',
    groupName: '{{{ Auth::user()->student_group ? Auth::user()->student_group->name : '' }}}'
  };
</script>
@endif