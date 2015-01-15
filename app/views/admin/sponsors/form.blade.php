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
<div class="form-group {{ $errors->first('description', 'has-error') }}">
  {{ Form::label('description', 'Description', array('class' => 'control-label')) }}
  <div class="panel panel-default">
    <div class="panel-body">
      {{ Form::textarea('description', null, array('class' => 'form-control input-lg', 'data-wysiwyg' => true)) }}
    </div>
  </div>
  {{ $errors->first('content', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('position', 'has-error') }}">
  {{ Form::label('position', 'Position', array('class' => 'control-label')) }}
  {{ Form::text('position', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('position', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('logo', 'has-error') }}">
  {{ Form::label('logo', 'Logo', array('class' => 'control-label')) }}
  {{ Form::file('logo') }}
  {{ $errors->first('logo', '<span class="help-block">:message</span>') }}
</div>
<button type="submit" class="btn btn-primary btn-lg pull-left">
  <span class="glyphicon glyphicon-pencil"></span>
  Save
</button>
