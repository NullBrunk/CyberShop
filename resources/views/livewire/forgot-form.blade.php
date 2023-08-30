<div>
    <form method="post" class="login-form">
        
        @csrf

        <div class="form-group">

            
            <input wire:model="email" type="mail" id="email" name="email" class="form-control rounded-left @if($errors -> has('email') or $errors -> has('invalid') or $errors -> has('verify') ) is-invalid @elseif($email !== '') is-valid @endif" placeholder="E-mail" value="{{old("email")}}" autofocus>    
            <div class="invalid-feedback">
                @error("email")
                    {{ $message }}
                @enderror
            </div>
        </div>


        <div class="form-group">
            <button type="submit" class="form-control btn btn-primary rounded submit px-3">Get reset link</button>
        </div>

    </form>
</div>
