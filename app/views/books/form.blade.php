@section('javascript_for_page')
<script type="text/javascript">
  var isbn = '9784906224524';
  $('#search_query').val(isbn);

  var performSearch = function(searchQuery) {
    return function() {
      $.get('https://www.googleapis.com/books/v1/volumes', {
        q: searchQuery
      }, function(data) {
        console.log(data);
      });
      return false;
    }
  };

  $('input[data-search-query]').keyup(function(e) {
    var $this = $(this),
        searchQuery = $this.val();
    clearTimeout($this.data('timer'));
    if (searchQuery.length > 0) {
      $this.data('timer', setTimeout(performSearch(searchQuery), 100));
    }
  });
</script>
@stop

<div class="panel panel-default">
  <div class="panel-heading">Book finder</div>
  <div class="panel-body">
    <input type="text" class="form-control input-lg" placeholder="Search for your book" data-search-query>
  </div>
  <div class="panel-body" data-search-results></div>
</div>

<hr>

<div class="form-group {{ $errors->first('isbn', 'has-error') }}">
  {{ Form::label('isbn', 'ISBN', array('class' => 'control-label')) }}
  {{ Form::text('isbn', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('isbn', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('name', 'has-error') }}">
  {{ Form::label('name', 'Name', array('class' => 'control-label')) }}
  {{ Form::text('name', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('name', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('condition', 'has-error') }}">
  {{ Form::label('condition', 'Item Condition', array('class' => 'control-label')) }}
  {{ Form::text('condition', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('condition', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('target_student_groups', 'has-error') }}">
  {{ Form::label('target_student_groups', 'Target Groups', array('class' => 'control-label')) }}
  {{ Form::text('target_student_groups', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('target_student_groups', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('target_course', 'has-error') }}">
  {{ Form::label('target_course', 'Target Course', array('class' => 'control-label')) }}
  {{ Form::text('target_course', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('target_course', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('price', 'has-error') }}">
  {{ Form::label('price', 'Price', array('class' => 'control-label')) }}
  {{ Form::text('price', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('price', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('quantity', 'has-error') }}">
  {{ Form::label('quantity', 'Quantity', array('class' => 'control-label')) }}
  {{ Form::text('quantity', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('quantity', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('contact_instructions', 'has-error') }}">
  {{ Form::label('contact_instructions', 'Contact Instructions', array('class' => 'control-label')) }}
  {{ Form::textarea('contact_instructions', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('contact_instructions', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('expires_at', 'has-error') }}">
  {{ Form::label('expires_at', 'Expires at', array('class' => 'control-label')) }}
  {{ Form::text('expires_at', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('expires_at', '<span class="help-block">:message</span>') }}
</div>
<button type="submit" class="btn btn-primary btn-large pull-left">
  <span class="glyphicon glyphicon-pencil"></span>
  Save
</button>