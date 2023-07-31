<form action="{{ route("contact.edit", $message["id"]) }}" method="post" style="background-color: #1890ff; margin-left: 1%; margin-right: 1%; padding-left: 10px; width: 35vw; height: 4vh; display: flex;">
    @method("patch")
    @csrf

    <input name="content" id="content_contact" type="text"  value="{{ htmlspecialchars_decode($message["content"]) }}" style="width: 90%; height: 100%; background-color: #434756; color: #eee; border: none; border-top-left-radius: 6px; border-bottom-left-radius: 6px; padding: 6px;">
    <input name="id" type="hidden" value="{{$message["id"]}}">
    
    <button style="width: 10%; height: 100%; background-color: #293e61; color: white; border-top-right-radius: 6px; border-bottom-right-radius: 6px;; border: 1px solid #293e61; display:flex; justify-content: center; text-align:center;">
        <i style="margin:auto;" class="bi bi-pencil-square"></i>
    </button>
    
    <script>
        document.getElementById("content_contact").style.outline = 'none'
    </script>
</form>