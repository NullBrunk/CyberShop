<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title id="title">Update comment </title>
        <meta content="" name="description">
        <meta content="" name="keywords">


        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

        <link href="../../assets/css/style.css" rel="stylesheet">
    </head>


    <body>

        @include('layout/header', ["dotdotslash" => "../"] )





        <section style="margin-top: 12vh; padding: 2.5%;">

        <h2 style="margin-bottom: 2%">Update comment </h2>
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
            <div id="formcomm" class="commentsbox" >

                

                <form method="post" action="{{ route("comment.update")  }}" style="width:100%;">
                    @csrf
                    <div class="title" style="height: 13vh;;">
                        Title of your comment <abbr>*</abbr>
                        <input name="title" type="text" value="{{$data["title"]}}" placeholder="Example: Nice product !" class="titlebar" maxlength="45">
                    </div>
                    <input name="id_product" type="hidden" value="{{$data["id_product"]}}">
                    
                    <div class="contentcomment title" style="margin-top: 10px; height: 23vh;">
                        Your comment <abbr>*</abbr>
                        <textarea placeholder="To help you write a useful comment for our CyberShop:

- Explain to us why you chose this note?
- What did you like best about this product?
- Who would you recommend it to?"
                        class="commentbar" name="comment" type="text">{{ htmlspecialchars_decode($data["content"]) }}</textarea>
                    </div>

                    <input name="id" type="hidden" value="{{$data['id']}}">
                    
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
                    <input class="commbutton" type="submit" value="Update comment">
                    <input name="abort" value="Abort" class="commbutton red" type="submit">

                    <br>

                </form>
                <p style="margin-bottom: 12vh;">
            </div>
        </section>
                                

        <div id="preloader"></div>
        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

        <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../../assets/vendor/waypoints/noframework.waypoints.js"></script>
        <script>
            const stars = document.getElementById("{{  "star" . (string)$data['rating'] }}")
            stars.checked = "checked" 
        </script>

    </body>
</html>
