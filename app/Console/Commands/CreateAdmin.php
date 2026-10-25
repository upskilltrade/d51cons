<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

#[Signature('admin:create {--name=} {--email=}')]
#[Description('Create an admin account, or reset the password of an existing one')]
class CreateAdmin extends Command
{
    public function handle(): int
    {
        $name = $this->option('name') ?: text('Name', required: true);
        $email = $this->option('email') ?: text('Email', required: true);
        $password = password('Password (at least 12 characters)', required: true);

        $validator = Validator::make(
            compact('name', 'email', 'password'),
            [
                'name' => ['required', 'string', 'max:120'],
                'email' => ['required', 'email:rfc', 'max:190'],
                'password' => ['required', Password::min(12)],
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->components->error($error);
            }

            return self::FAILURE;
        }

        $user = User::query()->updateOrCreate(['email' => strtolower($email)], [
            'name' => $name,
            'password' => $password,
        ]);

        $this->components->info(($user->wasRecentlyCreated ? 'Created' : 'Updated').' admin '.$user->email);

        return self::SUCCESS;
    }
}
