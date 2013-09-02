@extends('layouts.application')

@section('content')
  <div class="page-header">
    <h1>Locker Sale</h1>
  </div>
  @if ( ! $lockers_owned->isEmpty())
    <div class="panel panel-primary">
      <div class="panel-heading">Lockers I currently own</div>
      <div class="panel-body">
        <div class="row">
          @foreach ($lockers_owned as $locker)
            <div class="col-lg-2">
              <h2>
                {{{ $locker->name }}}
              </h2>
              <h4>
                {{{ $locker->lockerCluster->lockerFloor->name }}}
                <small>{{{ $locker->lockerCluster->name }}}</small>
              </h4>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif
  @foreach ($locker_floors as $locker_floor)
    <div class="page-header" id="{{ $locker_floor->anchor_name }}">
      <h2>{{{ $locker_floor->name }}}</h2>
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