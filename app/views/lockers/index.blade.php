@extends('layouts.application')

@section('content')
  @foreach ($locker_floors as $locker_floor)
    <div class="page-header" id="{{ $locker_floor->anchor_name }}">
      <h1>{{{ $locker_floor->name }}}</h1>
    </div>
    @foreach ($locker_floor->lockerClusters()->sorted()->get() as $locker_cluster)
      <div class="panel panel-default locker-cluster" id="{{ $locker_cluster->anchor_name }}">
        <div class="panel-heading">{{{ $locker_cluster->name }}}</div>
        <div class="lockers">
          <table class="table table-bordered lockers-table">
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
                    <td class="{{ $locker->{$locker->ownedBy(Auth::user()) ? 'owner_css_class' : 'css_class'} }}">
                      <h4>{{{ $locker->name }}}</h4>
                      @if ($locker->ownedBy(Auth::user()))
                        <a href="#" class="btn btn-default btn-sm btn-block disabled">Owned</a>
                      @else
                        {{ $locker->status_action }}
                      @endif
                    </td>
                  @else
                    <td></td>
                  @endif
                @endfor
              </tr>
            @endfor
          </table>
        </div>
      </div>
    @endforeach
  @endforeach
@stop