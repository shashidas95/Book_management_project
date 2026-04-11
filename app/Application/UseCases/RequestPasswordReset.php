<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Events\EventDispatcherInterface;
use App\Infrastructure\Events\PasswordResetRequested;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RequestPasswordReset
{
    // public function __construct(private UserRepositoryInterface $repository) {}
    // Inject the INTERFACE, not the concrete class
    public function __construct(private EventDispatcherInterface $dispatcher, private UserRepositoryInterface $repository) {}
    public function execute(string $email)
    {
        // 1. Check if user exists
        $user = $this->repository->findByEmail($email);
        if (!$user) {
            // Security Tip: Don't reveal if the email exists or not
            return;
        }
        // 2. Generate a secure token
        $token = Str::random(64);

        DB::table('password_reset_tokens')->UpdateOrInsert(
            [
                'email' => $email,
            ],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );
        // 4. Send the Email (You'd create a Mailable class for this)
        Mail::to($email)->send(new ResetPasswordMail($token, $user->email));
        // This call is now "Infrastructure Agnostic"
        $this->dispatcher->dispatch(new PasswordResetRequested($email, $token));

        return $token; // Returned for your API testing, but usually hidden
    }
}
