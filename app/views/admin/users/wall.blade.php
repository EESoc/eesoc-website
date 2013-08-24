@extends('layouts.admin')

@section('content')

  <?php $counter = 0; ?>

  @foreach ($users as $user)
    @if (($counter % 6) === 0)
      <div class="row">
    @endif
      
      <div class="col-lg-2">
        <a href="{{{ $user->image_url }}}">
          <img src="data:{{ $user->image_content_type }};base64,{{ base64_encode($user->image_blob) }}" width="162" height="216" alt="{{{ $user->name }}}" class="img-thumbnail">
        </a>
      </div>

    @if (($counter % 6) === 5)
      </div><!-- .row -->
      <hr style="visibility: hidden">
    @endif
    <?php $counter++; ?>
  @endforeach

  @if (($counter % 6) !== 0)
    </div><!-- .row -->
  @endif

@stop