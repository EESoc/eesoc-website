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
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($my_books as $book)
          <tr>
            <td>{{{ $book->name }}}</td>
            <td>
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
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <div class="page-header">
      <h3>Books For Sale</h3>
    </div>
  @endif
  <table class="table table-hover">
    <thead>
      <tr>
        <th class="col-lg-1"></th>
        <th>Book</th>
        <th>Seller</th>
        <th class="text-right">Price</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($other_books as $book)
        <tr>
          <td>{{ $book->thumbnail_image }}</td>
          <td>
            {{{ $book->name }}}
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
          </td>
          <td>
            <p>{{{ $book->user->name }}}</p>
            <p>
              <a data-toggle="modal" href="#seller-{{{ $book->id }}}" class="btn btn-primary btn-sm">Contact</a>
            </p>
          </td>
          <td class="text-right">{{{ $book->price }}}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

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