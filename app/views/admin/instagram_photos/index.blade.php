@extends('layouts.admin')

@section('content')
  <div class="page-header">
    <h1>Instagram Photos</h1>
  </div>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>Photo</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($photos as $photo)
        <tr>
          <td><img src="{{{ $photo->image_thumbnail_url }}}" alt="{{{ $photo->instagram_username }}}"></td>
          <td>
            @if ($photo->hidden)
              <a href="{{{ route('admin.instagram-photos.update', ['instagram_photos' => $photo->id, 'action' => 'unhide']) }}}" data-method="put" class="btn btn-warning">Unhide</a>
            @else
              <a href="{{{ route('admin.instagram-photos.update', ['instagram_photos' => $photo->id, 'action' => 'hide']) }}}" data-method="put" class="btn btn-danger">Hide</a>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop