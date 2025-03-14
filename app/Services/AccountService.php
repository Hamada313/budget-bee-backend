<?php

namespace App\Services;

use App\Models\Account;
use App\Models\User;
use Carbon\Carbon;

class AccountService
{
    public function create(User $user, object $request, String $type = "non-default"): Account
    {
        if ($type == 'default') {
            return Account::create([
                'user_id' => $user->uuid,
                'name' => $request->name,
                'balance' => $request->balance,
                'is_default' => true,
            ]);
        }
        return  Account::create([
            'user_id' => $user->uuid,
            'name' => $request->name,
            'balance' => $request->balance,
        ],);
    }

    public function getAllByUser(User $user)
    {
        return Account::where('user_id', $user->uuid)->orderBy('created_at')->get();
    }

    public function getByAccUuid(User $user, string $uuid): Account
    {
        $account = Account::where('user_id', $user->uuid)->where('uuid', $uuid)->first();
        if (!$account) {
            abort(404, "Account not found");
        }
        return $account;
    }

    public function update(Account $account, object $request): Account
    {
        return tap($account)->update([
            'name' => $request->name,
            'balance' => $request->balance,
            'updated_at' => Carbon::now(config('app.timezone'))
        ]);
    }

    public function delete(Account $account): bool
    {
        $account->delete();
        return true;
    }

    public function setDefault(User $user, Account $account): Account
    {
        /* if user_id was the primary key for account you 
        can user Account::find($user->uuid) instead */ 
        $oldDefault = Account::where('user_id', $user->uuid);  
        if ($oldDefault) {
            $oldDefault->update(['is_default' => false]);
        }
        return tap($account)->update(['is_default' => true]);
    }

    public function getDefault(User $user): Account
    {
        $account = Account::where('user_id', $user->uuid)->where('is_default', true)->first();
        if (!$account) {
            abort(404, "Default account not found");
        }
        return $account;
    }
}
