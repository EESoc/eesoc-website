@section('javascript_for_page')
<script>
  CKEDITOR.inline('description', {
    filebrowserBrowseUrl: '{{ URL::action('Barryvdh\ElfinderBundle\ElfinderController@showCKEditor') }}'
  });
</script>
@stop

<div class="form-group {{ $errors->first('name', 'has-error') }}">
  {{ Form::label('name', 'Name', array('class' => 'control-label')) }}
  {{ Form::text('name', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('name', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('category_id', 'has-error') }}">
  {{ Form::label('category_id', 'Category', array('class' => 'control-label')) }}
  {{ Form::select('category_id', $categories->lists('name', 'id'), null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('category_id', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('date', 'has-error') }}">
  {{ Form::label('date', 'Date', array('class' => 'control-label')) }}
  {{ Form::text('date', null, array('class' => 'form-control input-lg', 'placeholder' => 'yyyy-mm-dd')) }}
  {{ $errors->first('date', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('starts_at', 'has-error') }}">
  {{ Form::label('starts_at', 'Starts At', array('class' => 'control-label')) }}
  {{ Form::text('starts_at', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('starts_at', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('ends_at', 'has-error') }}">
  {{ Form::label('ends_at', 'Ends At', array('class' => 'control-label')) }}
  {{ Form::text('ends_at', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('ends_at', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('location', 'has-error') }}">
  {{ Form::label('location', 'Location', array('class' => 'control-label')) }}
  {{ Form::text('location', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('location', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('description', 'has-error') }}">
  {{ Form::label('description', 'Description', array('class' => 'control-label')) }}
  <div class="panel panel-default">
    <div class="panel-body">
      {{ Form::textarea('description', null, array('class' => 'form-control input-lg', 'data-wysiwyg' => true)) }}
    </div>
  </div>
  {{ $errors->first('description', '<span class="help-block">:message</span>') }}
</div>
<button type="submit" class="btn btn-primary btn-lg pull-left">
  <span class="glyphicon glyphicon-pencil"></span>
  Save
</button>
