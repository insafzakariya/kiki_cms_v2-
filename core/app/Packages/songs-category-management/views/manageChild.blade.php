<ul>
@foreach($childs as $child)
	<li id="{{$child->categoryId}}">
		@if($child->status == 1)
			{{ $child->name }}
		@else
			<del>{{ $child->name }}</del>
		@endif
      	@if(count($child->childs))
            @include('SongsCategory::manageChild',['childs' => $child->childs])
        @endif
	</li>
@endforeach
</ul>
