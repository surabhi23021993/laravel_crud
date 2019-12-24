<?php

namespace App\Http\Controllers;

use App\CandidateUserAssoc;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class UserController extends BaseController
{

    public function index(Request $request)
    {
        $page = $request->page;
        $limit = $request->limit;
        if (($page == null || $limit == null) || ($page == -1 || $limit == -1)) {
            $user = User::orderBy('name', 'asc')->paginate(50);
        } else {
            $user = User::orderBy('name', 'asc')->paginate($limit);
        }
        if ($user->first()) {
            return $this->dispatchResponse(200, "Record found successfully.", $user);
        } else {
            return $this->dispatchResponse(404, "No Records Found!!", $user);
        }
    }

    public function create()
    {
        $posted_data = Input::all();
        DB::beginTransaction();
        try {
            $objectUser = new User();
            if ($objectUser->validate($posted_data)) {
                $posted_data["password"] = trim($posted_data["password"]);
                $posted_data["password"] = Hash::make($posted_data["password"]);
                
                $model = User::create($posted_data);
                DB::commit();
                if ($model) {
                    return $this->dispatchResponse(200, "User Created Successfully...!!", $model);
                }

            } else {
                DB::rollback();
                return $this->dispatchResponse(400, "Something went wrong.", $objectUser->errors());
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($id)
    {
        $posted_data = Input::all();

        // return $posted_data;
        try {
            DB::beginTransaction();
            $model = User::find((int) $id);

            if ($model->validate($posted_data)) {
                if ($posted_data['password'] == '') {
                    unset($posted_data['password']);
                } else {
                    $posted_data['password'] = Hash::check('plain-text', $posted_data['password']) ? $posted_data['password'] : Hash::make($posted_data['password']);
                }
                if ($model->update($posted_data)) {
                    DB::commit();
                    return $this->dispatchResponse(200, "User Updated Successfully...!!", $model);
                }
            } else {
                DB::rollback();
                return $this->dispatchResponse(400, "Something went wrong.", $model->errors());
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function view($id)
    {
        $model = User::find((int) $id);

        if ($model) {
            return $this->dispatchResponse(200, "Records Found...!!", $model);
        }

    }

    // public function changePassword()
    // {
    //     $posted_data = Input::all();
    //     DB::beginTransaction();
    //     try {
    //         $newPassword = trim($posted_data["new_password"]);
    //         $posted_data["new_password"] = Hash::make($newPassword);

    //         $user_data = User::find((int) $posted_data["user_id"]);
    //         if ($posted_data["old_password"] != null) {
    //             if (Hash::check($posted_data["old_password"], $user_data->password)) {
    //                 $user_data->password = $posted_data["new_password"];
    //                 $user_data->update();
    //                 DB::commit();
    //                 if ($user_data) {
    //                     return $this->dispatchResponse(200, "Password changed successfully...!!", $user_data);
    //                 }

    //             } else {
    //                 DB::rollback();
    //                 return $this->dispatchResponse(400, "Old password is not matched.", $user_data->errors());
    //             }
    //         } else {
    //             $user_data->password = $posted_data["new_password"];
    //             $user_data->update();
    //             DB::commit();
    //             if ($user_data) {
    //                 return $this->dispatchResponse(200, "Password changed successfully...!!", $user_data);
    //             }

    //         }
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         throw $e;
    //     }
    // }

}
