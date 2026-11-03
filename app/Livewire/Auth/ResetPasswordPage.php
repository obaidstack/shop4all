<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Attributes\Title;
use Str;

#[Title('Reset Password - Shop4all')]
class ResetPasswordPage extends Component
{
    public $token;

    #[Url(as: 'email')]
    public $email;
    public $password;
    public $password_confirmation;
    
    public function mount($token) {
        $this->token = $token;
    }

    public function save(){
        $this->validate([
            'token' => 'required',
            'email' => 'required|email|max:255|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);
        $status = Password::reset(
            [
                'token' => $this->token,
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
            ],
            function(User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));

            },
        );

        return $status === Password::PASSWORD_RESET ? redirect('/login') : 
            session()->flash('error', 'Something went wrong');
    }

    public function render()
    {
        return view('livewire.auth.reset-password-page');
    }
}
