<?php

namespace Lyon\Console;

use Illuminate\Auth\AuthManager;
use Illuminate\Console\Command;

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

        $token = $this->auth->setTTL($ttl * 60)->attempt($credentials);

        if(!$token) {
            $this->error("JWT token could not be created. Invalid credentials.");
            return;
        }

        $this->info("JWT token created successfully: {$token}");
    }
}