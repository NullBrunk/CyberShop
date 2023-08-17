<div>
    <form method="post" class="login-form" action="/signup" >
                            
        @csrf
                                           
        <div class="form-group">
            <input wire:model="mail" name="mail" value="{{ old("mail") }}" type="mail" class="form-control rounded-left @error('mail') is-invalid @elseif($mail !== '') is-valid @endif" placeholder="E-mail" autofocus>
            <div class="invalid-feedback">
                @error("mail")
                    {{ $message }}
                @enderror
            </div>
        </div>
    
        <div class="form-group ">
            <input wire:model="pass" name="pass" value="{{ old("pass") }}" type="password" class="form-control rounded-left @error('pass') is-invalid @elseif($pass !== '') is-valid @endif" placeholder="Password">
            <div class="invalid-feedback">
                @error("pass")
                    {{ $message }}
                @enderror
            </div>
        </div>
    
        
        <div class="form-group ">
            <input wire:model="repass"  value="{{ old("repass") }}" name="repass" type="password" class="form-control rounded-left @error('repass') is-invalid @elseif($repass !== '') is-valid @endif" placeholder="Re Password">
            <div class="invalid-feedback">
                @error("repass")
                    {{ $message }}
                @enderror
            </div>
        </div>
    
    
        <div class="form-group">
            <button type="submit" class="form-control btn btn-primary rounded submit px-3">Sign-Up</button>
        </div>
    
        <div class="form-group d-md-flex">
    
            <div class="w-50">                                   
            </div>
            
            <div class="w-50 text-md-right">
                <a style="color:#47b2e4 !important;" href="/login">Login</a>
            </div>
    
        </div>
    </form>
</div>