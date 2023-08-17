<div>
    
    @if(!empty($notifs))

        <li id="cart" style="list-style-type: none;" class="dropdown ">
            <a class="nav-link" href="#">
                <span class="badge bg-primary badge-number">
                    {{ $notifs_number }}
                </span>

                <i class="bi bi-bell"></i>
                <span>Notifs</span>
            </a>

            <ul class="cartn notif">
                @foreach($notifs as $n)

                    <li>
                        <p class="show_cart">
                            <li class="notification-item">
                                <i style="color:#19526f; font-size: 32px ;margin: 0 20px 0 10px;" class="{{ $n['icon'] }}"></i>
                                <div>
                                    <h4 style="color: rgb(68, 68, 68)">{{ $n["title"] }} </h4>
                                    <p> {{ $n["content"] }} </p>

                                    <a href="{{ $n["more"] }}">
                                        See more
                                        <i class="bx bx-chevrons-right"></i>
                                    </a>
                                </div>
                            </li>
                        <p>
                    </li>
                @endforeach
            </ul>
        </li>
    
    @else

        <a class="nav-link" href="#">
            <i class="bi bi-bell"></i>
            <span>Notifs</span>
        </a>

    @endif

</div>
