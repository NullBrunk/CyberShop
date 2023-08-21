<div>
    @foreach($comments as $comm)
        {!! style($comm -> content) !!}
    @endforeach
</div>
