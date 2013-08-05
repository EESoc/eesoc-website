@foreach(array('success', 'warning', 'notice', 'danger') as $type)
  @if (Session::has($type))
    <div class="alert alert-{{ $type }}">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      {{ Session::get($type) }}
    </div>
  @endif
@endforeach