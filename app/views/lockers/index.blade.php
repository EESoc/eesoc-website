@extends('layouts.application')

@section('content')
  @foreach ($locker_floors as $locker_floor)
    <h1>Floor: {{ $locker_floor->name }}</h1>
    @foreach ($locker_floor->locker_clusters as $locker_cluster)
      <h2>Cluster: {{ $locker_cluster->name }}</h2>
      <div style="overflow-x: scroll;">
        <table class="table table-bordered lockers">
          <?php
            $lockers = $locker_cluster->lockers;
            $maximum_columns = $lockers->totalColumns();
            $maximum_rows    = $lockers->totalRows();
          ?>
          @for ($row = 0; $row <= $maximum_rows; ++$row)
            <tr>
              @for ($column = 0; $column <= $maximum_columns; ++$column)
                @if ($locker = $lockers->at($row, $column))
                  <?php $locker = $locker->getPresenter(); ?>
                  <td class="{{ $locker->css_class }}">
                    <div class="text-center">
                    <h3>{{{ $locker->name }}}</h3>
                    <hr class="invisible">
                    @if ($locker->is_vacant)
                      <a href="javascript:alert('TODO: Claim this locker')" class="btn btn-primary btn-sm">Claim</a>
                    @elseif ($locker->is_taken)
                      Taken :(
                    @elseif ($locker->is_reserved)
                      Reserved
                    @endif
                    </div>
                  </td>
                @else
                  <td></td>
                @endif
              @endfor
            </tr>
          @endfor
        </table>
      </div>
    @endforeach
  @endforeach
@stop