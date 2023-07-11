<x-mail::message>
    # Hello!

    {{ __('This email is being sent to you as a notification that an account for :restaurant has been created for you at :app.', [
        'restaurant' => $restaurant,
        'app'        => config('app.name'),
    ]) }}

    {{ __('Please note that new accounts do not have a pre-set password. Therefore, it is necessary for you to set your password manually.') }}

    <x-mail::button :url="$setUrl">
        Set Password
    </x-mail::button>

    {{ __('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]) }}

    {{ __('In the event that the link has expired, you have the option to request a new one.') }}

    <x-mail::button :url="$requestNewUrl">
        Request New Link
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
