@extends('layouts.application')

@section('content')

    <div class="jumbotron">
        <h1>Welcome {{{ $me }}}!</h1>

		<h3>Group {{ $group }} - Room {{ $room }} for Lunch</h3>
		
		@if ($isParent)
            <h2>Your Partner</h2>
        @else
			<h2>Your Mums and Dads</h2>
		@endif
		
        <div class="row">
            @foreach ($parents as $parent)

                <div class="col-md-4 text-center">
            @if ($parent->has_image)
                        <img src="data:{{ $parent->image_content_type }};base64,{{ base64_encode($parent->image_blob) }}" width="81" height="108" alt="{{{ $parent->name }}}" class="img-thumbnail" />
            @endif
                    <h3>{{{ $parent->name }}}</h3>
                </div>
            @endforeach
        </div>
    </div>
		
	@if ($isParent)
		<h2>Your FamilEEE</h2>
	@else
		<h2>Your Siblings</h2>
	@endif    

    <?php $counter = 0; ?>

    @foreach ($children as $child)

        @if (($counter % 3) === 0)
            <div class="row">
        @endif

        <div class="col-md-4 text-center">
            @if ($child->has_image)
                <img src="data:{{ $child->image_content_type }};base64,{{ base64_encode($child->image_blob) }}" width="81" height="108" alt="{{{ $child->name }}}" class="img-thumbnail" />
            @endif
            <h3>{{{ $child->name }}}</h3>
        </div>

        @if (($counter % 3) === 2)
        </div>
        @endif

        <?php $counter++; ?>
    @endforeach

    @if (($counter % 3) !== 0)
    </div>
    @endif
@stop