<?php

namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;

class UserAuthTransformer extends TransformerAbstract {

    public function transform(\App\User $user) {
              
        
        
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'authToken' => $user->remember_token,
            
        ];
    }
}
