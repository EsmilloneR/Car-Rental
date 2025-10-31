<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'id_pictures' => ['required', 'array', 'min:1'],
            'id_pictures.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ])->validate();

        $idPaths = [];
        foreach($input['id_pictures'] as $file){
            $idPaths[] = $file->store('id_pictures', 'public');
        }


        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'id_pictures' => $idPaths,
        ]);
    }
}
