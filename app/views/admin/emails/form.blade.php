@section('javascript_for_page')
<script>
  CKEDITOR.inline('body', {
    filebrowserBrowseUrl: '{{ URL::action('Barryvdh\ElfinderBundle\ElfinderController@showCKEditor') }}'
  });

  $(function() {
    $('[data-recipients-count]')
      .change(function() {
        var count = 0;
        $('[data-recipients-count]:checked').each(function() {
          count += $(this).data('recipients-count');
        });
        $('[data-recipients-total]').text(count);
      })
      .trigger('change');
  });
</script>
@stop

<div class="row">
  <div class="col-lg-9">
    <div class="panel panel-default">
      <div class="panel-heading">Details</div>
      <div class="panel-body form-horizontal">
        <div class="form-group {{ $errors->first('subject', 'has-error') }}">
          {{ Form::label('subject', 'Subject', array('class' => 'control-label col-lg-2')) }}
          <div class="col-lg-10">
            {{ Form::text('subject', null, array('class' => 'form-control')) }}
            {{ $errors->first('subject', '<span class="help-block">:message</span>') }}
          </div>
        </div>
        <div class="form-group {{ $errors->first('preheader', 'has-error') }}">
          {{ Form::label('preheader', 'Preheader', array('class' => 'control-label col-lg-2')) }}
          <div class="col-lg-10">
            {{ Form::text('preheader', null, array('class' => 'form-control')) }}
            {{ $errors->first('preheader', '<span class="help-block">:message</span>') }}
          </div>
        </div>
        <div class="form-group {{ $errors->first('from_name', 'has-error') }}">
          {{ Form::label('from_name', 'From Name', array('class' => 'control-label col-lg-2')) }}
          <div class="col-lg-10">
            {{ Form::text('from_name', null, array('class' => 'form-control')) }}
            {{ $errors->first('from_name', '<span class="help-block">:message</span>') }}
          </div>
        </div>
        <div class="form-group {{ $errors->first('from_email', 'has-error') }}">
          {{ Form::label('from_email', 'From Email', array('class' => 'control-label col-lg-2')) }}
          <div class="col-lg-10">
            {{ Form::text('from_email', null, array('class' => 'form-control')) }}
            {{ $errors->first('from_email', '<span class="help-block">:message</span>') }}
          </div>
        </div>
        <div class="form-group {{ $errors->first('reply_to_email', 'has-error') }}">
          {{ Form::label('reply_to_email', 'Reply-To Email', array('class' => 'control-label col-lg-2')) }}
          <div class="col-lg-10">
            {{ Form::text('reply_to_email', null, array('class' => 'form-control')) }}
            {{ $errors->first('reply_to_email', '<span class="help-block">:message</span>') }}
          </div>
        </div>

      </div>
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
  </div>
  <div class="col-lg-3">
    <div class="panel panel-default">
      <div class="panel-heading">Save</div>
      <div class="panel-body">
        @if ($email->can_save)
          <button type="submit" class="btn btn-primary" name="action" value="save">
            <span class="glyphicon glyphicon-floppy-disk"></span>
            Save
          </button>
        @endif
        @if ($email->can_send)
          <button type="submit" class="btn btn-default" name="action" value="send">
            <span class="glyphicon glyphicon-send"></span>
            Send Now!
          </button>
        @endif
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        Recipients
        <span class="label label-success"><span data-recipients-total>0</span> total</span>
      </div>
      <div class="panel-body">
        <div class="form-group">
          @foreach ($newsletters as $newsletter)
            <div class="checkbox">
              <label>
                {{{ $newsletter->name }}}
                {{ Form::checkbox(
                    'newsletter_ids[]',
                    $newsletter->id,
                    in_array($newsletter->id, $email->newsletters->lists('id')),
                    array('data-recipients-count' => $newsletter->recipients_count)
                ) }}
              </label>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>