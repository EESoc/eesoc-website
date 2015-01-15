@section('javascript_for_page')
  <script>
    $(function() {
      var $input = $('input[data-search-query]');
      var $results = $('[data-search-results]');
      var $loader = $('[data-loader]');
      var currentRequest;

      var selectBook = function(item) {
        return function() {
          $results.empty();
          $input.val('');

          // Google book id
          $('[name=google_book_id]').val(item.id);
          $('[name=thumbnail]').val(item.volumeInfo.imageLinks.thumbnail);

          // ISBN
          // $('#isbn').val(
          //   ($.grep(item.volumeInfo.industryIdentifiers, function(data, i) {
          //     return data.type === 'ISBN_13';
          //   }) || item.volumeInfo.industryIdentifiers)[0].identifier
          // );
          if (typeof item.volumeInfo.industryIdentifiers !== 'undefined' && item.volumeInfo.industryIdentifiers.length > 0) {
            $('#isbn').val(item.volumeInfo.industryIdentifiers[0].identifier);
          }

          $('#name').val(item.volumeInfo.title);

          return false;
        };
      };

      var performSearch = function(searchQuery) {
        if (currentRequest) {
          currentRequest.abort();
        }
        return function() {
          $loader.removeClass('hide');
          currentRequest = $.get('https://www.googleapis.com/books/v1/volumes?key={{{ Config::get('google.api_key') }}}', {
            q: searchQuery,
            maxResults: 6
          }, function(data) {
            $loader.addClass('hide');
            $results.empty();
            $.each(data.items, function(i, item) {
              console.log(item);
              $results
                .append(
                  $('<div class="col-lg-2" />')
                    .append(
                      $('<a href="#" class="thumbnail" />')
                        .append(
                          (
                            typeof item.volumeInfo.imageLinks !== 'undefined'
                            ? $('<img />')
                                .attr('src', item.volumeInfo.imageLinks.thumbnail)
                                .attr('alt', item.volumeInfo.title)
                            : ''
                          )
                        )
                        .append(
                          $('<div class="caption" />')
                            .append(
                              $('<h4 />')
                                .text(item.volumeInfo.title)
                            )
                        )
                        .click(selectBook(item))
                    )
                );
            });
          });
          return false;
        }
      };

      $input.keyup(function(e) {
        var $this = $(this),
            searchQuery = $this.val();
        clearTimeout($this.data('timer'));
        if (searchQuery.length > 0) {
          $this.data('timer', setTimeout(performSearch(searchQuery), 100));
        }
      });
    });
  </script>
@stop

<div class="panel panel-default">
  <div class="panel-heading">Book finder</div>
  <div class="panel-body">
    <input type="text" class="form-control input-lg" placeholder="Search using book title, ISBN, author, etc" data-search-query>
  </div>
  <div class="panel-body">
    <p class="text-center hide" data-loader>
      <img src="{{ asset('assets/images/loading.gif') }}" alt="Loading">
    </p>
    <div class="row book-search-results" data-search-results></div>
  </div>
</div>

{{ Form::hidden('google_book_id', null) }}
{{ Form::hidden('thumbnail', null) }}

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
  {{ Form::text('condition', null, array('class' => 'form-control input-lg', 'placeholder' => 'New? Used?')) }}
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
  <div class="input-group">
    <span class="input-group-addon">£</span>
    {{ Form::text('price', $book->raw_price, array('class' => 'form-control input-lg')) }}
  </div>
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
<div class="form-group {{ $errors->first('expires_at', 'has-error') }} hide">
  {{ Form::label('expires_at', 'Expires at', array('class' => 'control-label')) }}
  {{ Form::text('expires_at', null, array('class' => 'form-control input-lg')) }}
  {{ $errors->first('expires_at', '<span class="help-block">:message</span>') }}
</div>
<button type="submit" class="btn btn-primary btn-large pull-left">
  <span class="glyphicon glyphicon-pencil"></span>
  Save
</button>
