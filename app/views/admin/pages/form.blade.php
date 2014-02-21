@section('javascript_for_page')
<script>
  CKEDITOR.config.extraPlugins = 'justify';
  CKEDITOR.inline('content', {
    filebrowserBrowseUrl: '{{ URL::action('Barryvdh\ElfinderBundle\ElfinderController@showCKEditor') }}'
  });
</script>
@stop

<div class="form-group {{ $errors->first('name', 'has-error') }}">
  {{ Form::label('name', 'Name', array('class' => 'control-label')) }}
  {{ Form::text('name', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('name', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('slug', 'has-error') }}">
  {{ Form::label('slug', 'Slug', array('class' => 'control-label')) }}
  <div class="input-group">
    <span class="input-group-addon">{{ URL::to('/') }}/</span>
    {{ Form::text('slug', null, array('class' => 'form-control input-lg', 'data-slugify' => '#name')) }}
  </div>
  {{ $errors->first('slug', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('content', 'has-error') }}">
  {{ Form::label('content', 'Content', array('class' => 'control-label')) }}
  <div class="panel panel-default">
    <div class="panel-body">
      {{ Form::textarea('content', null, array('class' => 'form-control input-lg', 'data-wysiwyg' => true)) }}
    </div>
  </div>
  {{ $errors->first('content', '<span class="help-block">:message</span>') }}
</div>
<button type="submit" class="btn btn-primary btn-lg pull-left">
  <span class="glyphicon glyphicon-pencil"></span>
  Save
</button>