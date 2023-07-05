<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>Contact</title>
        <meta content="" name="description">
        <meta content="" name="keywords">

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="../assets/vendor/glightbox/css/glightbox.css" rel="stylesheet">
        <link href="../assets/vendor/aos/aos.css" rel="stylesheet">

        
        <script src="../assets/js/sweetalert2.js"></script>

        <link href="../assets/css/style.css" rel="stylesheet">
        <link href="../assets/css/contact.css" rel="stylesheet">

    </head>

    <body>
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
        
        @include('../layout/header')


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
                
                        <?php 
                            $names = array_keys($data);  
                        ?>

                        @for($i = sizeof($names) - 1; $i >= 0; $i--)
                            @if(isset($user) && $user === $names[$i])
                                <a class="profile-box hoverblue" href="{{route("contactuser", $names[$i])}}"> {{ $names[$i] }}</a> 
                            @else
                                <a class="profile-box" href="{{route("contactuser", $names[$i])}}"> {{ $names[$i] }}</a> 
                            @endif
                            <hr>
                        @endfor
            
                    </div>

                    <div class="right">
                        @if(isset($noone) && $noone === true)

                        @else 
                            <div class="msgs" id="chat">
                                
                                @if(isset($data[$user]))
                                    

                                    @for($i=0; $i < sizeof($data[$user]); $i++)


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


                                            <button onclick="window.location.href = '{{ route('delete', $data[$user][$i]['id']) }}'"
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
                                <form method="post" action="{{route("contact")}}">
                                     <input placeholder="Send a message to {{ explode("/contact/", url() -> current())[1] }}" type="text" name="content" value="{{ old("content") }}" autofocus>
                                     @csrf
                                    <button name="submit"><i class="bx bx-send"></i></button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div id="preloader"></div>
            <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

            <script src="../assets/vendor/aos/aos.js"></script>
            <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
            <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
            <script src="../assets/vendor/waypoints/noframework.waypoints.js"></script>

            <script src="../assets/js/main.js"></script>

            <script>
                var chat = document.getElementById("chat");
                chat.scrollTop = chat.scrollHeight; // Défilement vers le bas
            </script>
    </body>
</html>