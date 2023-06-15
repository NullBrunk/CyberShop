<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
    
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="assets/css/profile.css" rel="stylesheet">



  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>
    


<body style="background-color: #37517e;">
    @include('layout/header')
    <div style="padding-top: 350px;"></div>
  <div class="main-content" >
   
    <script>


      function undisable(){

        for(elem of ["email", "input-sub", "password"])
          document.getElementById(elem).disabled = !document.getElementById(elem).disabled

      
         
      }
    </script>
    <!-- Page content -->
    <div class="container-fluid mt--7" style="padding-right: 2.5vw !important; padding-left: 2.5vw !important;">
      <div class="row">
        
        <div class="col-xl-8 order-xl-1" class="">
          <div class="card bg-secondary shadow" style="border: 0px; width: 95vw;">
            <div class="card-header bg-white border-0">
              <div class="row align-items-center">
                <div class="col-8">
                  <h3 class="mb-0">My account</h3>
                </div>
                <div class="col-4 text-right">
                </div>
              </div>
            </div>
            <div class="card-body" style="background-color: white;">


              <p style="color: #6c757d !important; font-size: 1rem; font-weight: 500; line-height 1,2; font-family: 'Jost', sans-serif; white-space: nowrap;" class="heading-small text-muted mb-4">User information  
                <button onclick="undisable()" class="btn-profile">
                 Edit 
                  <i class="bi bi-pencil-square"></i>

                </button>
            </p>    



          @if($errors -> has("email") or $errors -> has("password") or isset($_SESSION['nul']))
              <div class="alert alert-danger">
                An error has occured
              </div>

              <?php
                unset($_SESSION['nul']);
              ?>

          @elseif(isset($_SESSION['done']))
            <div class="alert alert-success">
                Your information have been updated
            </div>

            <?php
              unset($_SESSION['done']);
            ?>
          @endif
          <form action="{{ route("profile") }}" method="post">

            @csrf

                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group focused">
                        <label class="form-control-label" for="input-address">E-mail</label>
                        <input id="email" name="email" class="form-control form-control-alternative" placeholder="Your e-mail address" value="{{$_SESSION['mail']}}" type="text" disabled>
                        

                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group focused">
                        <label class="form-control-label" for="input-address">Password</label>

                        <input id="password" name="password" class="form-control form-control-alternative" placeholder="Your password" value="{{$_SESSION['pass']}}" type="password" disabled>
                      
             
                      </div>
                    </div>
                  </div>
                </div>
                <input type="submit" id="input-sub" name="submit" value="Update" class="btn btn-primary" style="background-color: #32325d;" disabled>
             
              </form>




                <hr class="my-4">
                <!-- Address -->
                <h6 class="heading-small text-muted mb-4">Contact information</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    
                  </div>
                  <div class="row">
                    <div class="col-lg-4">
                      <div class="form-group focused">
                        <label class="form-control-label" for="input-city">City</label>
                        <input type="text" id="input-city" class="form-control form-control-alternative" placeholder="City" value="New York">
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group focused">
                        <label class="form-control-label" for="input-country">Country</label>
                        <input type="text" id="input-country" class="form-control form-control-alternative" placeholder="Country" value="United States">
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label class="form-control-label" for="input-country">Postal code</label>
                        <input type="number" id="input-postal-code" class="form-control form-control-alternative" placeholder="Postal code">
                      </div>
                    </div>
                  </div>
                </div>
                <hr class="my-4">
                <!-- Description -->
                <h6 class="heading-small text-muted mb-4">About me</h6>
                <div class="pl-lg-4">
                  <div class="form-group focused">
                    <label>About Me</label>
                    <textarea rows="4" class="form-control form-control-alternative" placeholder="A few words about you ...">A beautiful Dashboard for Bootstrap 4. It is Free and Open Source.</textarea>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div style="margin-top: 20px"></div>
</body>

</html>
