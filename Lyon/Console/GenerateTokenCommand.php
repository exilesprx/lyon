<?php

namespace Lyon\Console;

use Illuminate\Console\Command;
use Tymon\JWTAuth\JWTGuard;

/**
 * Class GenerateTokenCommand
 * @package Lyon\Console
 */
class GenerateTokenCommand extends Command
{
    /**
     * @var JWTGuard
     */
    private $guard;

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
     * @param JWTGuard $guard
     */
    public function __construct(JWTGuard $guard)
    {
        parent::__construct();

        $this->guard = $guard;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $credentials = [];

        $username = $this->option('username');

        if($email = $this->option('email')) {
            $credentials['email'] = $username;
        }
        else {
            $credentials['username'] = $username;
        }

        $credentials['password'] = $this->option('password');

        $ttl = $this->option('ttl');

        $token = $this->guard->setTTL($ttl * 60)->attempt($credentials);

        if(!$token) {
            $this->error("JWT token could not be created. Invalid credentials.");
            return;
        }

        $this->info("JWT token created successfully: {$token}");
    }
}