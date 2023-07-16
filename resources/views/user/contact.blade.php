@extends("static.base")

@section("title", "Contact")


@section("content")   
    <body>
        <link href="../assets/css/contact.css" rel="stylesheet">

        <script>
            function menu(id){
                const menu = document.getElementById(id)
                if(menu.classList[3]){
                    menu.classList.remove("none")
                }
                else {
                    menu.classList.add("none") 
                }

                var chatDiv = document.getElementById("chat");
                chatDiv.scrollTop += 40;
            }
        </script>

        @include('layout.header')


        <div class="main" >

            <script>

                function sendmsg(){
                    Swal.fire({
                        title: 'Enter the mail of the user',
                        input: 'text',
                        inputAttributes: {
                        autocapitalize: 'off'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Contact',
                    
                    }).then((result) => {
                        if (result.isConfirmed) {
                        return window.location.href = "/contact/" + result.value;
                        }
                    })
                }
                
            </script>
            
            
            @if(isset($_SESSION["contact_no_one"]) or isset($_SESSION["contact_yourself"]))
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error !',
                        text: "You can't contact this user !",
                        })
                </script> 
                
                <?php
                    unset($_SESSION["contact_no_one"]);
                    unset($_SESSION["contact_yourself"]);
                ?>
                
            @endif

            <div class="content">

                <div class="left">
                    <div onclick="sendmsg()" class="sendmsg profile-box" style="cursor: pointer;">
                        <i class="bx bx-mail-send"></i> 
                    </div>
                    
                    <div style="background: white; height: 1px;">_</div>

                        @for($i = sizeof($contact) - 1; $i >= 0; $i--)

                            @php($value = 0)

                            @php($keys = array_keys($data[$contact[$i][1]]))
                            @php($last_key = end($keys))

                            @while($last_key >= 0 && $data[$contact[$i][1]][$last_key]["readed"] !== 1 )
                                @php($value++)
                                @php($last_key--)
                            @endwhile


                            @if(isset($user) && $user === $contact[$i][1])
                                <a class="profile-box hoverblue" href="{{route("contact.user", $contact[$i][1])}}"> {{ $contact[$i][1] }}</a> 
                            @else
                                <a class="profile-box" href="{{route("contact.user", $contact[$i][1])}}"> {{ $contact[$i][1] }} @if(end($data[$contact[$i][1]])["readed"] === 0 and end($data[$contact[$i][1]])["me"] === false) <span class="notifs-unreaded-message"> {{ $value }} </span> @endif</a> 
                            @endif
                            <hr>
                        @endfor
            
                    </div>

                    <div class="right">
                        @if(isset($noone) && $noone === true)

                        @else 
                            <div class="msgs" id="chat">
                                
                                @if(isset($data[$user]))
                                    

                                    

                                    @for($i=0; $i < sizeof($data[$user]) - 1; $i++)


                                        @if(!isset($old))
                                            @if($data[$user][$i]['me'] === false)
                                                <div class="profilemsg time" style="background-color: #dbd7d7"><i class="bi bi-person-fill-down"></i></i> <i class="bi bi-dot"></i> <span>{{ $data[$user][$i]["time"] }}</span></div>
                                            @else
                                                <div class="profilemsg time" style="background-color: #d1eaf9"><i class="bi bi-person-fill-up"></i> <i class="bi bi-dot"></i> <span>{{ $data[$user][$i]["time"] }}</span></div>
                                            @endif
                                        @elseif((!($old === $data[$user][$i]['me']) && ($data[$user][$i]['me'] === false)))
                                            
                                            <p class="close" style="background-color: #d1eaf9"></p>
                                            
                                            <div class="profilemsg time" style="background-color: #dbd7d7"><i class="bi bi-person-fill-down"></i></i> <i class="bi bi-dot"></i> <span>{{ $data[$user][$i]["time"] }}</span></div>
                                        
                                        @elseif((!($old === $data[$user][$i]['me']) && ($data[$user][$i]['me'] === true)))
                                            
                                            <p class="close"></p>
                                            
                                            <div class="profilemsg time" style="background-color: #d1eaf9"><i class="bi bi-person-fill-up"></i> <i class="bi bi-dot"></i> <span>{{ $data[$user][$i]["time"] }}</span></div>
                                        
                                        @endif


                                        @if(!$data[$user][$i]['me'])


                                            <div class="message">
                                                {{ $data[$user][$i][0] }}
                                            </div>

                                        @else 
                                            <div class="message from-me ">

                                                {{ $data[$user][$i][0] }} 
                                                <i onclick="menu({{$data[$user][$i]['id']}})" class="dots bx bx-dots-vertical-rounded"></i>

                                            </div>


                                            <button onclick="window.location.href = '{{ route('contact.delete', $data[$user][$i]['id']) }}'"
                                                id="{{$data[$user][$i]["id"]}}"  
                                                class="btn btn-primary menu none ">
                                                DELETE <i class="bi bi-trash2-fill"></i>
                                            </button>

                                        @endif
                                    

                                        @php($old = $data[$user][$i]['me'])


                                    @endfor


                                    @if($old)
                                        <p class="close" style="background-color: #d1eaf9"></p>
                                    @else
                                        <p class="close"></p>
                                    @endif

                                    <?php
                                        unset($old)
                                    ?>
                                
                                @endif
                            </div>


                            <div class="textbar">
                                <form method="post" action="{{route("contact.show")}}">
                                        <input placeholder="Send a message to {{ explode("/contact/", url() -> current())[1] }}" type="text" name="content" value="{{ old("content") }}" autofocus>
                                        @csrf
                                    <button name="submit"><i class="bx bx-send"></i></button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <script>
                var chat = document.getElementById("chat");
                chat.scrollTop = chat.scrollHeight; // Défilement vers le bas
            </script>
@endsection
