@extends("static.base")

@section("title", "Contact")


@section("content")   

    <body style="background-color: #324769 !important;">

        <link href="/assets/css/contact.css" rel="stylesheet">
        
        <script type="text/javascript">
            
            $(function() {
                $('#textarea').markItUp(mySettings);
            })        
        </script>

        <script src="https://unpkg.com/htmx.org@1.9.3" integrity="sha384-lVb3Rd/Ca0AxaoZg5sACe8FJKF0tnUgR2Kd7ehUOG5GCcROv5uBIZsOqovBAcWua" crossorigin="anonymous"></script>
        <script>
            function menu(id_delete, id_edit){
                document.getElementById(id_delete).classList.toggle("none")
                document.getElementById(id_edit).classList.toggle("none")

                var chatDiv = document.getElementById("chat");
                chatDiv.scrollTop += 40;
            }
            
            function confirm_delete(url){
            Swal.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#293e61',
                    cancelButtonColor: '#af2024',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {

                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                })
            }
        </script>



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
            
            @error("contact_yourself")
                <script>salert("{{$message}}")</script>
            @enderror

            @error("contact_no_one")
                <script>salert("{{$message}}")</script>
            @enderror

            <div class="content">

                <div class="left">
                    <div onclick="sendmsg()" class="sendmsg profile-box" style="cursor: pointer; height: 22% !important;">
                        <i class="bx bx-mail-send"></i> 
                    </div>

                        @for($i = sizeof($contact) - 1; $i >= 0; $i--)
                            @php($value = 0)

                            @php($keys = array_keys($data[$contact[$i][1]]))
                            @php($last_key = end($keys))

                            @while($last_key >= 0 && $data[$contact[$i][1]][$last_key]["readed"] !== 1 )
                                @php($value++)
                                @php($last_key--)
                            @endwhile


                            @if(isset($user) && $user === $contact[$i][1])
                                <a class="profile-box changecolor" href="{{route("contact.user", $contact[$i][1])}}"> {{ $contact[$i][1] }}</a> 
                            @else
                                <a class="profile-box" href="{{route("contact.user", $contact[$i][1])}}"> {{ $contact[$i][1] }} @if(end($data[$contact[$i][1]])["readed"] === 0 and end($data[$contact[$i][1]])["me"] === false) <span class="notifs-unreaded-message"> {{ $value }} </span> @endif</a> 
                            @endif
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
                                                <div class="profilemsg time" style="background-color: #dbd7d7; margin-right: 42%;"><i class="bi bi-person-fill-down"></i></i> <i class="bi bi-dot"></i> <span>{{ $data[$user][$i]["time"] }}</span></div>
                                            @else
                                                <div class="profilemsg time" style="background-color: #ddeffd; margin-left: 42%;"><i class="bi bi-person-fill-up"></i> <i class="bi bi-dot"></i> <span>{{ $data[$user][$i]["time"] }}</span></div>
                                            @endif
                                        @elseif((!($old === $data[$user][$i]['me']) && ($data[$user][$i]['me'] === false)))
                                            
                                            <p class="close" style="background-color: #ddeffd; margin-left: 42% !important;"></p>                                        
                                            <div class="profilemsg time" style="background-color: #dbd7d7; margin-right: 42%;"><i class="bi bi-person-fill-down"></i></i> <i class="bi bi-dot"></i> <span>{{ $data[$user][$i]["time"] }}</span></div>
                                        
                                        @elseif((!($old === $data[$user][$i]['me']) && ($data[$user][$i]['me'] === true)))
                                            
                                            <p class="close" style="margin-right: 42% !important;"></p>
                                            <div class="profilemsg time" style="background-color: #ddeffd; margin-left: 42%;"><i class="bi bi-person-fill-up"></i> <i class="bi bi-dot"></i> <span>{{ $data[$user][$i]["time"] }}</span></div>
                                        
                                        @endif


                                        @if(!$data[$user][$i]['me'])
                                            <div class="message" >
                                                @if($data[$user][$i]["type"] === "text")
                                                    
                                                        <p>{!! $data[$user][$i][0] !!}</p>
                                                    
                                                @else
                                                    <img class="contactimg  "  src="../storage/{{ $data[$user][$i][0] }}">
                                                @endif
                                            </div>

                                        @else 
                                            <div hx-target="this" style="background-color: #ddeffd; margin-left: 42%; width: 57%;">
                                                <div class="message from-me"  hx-swap="outerHTML">

                                                    @if($data[$user][$i]["type"] === "text")
                                                        <p>{!! $data[$user][$i][0] !!}</p>
                                                    @else
                                                        <img class="contactimg" src="../storage/{{ $data[$user][$i][0] }}">
                                                    @endif
                                                    <i onclick="menu({{$data[$user][$i]['id']}}, '{{ 'edit' . $data[$user][$i]['id']}}')" class="dots bx bx-dots-vertical-rounded"></i>

                                                <br>
                                            </div>

                                            
                                            {{-- If the message is an image you cannot edit it --}}
                                            @if($data[$user][$i]["type"] === "text")
                                                <button hx-get="{{ route("contact.edit_form", $data[$user][$i]["id"]) }}"
                                                class="btn btn-primary menu none update" id="{{ 'edit' . $data[$user][$i]['id']}}" style="margin-top: 4px; margin-left: auto !important;">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            @endif

                                            <button onclick='confirm_delete( "{{ route("contact.delete", $data[$user][$i]["id"]) }}" )' id="{{$data[$user][$i]["id"]}}"  class="btn btn-primary menu none" style="margin-top: 4px;">
                                                <i class="bi bi-trash2-fill"></i>
                                            </button>
                                        </div>

                                        @endif
                                    

                                        @php($old = $data[$user][$i]['me'])


                                    @endfor


                                    @if($old)
                                        <p class="close" style="background-color: #ddeffd; margin-left: 42% !important;"></p>
                                    @else
                                        <p class="close" style="margin-right: 42% !important;"></p>
                                    @endif

                                    <?php
                                        unset($old)
                                    ?>
                                
                                @endif
                            </div>

                            
                            <div class="textbar">
                                <form method="post" id="formchat" action="{{route("contact.store")}}" enctype="multipart/form-data">

<input placeholder="Send a message to {{explode("/contact/", url() -> current())[1]}}" id="textarea" name="content" class="textarea" autofocus>
                                    @csrf
                                    <input type="file" id="file-input" name="img" style="width: 0;">
                                    <label for="file-input">
                                        <i class="bx bx-image"></i>
                                      </label>
                                    <button name="submita"><i class="bx bx-send"></i></button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <script>
                var fileInput = document.getElementById('file-input');
                var form = document.getElementById('formchat');

                fileInput.addEventListener('change', function() {
                    form.submit();
                });

                document.addEventListener('DOMContentLoaded', function() {
                    var chat = document.getElementById("chat");
                    chat.scrollTop = chat.scrollHeight; // Défilement vers le bas                
                });

                
            </script>


@endsection

