<div>
    @foreach($comments as $comm )

        <div style="padding: 10px;">
            <a href="{{ route("details", $comm -> product -> id) }}">
                {{ $comm -> product -> name }}
            </a> | <strong>{{$comm -> title }}</strong>
            <div class="flex">

                <div class="comment-content">
                    {!! style($comm -> content) !!}
                </div>
                <div class="likespan">
                    <p class="like">
                        <span >
                            {{ $comm -> like -> count() }}
                        </span>

                        <i class="bi bi-heart"></i>
                    </p>
                </div>
            </div>
        </div>
        <hr class="hrsep">

    @endforeach
</div>
