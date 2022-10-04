<?php

namespace App\Services\Logic\Account;

use App\Models\Account;
use App\Models\User;
use App\Models\UserBalance;
use App\Validators\AccountValidator;
use App\Lib\Validators\CommonValidator;
use App\Validators\VerifyValidator;
use App\Services\Logic\LogicService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterService extends LogicService
{

    public function handle($params)
    {

        $account = $params['account'] ?? '';
        $verifyCode = $params['verify_code'] ?? '';
        $password = $params['password'] ?? '';

        $verifyValidator = new VerifyValidator();
        $verifyValidator->checkCode($account, $verifyCode);

        $accountValidator = new AccountValidator();

        $accountValidator->checkLoginName($account);
        $data = [];

        if (CommonValidator::phone($account)) {

            $data['phone'] = $accountValidator->checkPhone($account);

            $accountValidator->checkIfPhoneTaken($account);

        } elseif (CommonValidator::email($account)) {

            $data['email'] = $accountValidator->checkEmail($account);

            $accountValidator->checkIfEmailTaken($account);
        }

        $password = $accountValidator->checkPassword($password);
        $data['password'] = Hash::make($password);
        try {
            DB::beginTransaction();
            $account = Account::query()->create($data);
            $user = new User();

            $user->id = $account->id;
            $user->name = "user_{$account->id}";

            if ($user->save() === false) {
                throw new \RuntimeException('Create User Failed');
            }
            $userBalance = new UserBalance();

            $userBalance->user_id = $account->id;

            if ($userBalance->save() === false) {
                throw new \RuntimeException('Create User Balance Failed');
            }

            DB::commit();

            return $account;

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Register Error ' . json_encode([
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }
    }

}
