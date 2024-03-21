<?php

namespace Moox\Press\Services;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;

class Login extends BaseAuth
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getLoginFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getLoginFormComponent(): Component
    {
        return
        TextInput::make('login')
            ->label('Login')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('user_pass')
            ->label(__('filament-panels::pages/auth/login.form.password.label'))
            ->hint(filament()->hasPasswordReset() ? new HtmlString(Blade::render('<x-filament::link :href="filament()->getRequestPasswordResetUrl()"> {{ __(\'filament-panels::pages/auth/login.actions.request_password_reset.label\') }}</x-filament::link>')) : null)
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->autocomplete('current-password')
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/login.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/login.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();
        $credentials = $this->getCredentialsFromFormData($data);

        $userModel = config('auth.providers.wpusers.model');
        $user = $userModel::where('user_email', $credentials['user_email'])
            ->orWhere('user_login', $credentials['user_email'])
            ->first();

        $wpAuthService = new \Moox\Press\Services\WordPressAuthService();

        // Check if user exists and password is correct
        if (! $user || ! $wpAuthService->checkPassword($credentials['password'], $user->user_pass)) {
            $this->throwFailureValidationException();
        }

        //dd($user);

        // Manually log in the user
        Auth::login($user, $data['remember'] ?? false);

        /*
        // Additional checks for Filament user access, if necessary
        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();
            $this->throwFailureValidationException();
        }
        */

        session()->regenerate();

        return app(LoginResponse::class);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        $login_type = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'user_email' : 'user_login';

        return [
            $login_type => $data['login'],
            'password' => $data['user_pass'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.login' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
}
