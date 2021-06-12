<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Exception;

class RepositoryPattern extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository_pattern {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new  Repository pattern';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        try {
            // check Interface exist
            if(file_exists(__DIR__ . '/../../Contracts/Repositories/' . $name . 'RepositoryInterface.php')) {
                throw new Exception(__('messages.exist', ["name" => "Interface"]));
            }
            
            // create Interface file
            $content = file_get_contents(__DIR__ . '/../../Contracts/Repositories/' . 'ExampleRepositoryInterface.php');
            $content = str_replace('Example', $name, $content);
            file_put_contents(__DIR__ . '/../../Contracts/Repositories/' . $name . 'RepositoryInterface.php', $content);
            $this->info(__('messages.created', ['name' => "Interface"]));

            // check repository exist
            if(file_exists(__DIR__ . '/../../Repositories/Eloquent/' . $name . 'Repository.php')) {
                throw new Exception(__('messages.exist', ["name" => "Repository"]));
            }

            // create Repository file
            $content = file_get_contents(__DIR__ . '/../../Repositories/Eloquent/' . 'ExampleRepository.php');
            $content = str_replace('Example', $name, $content);
            $content = str_replace('example', strtolower($name), $content);
            file_put_contents(__DIR__ . '/../../Repositories/Eloquent/' . $name . 'Repository.php', $content);
            $this->info(__('messages.created', ['name' => "Repository"]));

            // check Provider exist
            if(file_exists(__DIR__ . '/../../Providers/Repositories/' . $name . 'ServiceProvider.php')) {
                throw new Exception(__('messages.exist', ["name" => "Provider"]));
            }

            // create Provider file
            $content = file_get_contents(__DIR__ . '/../../Providers/Repositories/' . 'ExampleServiceProvider.php');
            $content = str_replace('Example', $name, $content);
            file_put_contents(__DIR__ . '/../../Providers/Repositories/' . $name . 'ServiceProvider.php', $content);
            $this->info(__('messages.created', ['name' => "Provider"]));
            
        } catch(Exception $error) {
            $this->error( $error->getMessage() );
        }
    } 
}