<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use App\Services\AccountService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $accounts = $this->accountService->getAllByUser(auth('api')->user());

        return response([
            'message' => 'Accounts retrieved successfully',
            'results' => [
                'accounts' => AccountResource::collection($accounts),
            ]
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric|min:0'
        ]);

        $user = auth('api')->user();
        $account = $this->accountService->create($user, $request);

        return response([
            'message' => 'Account created successfully',
            'results' => [
                'account' => new AccountResource($account),
            ]
        ]);
    }

        /**
     * Store a newly created resource in storage.
     */
    public function storeDefault(Request $request): Response
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric|min:0'
        ]);

        $user = auth('api')->user();
        $account = $this->accountService->create($user, $request,"default");

        return response([
            'message' => 'Account created successfully',
            'results' => [
                'account' => new AccountResource($account),
            ]
        ]);
    }


    public function show($uuid)
    {
        //
        $account = $this->accountService->getByAccUuid(auth('api')->user(), $uuid);
        
        return response([
            'message' => 'Account retrieved successfully',
            'results' => [
                'account' => new AccountResource($account),
            ]
        ]);
    }

    public function update(Request $request, $uuid): Response
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric|min:0'
        ]);

        $account = $this->accountService->getByAccUuid(auth('api')->user(), $uuid);
        $updatedAccount = $this->accountService->update($account, $request);

        return response([
            'message' => 'Account updated successfully',
            'results' => [
                'account' => new AccountResource($updatedAccount),
            ]
        ]);
    }


    public function delete($uuid): Response
    {
    
        $account = $this->accountService->getByAccUuid(auth('api')->user(), $uuid);
        $result = $this->accountService->delete($account);
        if (!$result) {
            abort(500, "Account could not be deleted");
        }
        return response([
            'message' => 'Account deleted successfully',
        ]);
    }

    public function setDefault($uuid): Response
    {
        $account = $this->accountService->getByAccUuid(auth('api')->user(), $uuid);
        $defaultAccount = $this->accountService->setDefault(auth('api')->user(), $account);

        return response([
            'message' => 'Default account set successfully',
            'results' => [
                'account' => new AccountResource($defaultAccount),
            ]
        ]);
    }

    public function getDefault(): Response
    {
        $defaultAccount = $this->accountService->getDefault(auth('api')->user());   

        return response([
            'message' => 'Default account retrieved successfully',
            'results' => [
                'account' => new AccountResource($defaultAccount),
            ]
        ]);
    }
}
