@extends('layouts.application')

@section('content')
<div class="jumbotron">
  <div class="container">
    <h1>Confirm claiming this locker?</h1>
    <h2>
      {{ $locker->lockerCluster->lockerFloor->name }}
      <small>{{ $locker->lockerCluster->name }}</small>
    </h2>
    <p class="lockers">
      <table class="table table-bordered lockers-table">
        <?php
          $lockers = $locker->lockerCluster->lockers;
          $maximum_columns = $lockers->totalColumns();
          $maximum_rows    = $lockers->totalRows();
        ?>
        @for ($row = 0; $row <= $maximum_rows; ++$row)
          <tr>
            @for ($column = 0; $column <= $maximum_columns; ++$column)
              @if ($__locker = $lockers->at($row, $column))
                <td
                  @if ($__locker->id === $locker->id)
                    class="{{ $__locker->selected_css_class }}"
                    data-selected
                  @else
                    class="{{ $__locker->muted_css_class }}"
                  @endif
                >
                  <h4>{{{ $__locker->name }}}</h4>
                </td>
              @else
                <td></td>
              @endif
            @endfor
          </tr>
        @endfor
      </table>
    </p>
    <p class="pull-right">
      <a href="{{{ URL::action('LockersController@postClaim', $locker->id) }}}" class="btn btn-primary btn-lg" data-method="post" data-disable-with="Processing&hellip;">Yep</a>
      <a href="{{{ URL::action('LockersController@getIndex') }}}" class="btn btn-link btn-lg">Nope</a>
    </p>
  </div>
</div>
@stop

@section('javascript_for_page')
<script type="text/javascript">
$(function() {
  var $outer = $('.lockers'),
      $inner = $outer.find('.lockers-table'),
      $selected = $('td[data-selected]');

  var outerWidth = $outer.width(),
      selectedWidth = $selected.outerWidth(true),
      selectedIndex = $selected.index();

  var leftScreenOffset = (outerWidth - selectedWidth) / 2,
      leftSiblingOffset = selectedWidth * selectedIndex;

  var scrollValue = leftSiblingOffset - leftScreenOffset;
  $outer.scrollLeft(Math.max(0, scrollValue));
});
</script>
@stop