<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Contact</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.css" rel="stylesheet">
  <script src="../assets/js/sweetalert2.js"></script>

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">
  <link href="../assets/css/contact.css" rel="stylesheet">


</head>



<body>
      @include('../layout/header')

      <div class="main">
      <div class="header">

        <div onclick="sendmsg()" class="sendmsg">
          <i class="bx bx-mail-send"></i>
        </div>

      </div>

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

          <?php 


            $names = array_keys($data);  
          ?>
          @foreach($names as $n)
            <a class="profile-box" href="{{route("contactuser", $n)}}"> {{ $n }}</a> 
            <hr>
          @endforeach
          

        </div>

        <div class="right">
          
          @if(isset($noone) && $noone === true)
          
          @else 
            <div class="msgs">
              
              @if(isset($data[$user]))
                @foreach($data[$user] as $d)

                  @if(!$d['me'])
                    <div class="message">{{$d[0]}}</div>
                  
                    @else 
                      <div class="message from-me">
                        {{$d[0]}}
                      </div>
                  @endif
                  
                @endforeach
              @endif
              
            </div>

            <div class="textbar">
              <form method="post" action="{{route("contact")}}">
                <input placeholder="Send a message to {{ explode("/contact/", url() -> current())[1] }}" type="text" name="content" value="{{ old("content") }}">
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

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/aos/aos.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/glightbox/js/glightbox.js"></script>
  <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="../assets/vendor/waypoints/noframework.waypoints.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>

</body>

</html>