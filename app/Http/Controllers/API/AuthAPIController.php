<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\API\LoginAPIRequest;
use App\Http\Requests\API\RegisterAPIRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;

class AuthAPIController extends AppBaseController
{

    public function __construct(UserRepository $userRepo, RoleRepository $roleRepo)
    {
        $this->userRepository = $userRepo;
        $this->roleRepository = $roleRepo;
    }

    /**
     * @param RegisterAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/register",
     *      summary="Register new user",
     *      tags={"Authentication"},
     *      description="User Register",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Register user to be login",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/User")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/User"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function register(RegisterAPIRequest $request)
    {
        request()->request->add([
            'password' => $request->password
        ]);
        $user = $this->userRepository->register($request);

        if (empty($user)) {
            return $this->sendError('User not created!', 400);
        }
        $user->roles()->syncWithoutDetaching([optional($this->roleRepository->whereFirst('name', 'user'))->id]);

        $token = $this->oauthLogin($request);

        if (empty($token)) {
            return $this->sendError('User not logged in!', 400);
        }

        request()->request->add([
            'auth' => true
        ]);
        return $this->sendResponse([
            'user' => new UserResource($user),
            'token' => $token
        ], 'User created and logged in successfully.');
    }

    protected function oauthLogin(Request $request)
    {
        $client = Client::where('password_client', true)->first();
        request()->request->add([
            "grant_type" => "password",
            "username" => $request->email,
            "password" => $request->password,
            "client_id" => $client->id,
            "client_secret" => $client->secret,
            "scope" => "*",
        ]);
        $tokenRequest = request()->create(
            env('APP_URL') . '/oauth/token',
            'post'
        );
        $instance = Route::dispatch($tokenRequest);
        return json_decode($instance->getContent());
    }

    /**
     * @param LoginAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/login",
     *      summary="Login",
     *      tags={"Authentication"},
     *      description="User Login",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Login",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/User")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/User"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function login(LoginAPIRequest $request)
    {
        if (auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $token = $this->oauthLogin($request);

            if (empty($token)) {
                return $this->sendError('User not logged in!', 400);
            }

            request()->request->add([
                'auth' => true
            ]);
            return $this->sendResponse([
                'user' => new UserResource(auth()->user()),
                'token' => $token
            ], 'User logged in successfully.');
        }
        return $this->sendError('Unauthorized!', 401);
    }

    /**
     * Verify email
     *
     * @param $user_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function verify($userId, Request $request) {
        if (!$request->hasValidSignature()) {
            return false;
        }

        $user = User::findOrFail($userId);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->to('/');
    }

    /**
     * Resend email verification link
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resend() {
        if (auth()->user()->hasVerifiedEmail()) {
            return false;
        }

        auth()->user()->sendEmailVerificationNotification();

        return $this->respondWithMessage("Email verification link sent on your email id");
    }
}
