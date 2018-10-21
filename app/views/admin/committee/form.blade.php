@section('javascript_for_page')
<script>
  CKEDITOR.inline('description', {
    filebrowserBrowseUrl: '{{ URL::action('Barryvdh\ElfinderBundle\ElfinderController@showCKEditor') }}'
  });
</script>
@stop

<div class="form-group {{ $errors->first('name', 'has-error') }}">
  {{ Form::label('name', 'Name*', array('class' => 'control-label')) }}
  {{ Form::text('name', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('name', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('role', 'has-error') }}">
  {{ Form::label('role', 'Role*', array('class' => 'control-label')) }}
  {{ Form::text('role', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('role', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('short_description', 'has-error') }}">
  {{ Form::label('short_description', 'Short Description*', array('class' => 'control-label')) }}
  {{ Form::text('short_description', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('short_description', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('description', 'has-error') }}">
  {{ Form::label('description', 'Description (Optional)', array('class' => 'control-label')) }}
  <div class="panel panel-default">
    <div class="panel-body">
      {{ Form::textarea('description', null, array('class' => 'form-control input-lg', 'data-wysiwyg' => true)) }}
    </div>
  </div>
  {{ $errors->first('content', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('list_position', 'has-error') }}">
  {{ Form::label('list_position', 'List Position', array('class' => 'control-label')) }}
  {{ Form::text('list_position', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('list_position', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('logo', 'has-error') }}">
  {{ Form::label('logo', 'Logo', array('class' => 'control-label')) }}
  {{ Form::file('logo') }}
  {{ $errors->first('logo', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('facebookURL', 'has-error') }}">
  {{ Form::label('facebookURL', 'Facebook URL (Optional)', array('class' => 'control-label')) }}
  {{ Form::text('facebookURL', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('facebookURL', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('githubURL', 'has-error') }}">
  {{ Form::label('githubURL', 'Github URL (Optional)', array('class' => 'control-label')) }}
  {{ Form::text('githubURL', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('githubURL', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('email', 'has-error') }}">
  {{ Form::label('email', 'Email*', array('class' => 'control-label')) }}
  {{ Form::text('email', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('email', '<span class="help-block">:message</span>') }}
</div>
<button type="submit" class="btn btn-primary btn-lg pull-left">
  <span class="glyphicon glyphicon-pencil"></span>
  Save
</button>
