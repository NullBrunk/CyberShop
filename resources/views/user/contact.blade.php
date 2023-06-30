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
z
        
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
                    <div onclick="sendmsg()" class="sendmsg profile-box">
                        <i class="bx bx-mail-send"></i> 
                    </div>
                    
                    <div style="background: white; height: 1px;">_</div>
                
                        <?php 
                            $names = array_keys($data);  
                        ?>

                        @foreach($names as $n)

                            @if(isset($user) && $user === $n)
                                <a class="profile-box red" href="{{route("contactuser", $n)}}"> {{ $n }}</a> 
                            @else
                                <a class="profile-box" href="{{route("contactuser", $n)}}"> {{ $n }}</a> 
                            @endif
                            <hr>
                        @endforeach
            
                    </div>

                    <div class="right">
                        @if(isset($noone) && $noone === true)

                        @else 
                            <div class="msgs" id="chat">
                                
                                @if(isset($data[$user]))
                                    
                                    @foreach($data[$user] as $d)

                                        @if(!$d['me'])

                                            <div class="message">
                                                {{$d[0]}}
                                            </div>

                                        @else 
                                            <div class="message from-me">
                                                {{$d[0]}} 

                                                <i 
                                                    onclick="menu({{$d['id']}})" 
                                                    class="dots bx bx-dots-vertical-rounded"
                                                ></i>
                                                                        
                                            </div>

                                            <button onclick="
                                                window.location.href = '{{ route('delete', $d['id']) }}'
                                                "
                                                id="{{$d["id"]}}"  
                                                class="btn btn-primary menu none">

                                                DELETE <i class="bi bi-trash2-fill"></i>
                                            </button>

                                        @endif
                                    
                                    @endforeach
                                
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
            <script src="../assets/vendor/glightbox/js/glightbox.js"></script>
            <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
            <script src="../assets/vendor/waypoints/noframework.waypoints.js"></script>

            <script src="../assets/js/main.js"></script>

            <script>
                var chat = document.getElementById("chat");
                chat.scrollTop = chat.scrollHeight; // Défilement vers le bas
            </script>
    </body>
</html>