@section('javascript_for_page')
<script>
  CKEDITOR.inline('description', {
    filebrowserBrowseUrl: '{{ URL::action('Barryvdh\ElfinderBundle\ElfinderController@showCKEditor') }}'
  });
</script>
@stop

<div class="form-group {{ $errors->first('slug', 'has-error') }}">
  {{ Form::label('slug', 'Short URL*', array('class' => 'control-label')) }}
  {{ Form::text('slug', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('slug', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('full_url', 'has-error') }}">
  {{ Form::label('full_url', 'Full URL*', array('class' => 'control-label')) }}
  {{ Form::text('full_url', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('full_url', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('expiry_date', 'has-error') }}">
  {{ Form::label('expiry_date', 'Expiry Date (format: yyyy-mm-dd, blank => null)', array('class' => 'control-label')) }}
  {{ Form::text('expiry_date', null, array('id', 'datepicker', 'class' => 'form-control input-lg')) }}
  {{ $errors->first('expiry_date', '<span class="help-block">:message</span>') }}
</div>
<button type="submit" class="btn btn-primary btn-lg pull-left">
  <span class="glyphicon glyphicon-pencil"></span>
  Save
</button>