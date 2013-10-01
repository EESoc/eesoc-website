@section('javascript_for_page')
<script>
  CKEDITOR.inline('body', {
    filebrowserBrowseUrl: '{{ URL::action('Barryvdh\ElfinderBundle\ElfinderController@showCKEditor') }}'
  });
</script>
@stop

<div class="form-group {{ $errors->first('subject', 'has-error') }}">
  {{ Form::label('subject', 'Subject', array('class' => 'control-label')) }}
  {{ Form::text('subject', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('subject', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('newsletter_id', 'has-error') }}">
  {{ Form::label('newsletter_id', 'Newsletter', array('class' => 'control-label')) }}
  {{ Form::select('newsletter_id', Newsletter::all()->lists('name', 'id'), null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('newsletter_id', '<span class="help-block">:message</span>') }}
</div>
<div class="form-group {{ $errors->first('body', 'has-error') }}">
  {{ Form::label('body', 'Body', array('class' => 'control-label')) }}
  <div class="panel panel-default">
    <div class="panel-body">
      {{ Form::textarea('body', null, array('class' => 'form-control input-lg', 'data-wysiwyg' => true)) }}
    </div>
  </div>
  {{ $errors->first('body', '<span class="help-block">:message</span>') }}
</div>

<div class="btn-group pull-left">
  <button type="submit" class="btn btn-primary btn-lg pull-left">
    <span class="glyphicon glyphicon-pencil"></span>
    Save
  </button>
  <button type="submit" class="btn btn-info btn-lg pull-left" data-confirm="Are you sure?" name="queue_send" value="1">
    <span class="glyphicon glyphicon-pencil"></span>
    Add to Mail Sender Queue
  </button>
</div>