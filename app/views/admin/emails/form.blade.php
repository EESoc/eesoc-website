@section('javascript_for_page')
<script src="{{{ asset('assets/js/epiceditor/js/epiceditor.min.js') }}}"></script>
<script>
  $(function() {
    (function() {
      var $previewFrame = $('iframe#htmlpreview');

      var editor = new EpicEditor({
          basePath: '',
          theme: {
              base: '{{{ asset('assets/js/epiceditor/themes/base/epiceditor.css') }}}',
              preview: '{{{ asset('assets/js/epiceditor/themes/preview/preview-dark.css') }}}',
              editor: '{{{ asset('assets/js/epiceditor/themes/editor/epic-dark.css') }}}'
          },
          button: {
              preview: false,
              fullscreen: false,
          },
          textarea: 'body'
      }).load();
   
      var refreshPreview = function() {
        var htmlcontent = this.exportFile(null, 'html');
        htmlcontent = htmlcontent.replace(/<p>/g,"<p style=\"margin: 1em 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #606060;font-family: HelveticaNeue-Light, 'Helvetica Neue Light', 'Helvetica Neue', 'Trade Gothic W01 Light', Helvetica, Arial, 'Lucida Grande', sans-serif;font-size: 14px;line-height: 150%;text-align: left;\">")
        .replace(/<h1>/g,"<h1 style=\"margin: 0;padding: 0;display: block;font-family: HelveticaNeue-Light, 'Helvetica Neue Light', 'Helvetica Neue', 'Trade Gothic W01 Light', Helvetica, Arial, 'Lucida Grande', sans-serif;font-size: 35px;font-style: normal;font-weight: 300;line-height: 125%;letter-spacing: -.5px;text-align: left;color: #606060 !important;\">")
        .replace(/<h2>/g,"<h2 style=\"margin: 0;padding: 0;display: block;font-family: HelveticaNeue-Light, 'Helvetica Neue Light', 'Helvetica Neue', 'Trade Gothic W01 Light', Helvetica, Arial, 'Lucida Grande', sans-serif;font-size: 28px;font-style: normal;font-weight: 300;line-height: 125%;letter-spacing: -.5px;text-align: left;color: #606060 !important;\">")
        .replace(/<h3>/g,"<h3 style=\"margin: 0;padding: 0;display: block;font-family: HelveticaNeue-Light, 'Helvetica Neue Light', 'Helvetica Neue', 'Trade Gothic W01 Light', Helvetica, Arial, 'Lucida Grande', sans-serif;font-size: 22px;font-style: normal;font-weight: 300;line-height: 125%;letter-spacing: -.5px;text-align: left;color: #606060 !important;\">")

        $previewFrame.contents().find("table#templateBody tbody.mcnTextBlockOuter td.mcnTextBlockInner td.mcnTextContent").html(htmlcontent)
      };

      editor.on('update', refreshPreview);

      // @TODO fix this
      $previewFrame.ready(function() {
        refreshPreview.call(editor);
      });

    })();

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
  <div class="col-lg-8">
    <div class="panel panel-default">
      <div class="panel-heading">Delivery</div>
      <div class="panel-body">

        @if ($email->can_save)
          <button type="submit" class="btn btn-primary" name="action" value="save">
            <span class="glyphicon glyphicon-floppy-disk"></span>
            Save
          </button>
        @endif

        @if ($email->can_send)
          <button type="submit" class="btn btn-info" name="action" value="send" data-confirm="Are you sure?">
            <span class="glyphicon glyphicon-send"></span>
            Send Now!
          </button>
          <button type="submit" class="btn btn-default" name="action" value="send_test">
            Send Test Email
          </button>
        @endif
      </div>
    </div>
  </div>

  <div class="col-lg-4">
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

<div class="row">
  <div class="col-lg-12">
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
            <span class="help-block">A short summary text that follows the subject line when an email is viewed in the inbox.</span>
            {{ $errors->first('preheader', '<span class="help-block">:message</span>') }}
          </div>
        </div>
        <hr>
        <div class="form-group {{ $errors->first('from_name', 'has-error') }}">
          {{ Form::label('from_name', 'From Name', array('class' => 'control-label col-lg-2')) }}
          <div class="col-lg-10">
            {{ Form::text('from_name', 'EESoc', array('class' => 'form-control')) }}
            {{ $errors->first('from_name', '<span class="help-block">:message</span>') }}
          </div>
        </div>
        <div class="form-group {{ $errors->first('from_email', 'has-error') }}">
          {{ Form::label('from_email', 'From Email', array('class' => 'control-label col-lg-2')) }}
          <div class="col-lg-10">
            {{ Form::text('from_email', 'please-reply@eesoc.com', array('class' => 'form-control')) }}
            {{ $errors->first('from_email', '<span class="help-block">:message</span>') }}
          </div>
        </div>
        <div class="form-group {{ $errors->first('reply_to_email', 'has-error') }}">
          {{ Form::label('reply_to_email', 'Reply-To Email', array('class' => 'control-label col-lg-2')) }}
          <div class="col-lg-10">
            {{ Form::text('reply_to_email', 'please-reply@eesoc.com', array('class' => 'form-control')) }}
            {{ $errors->first('reply_to_email', '<span class="help-block">:message</span>') }}
          </div>
        </div>

      </div>
    </div>

    @if ($email->exists)
    {{-- Only show form for saved emails --}}
      <div class="form-group {{ $errors->first('body', 'has-error') }}">
        {{ Form::label('body', 'Body', array('class' => 'control-label')) }}
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row email-preview">
              <div id="epiceditor" class="col-lg-6"></div>
              <div class="col-lg-6 html-preview">
                <iframe id="htmlpreview" src="{{{ action('Admin\EmailsController@getPreviewTemplate', $email->id) }}}"></iframe>
              </div>
            </div>

            {{ Form::textarea('body', null, ['class' => 'hide']) }}
          </div>
        </div>
        {{ $errors->first('body', '<span class="help-block">:message</span>') }}
      </div>
    @endif
  </div>
</div>
