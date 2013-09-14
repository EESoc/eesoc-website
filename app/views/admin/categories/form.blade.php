<div class="form-group {{ $errors->first('name', 'has-error') }}">
  {{ Form::label('name', 'Name', array('class' => 'control-label')) }}
  {{ Form::text('name', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('name', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('slug', 'has-error') }}">
  {{ Form::label('slug', 'Slug', array('class' => 'control-label')) }}
  {{ Form::text('slug', null, array('class' => 'form-control input-lg', 'data-slugify' => '#name')) }}
  {{ $errors->first('slug', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('description', 'has-error') }}">
  {{ Form::label('description', 'Description', array('class' => 'control-label')) }}
  {{ Form::textarea('description', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('description', '<span class="help-block">:message</span>') }}
</div>
<button type="submit" class="btn btn-primary btn-large pull-left">
  <span class="glyphicon glyphicon-pencil"></span>
  Save
</button>