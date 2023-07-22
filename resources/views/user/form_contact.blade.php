<form action="{{ route("contact.edit", $message["id"]) }}" method="post" style="background-color: #d1eaf9; margin-left: 1%; margin-right: 1%; padding-left: 10px;">
    @method("patch")
    @csrf
    <input id="a" type="text" name="content" value="{{ htmlspecialchars_decode($message["content"]) }}" style="width: 94%; height: 15%; background-color: #d1eaf9; border: 1px solid #8f8f9d;">
    <input type="hidden" name="id" value="{{$message["id"]}}">
    <button style="width: 5%; background-color: #293e61; color: white; border-radius: 5px; height: 15%; border: 1px solid #293e61;">
        <i class="bi bi-pencil-square"></i>
    </button>
    
    <script>
        document.getElementById("a").style.outline = 'none'
    </script>

</form>