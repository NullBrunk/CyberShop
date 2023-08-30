<div>
    <form method="post" class="login-form" action="{{ route("auth.reset", $code) }}">
        
        @csrf

        <div class="form-group">  
            <input wire:model="mail" type="mail" name="mail" class="form-control rounded-left @if($errors -> has('mail')) is-invalid @elseif($mail !== '') is-valid @endif" placeholder="E-mail" value="{{old("mail")}}" autofocus>    
            <div class="invalid-feedback">
                @error("mail")
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">  
            <input wire:model="pass" type="password" name="pass" class="form-control rounded-left @if($errors -> has('pass')) is-invalid @elseif($pass !== '') is-valid @endif" placeholder="Password" value="{{old("pass")}}" autofocus>    
            <div class="invalid-feedback">
                @error("pass")
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">  
            <input wire:model="repass" type="password" name="repass" class="form-control rounded-left @if($errors -> has('repass')) is-invalid @elseif($repass !== '') is-valid @endif" placeholder="Re password" value="{{old("repass")}}" autofocus>    
            <div class="invalid-feedback">
                @error("repass")
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="form-control btn btn-primary rounded submit px-3">Change password</button>
        </div>

    </form>
</div>
