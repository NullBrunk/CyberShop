@extends("layout.base")

@section("title", "Update comment")


@section("content")

    <body>

        <script>
            $(function() {        
                $('#commentTextBar').markItUp(mySettings);
            }) 
        </script>


        <section style="margin-top: 14vh; padding: 2.5%;">
            <div id="formcomm" class="commentsbox" >

                <form method="post" action="{{ route("comment.edit")  }}" style="width:100%;">

                    @method("patch")
                    @csrf

                    @error("title")
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="title" style="height: 13vh;;">
                        Title of your comment <abbr>*</abbr>
                        <input name="title" type="text" value="{{$data["title"]}}" placeholder="Example: Nice product !" class="titlebar" maxlength="45">
                    </div>
                    <input name="id_product" type="hidden" value="{{$data["id_product"]}}">


                    @error("comment")
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="contentcomment title" style="margin-top: 10px; height: 23vh;">
                        Your comment <abbr>*</abbr>
                        <textarea class="commentbar" name="comment" id="commentTextBar" type="text">{{ htmlspecialchars_decode($data["content"]) }}</textarea>
                    </div>
                    <input name="id" type="hidden" value="{{$data['id']}}">
                    
                    <br>


                    @error("rating")
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror

                    <p class="title" style="margin-bottom: 0; margin-top: 60px; ">Rating <abbr>*</abbr></p>
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
                                
        <script>
            const stars = document.getElementById("{{  "star" . (string)$data['rating'] }}")
            stars.checked = "checked" 
        </script>
        
@endsection