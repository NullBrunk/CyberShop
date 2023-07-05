<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>{{$data["name"]}}</title>
        <meta content="" name="description">
        <meta content="" name="keywords">

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
        <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="../assets/vendor/glightbox/css/glightbox.css" rel="stylesheet">
        <script src="../assets/js/sweetalert2.js"></script>
        <script src="https://unpkg.com/htmx.org@1.9.2" integrity="sha384-L6OqL9pRWyyFU3+/bjdSri+iIphTN/bvYyM37tICVyOJkWZLpP2vGn6VUEXgzg6h" crossorigin="anonymous"></script>
        <link href="../assets/css/style.css" rel="stylesheet">



    </head>

    <body>
        <script>
            function menu(id){

                const menu = document.getElementById(id);
                menu.classList.toggle("none")
               
            }

            function showcomm(){


                const form = document.getElementById("formcomm");
                const chevron = document.getElementById("chevron");
                const span = document.getElementById("commcontent");

                form.classList.toggle("none");
                chevron.classList.toggle("bx-chevron-right");
                chevron.classList.toggle("bx-chevron-down");

                if(span.innerText==="Click here to close this menu"){
                    span.innerText = "Click here to post a comment "
                }
                else {
                    span.innerText = "Click here to close this menu"
                }
                
            }

        </script>

        @include('../layout/header')

    
        <main id="main" >

            <!-- ======= Breadcrumbs ======= -->
            <section id="breadcrumbs" class="breadcrumbs" style="padding-top: 86px; padding-bottom: 0px !important;">
                <div class="container">
                    <ol></ol>
                </div>
            </section><!-- End Breadcrumbs -->

            <!-- ======= Portfolio Details Section ======= -->
            <section id="portfolio-details" class="portfolio-details" style="padding-bottom: 0px;">
                <div class="container">
                    <div class="row gy-4 whenigrowibecomeablock">
                        <div class="col-lg-8 takefull" style="width: 50%; display: flex;" >
                            <div style="margin: auto;">

                                @if(isset($_SESSION['done']) && ($_SESSION['done'] === "updated")  )
                                    <script>
                                    Swal.fire(
                                            'Updated !',
                                            'Product updated successfully.',
                                            'success'
                                        ) 
                                    </script>

                                    <?php
                                        unset($_SESSION['done'])
                                    ?>
                                @endif

                            <img data-aos="fade-right" style="width: 85% !important;" src="../storage/product_img/{{ $data["image"] }}" alt="">

                            </div>
                        </div>

                        <div data-aos="fade-left" class="col-lg-4 marginlr"  style="color: white; background-color: #324769 !important; border-radius: 12px; width: 50%; height: 75vh; display: flex; flex-direction: column; overflow: scroll;">
                            <div class="portfolio-info container" style="padding-bottom: 10px;" >
                                <h2>{{$data["name"]}}</h2>
                                <hr>
                            </div>

                            <div class="portfolio-info" style="position: relative;">
                                <p class="descr">

                                    {{ $data['descr'] }}
                                </p>
                            </div>
                        
                            @if(!isset($_SESSION["mail"]) or (isset($_SESSION["mail"]) && $data['mail'] !== $_SESSION["mail"]))
                                <form   class="navbar formshow" method="post" action="{{route("cart.add")}}">  
                                    @csrf   

                                    <button class="addtocart" type="submit">BUY NOW<i  style="font-weight: bold !important;" class="bi bi-cart-plus"></i></button>
                                    <input type="hidden"  name="id" value="{{$data['pid']}}">
                        
                            @else
                                <form class="navbar formshow" method="get" action="{{route("product.updateform", $data['pid'])}}" >  
                                    @csrf   
                                    <button  class="addtocart" type="submit">EDIT PRODUCT<i style="font-weight: bold !important;" class="bi bi-cart-check"></i></button>

                            @endif
                            </form>

                    
                        </div>

                        <br>
                        <hr>

                        <div id="info" data-aos="fade-right">
                        <h2>Product information</h2>

                            <table >
                                <tr>
                                    <th>Name</th>
                                    <td>{{$data["name"]}}</td>
                                </tr>

                                <tr>
                                    <th>Seller</th>
                                    <td>
                                        @if(!isset($_SESSION["mail"]) or (isset($_SESSION["mail"]) && $data['mail'] !== $_SESSION["mail"])) 
                                            <a href="{{route('contactuser', $data['mail'])}}">{{ $data['mail'] }}</a> 
                                        @else 
                                            {{ $data['mail'] }} 
                                        
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>Category</th>
                                    <td>{{ ucfirst(explode('-', $data["class"])[1]) }}</td>
                                </tr>

                                <tr>
                                    <th>Price</th>
                                    <td>{{ $data['price'] }}$</td>
                                </tr>

                                @if($rating)
                                    <tr>
                                    <th>Reviews</th>
                                        <td>

                                            {{ $rating['real'] }} 

                                            @for($i=0; $i<$rating['round']; $i++)
                                                <i class="bi bi-star-fill" style="color: #de7921;"></i>
                                            @endfor

                                            @if($rating["real"] >= $rating["round"] + 0.5)
                                                <i style="color: #de7921;" class="bi bi-star-half"></i>
                                            @elseif($rating["real"] != 5.0)
                                                <i class="bi bi-star" style="color: #de7921;"></i>
                                            @endif

                                            @for($i = $rating['round'] + 1; $i < 5; $i++)
                                                <i class="bi bi-star" style="color: #de7921;"></i>
                                            @endfor
                                            
                                            <a href="#comments"> {{$rating["rate"]}} ratings</a>

                                        </td>
                                    </tr>
                                @endif
                                
                            </table>
                            <p style="margin-top: 10vh;">
                            <hr>

                        </div>
                    </div>
                </div>

            </section>

           

            <section id="breadcrumbs" style="padding-top: 1%;" class="breadcrumbs">
                <div class="container" data-aos="fade-top-right">

                    <ol></ol>
                @if($errors -> has('title'))
                    <div class="alert alert-danger">
                        Title is required !
                    </div>
                @endif
                @if($errors->has('comment') or $errors->has('id'))
                    <div class="alert alert-danger">
                        A comment is required !
                    </div>
                @endif
        
                
                @if($errors -> has('rating'))
                    <div class="alert alert-danger">
                    You need to give a rating !
                    </div>
                @endif


                    @if(isset($_SESSION['done']) )
                        <script>
                            Swal.fire(
                                'Commented !',
                                'Your comment has been posted.',
                                'success'
                            ) 
                        </script>

                        <?php
                            unset($_SESSION['done'])
                        ?> 
                    @endif

                    @if(isset($_SESSION['updated']) )
                        <script>
                            Swal.fire(
                                'Updated !',
                                'Your comment has been updated.',
                                'success'
                            ) 
                        </script>

                        <?php
                            unset($_SESSION['updated'])
                        ?> 
                    @endif
                    <h2>Comments</h2>

                    @if(!isset($_SESSION["logged"]))
                        <div class="alert alert-info">
                            Login to post a comment.
                        </div>
                    @else
                    <p class="commentlink" onclick="showcomm()"><span class="amazonpolice" id="commcontent">Click here to post a comment</span> <i id="chevron" class="bx bx-chevron-right"></i></p>

                        <div id="formcomm" class="commentsbox none" >
                            
                            <form method="post" action="{{ route("comment.add") }}" style="width:100%;">
                                @csrf
                                <div class="title" style="height: 13vh;;">
                                    Title of your comment <abbr>*</abbr>
                                    <input name="title" type="text" value="{{old("title")}}" placeholder="Example: Nice product !" class="titlebar" maxlength="45">
                                </div>
                                
                                <div class="contentcomment title" style="margin-top: 10px; height: 23vh;">
                                    Your comment <abbr>*</abbr>
                                    <textarea placeholder="To help you write a useful comment for our CyberShop:

- Explain to us why you chose this note?
- What did you like best about this product?
- Who would you recommend it to?"
                                    class="commentbar" name="comment" type="text">{{old("comment")}}</textarea>
                                </div>

                                <input name="id" type="hidden" value="{{$data['pid']}}">
                                
                                <br>
                                <p class="title" style="margin-bottom: 0; margin-top: 10px; ">Rating <abbr>*</abbr></p>
                                <div class="rating">
                                    <input type="radio" id="star5" name="rating" value="5">
                                    <label for="star5"></label>
                                    <input type="radio" id="star4" name="rating" value="4">
                                    <label for="star4"></label>
                                    <input type="radio" id="star3" name="rating" value="3">
                                    <label for="star3"></label>
                                    <input type="radio" id="star2" name="rating" value="2">
                                    <label for="star2"></label>
                                    <input type="radio" id="star1" name="rating" value="1">
                                    <label for="star1"></label>
                                </div> 
                                <br>
                                <input class="commbutton" type="submit" value="Post comment">
                                <br>

                            </form>
                            <p style="margin-bottom: 12vh;">
                        </div>


                        
                        @endif
                        <p style="margin-bottom: 7vh;">

                    <div id="comments">

@if($comments)

    @foreach(json_decode($comments, true) as $comm)

        <div id="{{ "div" . $comm["id"] }}" class="comments">          
            <div class="profile">
                
                <p class="profile">
                    <i style="font-size:32px; color:#007185;" class="bi bi-person-circle"> </i>
                    <p class="name">
                        @if(isset($_SESSION['mail']) and ($_SESSION["mail"] === $data["mail"]))
                            {{ $comm["mail"] }}
                        @else
                            <a style="color: #007185" href="{{ route("contactuser", $comm["mail"]) }}">{{$comm["mail"]}}</a>
                        @endif

                    </p> 

                    @if(isset($_SESSION["mail"]) && $comm["mail"] === $_SESSION["mail"])

                        
                    <p class="trash"> 

                        
                        
                        <i id="" onclick='menu("{{$comm["id"]}}")' style="margin-top: 16px;" class="dots bx bx-dots-vertical-rounded"></i>     
                    </p>
                    
                                            
                            
                    @endif 
                    </div>
                </div>
                <div id="{{$comm['id']}}" class="none">
                
                    <script>
                        function deletecomm(){

                            Swal.fire({
                                title: 'Are you sure?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#293e61',
                                cancelButtonColor: '#af2024',
                                confirmButtonText: 'Yes, delete it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '{{ route('comment.delete', [$data['pid'], $comm['id']] ) }}'
                                }
                            })
                        
    
                        }
                    </script>

                    <a 
                     href="{{route("comment.update_form", $comm["id"])}}" 
                     
                     id="{{$comm['id'] . 'updatebutton'}}" class="btn btn-primary menu update" style="width: 39px; margin-left: auto !important;">
                         
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <button id="{{$comm['id'] . 'deletebutton'}}" onclick="deletecomm()" class="btn btn-primary menu" style="margin-top: 4px; width: 39px; margin-left: auto;">
                         
                        <i class="bi bi-trash2-fill"></i>
                    </button>

                </div>
                <span class="titlecomm">{{ $comm["title"] }}</span>

                <div class=stars>

                    @for($i=0; $i<$comm["rating"]; $i++)
                        <i class="bi bi-star-fill" style="color: #de7921;"></i>
                    @endfor

                    @for($i = $comm["rating"]; $i < 5; $i++)
                        <i class="bi bi-star" style="color: #de7921;"></i>
                    @endfor
                    <span class="at">
                        {{ $comm["writed_at"] }}
                    </span>
                </div>

                                

                <div class="comment">

                    {!! nl2br($comm["content"]) !!}
                    <hr>
                </div>
                                
    @endforeach
@endif


                    </div>
                </div>
            </section>
        </main>

        <div id="preloader"></div>
        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

        <script src="../assets/vendor/aos/aos.js"></script>
        <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/vendor/glightbox/js/glightbox.js"></script>
        <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
        <script src="../assets/vendor/waypoints/noframework.waypoints.js"></script>

        <script src="../assets/js/main.js"></script>

    </body>
</html>