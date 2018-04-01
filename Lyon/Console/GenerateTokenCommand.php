<?php

namespace Lyon\Console;

use Illuminate\Console\Command;
use Tymon\JWTAuth\JWTAuth;

/**
 * Class GenerateTokenCommand
 * @package Lyon\Console
 */
class GenerateTokenCommand extends Command
{
    /**
     * @var JWTAuth
     */
    private $auth;

    /**
     * @var string
     */
    protected $signature = "lyon:jwt
        {--u|username : The user's username}
        {--e|email : The user's email}
        {--p|password : The user's password}
        {--t|ttl : Set a custom TTL (in minutes)}";

    /**
     * @var string
     */
    protected $description = "Generate a JWT token for the given user.";

    /**
     * GenerateTokenCommand constructor.
     * @param JWTAuth $auth
     */
    public function __construct(JWTAuth $auth)
    {
        parent::__construct();

        $this->auth = $auth;
    }

    /**
     *
     */
    public function handle()
    {
        $credentials = [];

        if($email = $this->option('email')) {
            $credentials['email'] = $email;
        }
        elseif($username = $this->option('username')) {
            $credentials['username'] = $username;
        }
        else {
            $this->error('Email or username not entered. Try again!');
            return;
        }

        $credentials['password'] = $this->option('password');

        $ttl = $this->option('ttl');

        $token = $this->auth->setTTL($ttl * 60)->attempt($credentials);

        if(!$token) {
            $this->error("JWT token could not be created. Invalid credentials.");
            return;
        }

        $this->info("JWT token created successfully: {$token}");
    }
}