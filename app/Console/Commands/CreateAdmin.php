<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Services\AuthService;
use App\Contracts\Repositories\AdminRepositoryInterface;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
        private Authservice $authService,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $defaultIndex = 0;

        $type = $this->choice('Select the admin type.', ['New', 'Fake'], $defaultIndex);

        $this->line('********************************************************************************');
        
        match ($type) {
            "New"   => $this->adminNew(),
            "Fake"  => $this->adminFake(),
            default => $this->adminNew(),
        };
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    protected function adminNew()
    {
        $adminNew['name'] = $this->ask('Name');

        $adminNew['email'] = $this->ask('Email');

        $admin = $this->adminRepository->where('email', $adminNew['email']);

        if($admin) {
            $this->error('  Email already exists!');
        } else {
            $password = $this->secret('Password');

            $adminNew['confirm_password'] = $this->secret('Confirm password');

            if($password != $adminNew['confirm_password']) {
                $this->error('  Password not match!'); 
            } else {
                $adminNew['password'] = Hash::make($password);

                $this->adminRepository->create($adminNew);

                $this->info('  Admin created successfuly');
                
                $adminNew['password'] = $password;

                $this->messageCreate($adminNew);
            }
        }
    }

    /**
     * Create new admin fake.
     *
     * @param null
     * @return null
     */
    protected function adminFake()
    {
        $adminFake = [
            'name'     => 'fake',
            'email'    => 'fake@gmail.com',
            'password' => '12345678', 
        ];

        $existFake = $this->adminRepository->where('email', 'fake@gmail.com');

        if($existFake){
            $this->error('  Admin fake is exist!');

            $this->messageCreate($adminFake);
        } else {
            $password = $adminFake['password'];

            $adminFake['password'] = Hash::make($adminFake['password']);

            $this->adminRepository->create($adminFake);

            $this->info('  Admin created successfuly');

            $adminFake['password'] = $password;

            $this->messageCreate($adminFake);
        }
    }

    /**
     * Create message.
     *
     * @param array
     * @return null
     */
    protected function messageCreate ($admin)
    {   
        $this->line('********************************************************************************');
        $this->info('  Name: '. $admin['name']);
        $this->line('********************************************************************************');
        $this->info('  Email: '. $admin['email']);
        $this->line('********************************************************************************');
        $this->info('  Password: '. $admin['password']);
        $this->line('********************************************************************************');
    }
}
