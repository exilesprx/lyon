<?php

namespace Lyon\Console;

use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Console\Command;
use Tymon\JWTAuth\JWTGuard;

/**
 * Class GenerateTokenCommand
 * @package Lyon\Console
 */
class GenerateTokenCommand extends Command
{
    /**
     * @var AuthManager
     */
    private $auth;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = "lyon:token
        {--e|email : Signifies the username is an email}
        {--u|username= : The user's username}
        {--p|password= : The user's password}
        {--t|ttl= : Set a custom TTL (in minutes)}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generate a JWT token for the given user.";

    /**
     * GenerateTokenCommand constructor.
     * @param AuthManager $auth
     */
    public function __construct(AuthManager $auth)
    {
        parent::__construct();

        $this->auth = $auth;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $credentials = $this->getCredentials();

        try {
            $guard = $this->getGuard();

            if($ttl = $this->option('ttl')) {
                $guard->setTTL($ttl);
            }

            if(!$token = $guard->attempt($credentials)) {
                $this->error("JWT token could not be created. Invalid credentials.");
                return;
            }

            $this->info("JWT token created successfully: {$token}");
        }
        catch(Exception $exception) {
            $this->error("An error occurred: {$exception->getMessage()}");
            return;
        }
    }

    /**
     * Get the credentials array based on the options passed to the command.
     *
     * @return array
     */
    protected function getCredentials()
    {
        $username = $this->option('username');
        $password = $this->option('password');

        if($email = $this->option('email')) {
            return [
                'email'    => $username,
                'password' => $password
            ];
        }

        return [
            'username' => $username,
            'password' => $password
        ];
    }

    /**
     * Get the JWT guard from the AuthManager.
     *
     * @return JWTGuard
     * @throws Exception
     */
    protected function getGuard()
    {
        $guard = $this->auth->guard();

        if(!$guard instanceof JWTGuard)
        {
            $instance = get_class($guard);

            throw new Exception("Package is not compatible with the current guard: {$instance}");
        }

        return $guard;
    }
}