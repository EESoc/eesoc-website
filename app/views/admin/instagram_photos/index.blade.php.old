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
        <tr
          @if ($photo->hidden)
            class="danger"
          @endif
        >
          <td><img src="{{{ $photo->image_thumbnail_url }}}" alt="{{{ $photo->instagram_username }}}"></td>
          <td class="text-right">
            @if ($photo->hidden)
              <a href="{{{ action('Admin\InstagramPhotosController@putVisibility', [$photo->id, 'unhide']) }}}" data-method="put" class="btn btn-success">Unhide</a>
            @else
              <a href="{{{ action('Admin\InstagramPhotosController@putVisibility', [$photo->id, 'hide']) }}}" data-method="put" class="btn btn-danger">Hide</a>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop
