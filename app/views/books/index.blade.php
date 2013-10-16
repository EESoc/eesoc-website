@extends('layouts.application')

@section('content')
  <div class="page-header">
    <a href="{{{ route('dashboard.books.create') }}}" class="btn btn-primary btn-lg pull-right">
      <span class="glyphicon glyphicon-gbp"></span>
      Sell Book
    </a>
    <h1>Book Sale</h1>
  </div>
  @if ( ! $my_books->isEmpty())
    <div class="page-header">
      <h3>My Listed Books</h3>
    </div>
    @foreach ($my_books as $book)
      <div class="well well-sm">
        <div class="row">
          <div class="col-lg-10">
            <h5>{{{ $book->name }}}</h5>
          </div>
          <div class="col-lg-2 text-right">
            <div class="btn-group">
              <a href="{{{ route('dashboard.books.edit', $book->id) }}}" class="btn btn-sm btn-default">
                <span class="glyphicon glyphicon-edit"></span>
                Edit
              </a>
              <a href="{{{ route('dashboard.books.destroy', $book->id) }}}" class="btn btn-sm btn-danger" data-method="delete" data-confirm="Are you sure?">
                <span class="glyphicon glyphicon-remove"></span>
                Delete
              </a>
            </div>
          </div>
        </div>
      </div>
    @endforeach

    <div class="page-header">
      <h3>Books For Sale</h3>
    </div>
  @endif
  @foreach ($other_books as $book)
    <div class="well well-sm">
      <div class="row">
        <div class="col-lg-2 hidden-xs hidden-sm">{{ $book->thumbnail_image }}</div>
        <div class="col-lg-6 col-sm-9">
          <h4>{{{ $book->name }}}</h4>
          <dl>
            <dt>Condition</dt>
            <dd>{{{ $book->condition }}}</dd>
            @if ($book->isbn)
              <dt>ISBN</dt>
              <dd>{{{ $book->isbn }}}</dd>
            @endif
            @if ($book->target_course)
              <dt>Target Course</dt>
              <dd>{{{ $book->target_course }}}</dd>
            @endif
            @if ($book->target_student_groups)
              <dt>Target Student Groups</dt>
              <dd>{{{ $book->target_student_groups }}}</dd>
            @endif
          </dl>
        </div>
        <div class="col-lg-2 col-sm-3 text-right">
          <p>{{{ $book->user->name }}}</p>
          <p>
            <a data-toggle="modal" href="#seller-{{{ $book->id }}}" class="btn btn-info btn-sm">Contact</a>
          </p>
        </div>
        <div class="col-lg-2 text-right">
          <strong>{{{ $book->price }}}</strong>
        </div>
      </div>
    </div>
  @endforeach

  @foreach ($other_books as $book)
    <div class="modal fade" id="seller-{{{ $book->id }}}" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">{{{ $book->user->name }}}</h4>
          </div>
          <div class="modal-body">
            {{ $book->contact_instruction_paragraphs }}
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  @endforeach
@stop