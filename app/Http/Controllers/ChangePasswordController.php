<?php
namespace App\Http\Controllers;

use App\ChangePassword;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class ChangePasswordController extends BaseController
{
    public function index()
    {
        $changePassword = ChangePassword::paginate(200);
        if ($changePassword->first()) {
            return $this->dispatchResponse(200, "", $changePassword);
        } else {
            return $this->dispatchResponse(404, "No Records Found!!", $changePassword);
        }
    }

    /*
     *   Function used to change pasword
     */
    public function create()
    {
        $posted_data = Input::all();
        DB::beginTransaction();
        try {

            $objectChangePassword = new ChangePassword();

            $newPassword = trim($posted_data["new_password"]);
            $posted_data["new_password"] = Hash::make($newPassword);

            $userid = $posted_data["user_id"];
            $user_data = User::find((int) $userid);

            if ($objectChangePassword->validate($posted_data)) {

                //    $newPassword = trim($posted_data["new_password"]);
                // $posted_data["new_password"] = Hash::make($newPassword);

                // $userid = $posted_data["user_id"];

                //    $user_data = User::find((int) $userid);

                if ($posted_data["old_password"] != null) {
                    if (Hash::check($posted_data["old_password"], $user_data->password)) {

                        $user_data->password = $posted_data["new_password"];

                        $user_data->update();

                        $model = ChangePassword::create($posted_data);

                        DB::commit();
                        if ($model) {
                            return $this->dispatchResponse(200, "Password changed successfully...!!", $model);
                        }

                    } else {
                        DB::rollback();
                        return $this->dispatchResponse(400, "Old password is not matched.", $objectChangePassword->errors());
                    }
                } else {

                    $user_data->password = $posted_data["new_password"];

                    $user_data->update();

                    $model = ChangePassword::create($posted_data);

                    DB::commit();
                    if ($model) {
                        return $this->dispatchResponse(200, "Password changed successfully...!!", $model);
                    }

                }
            } else {
                DB::rollback();
                return $this->dispatchResponse(400, "Something went wrong.", $objectChangePassword->errors());
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /*
     *  function used to change update password (not in use)
     */
    public function update($id)
    {
        $posted_data = Input::all();

        try {
            DB::beginTransaction();
            $model = ChangePassword::find((int) $id);

            if ($model->validate($posted_data)) {

                DB::commit();
                if ($model->update($posted_data)) {
                    return $this->dispatchResponse(200, "Password changed successfully...!!", $model);
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
}
