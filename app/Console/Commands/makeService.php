<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class makeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'makeService';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a Service in app\Services';

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
    public function handle(Filesystem $files)
    {

        $fileName = $this->ask('Name of the Service (without Service at the end; "User")');

        $file = "${fileName}Service.php";
        
        $file= '/Services/'.$file;

$content = "<?php
namespace App\Services;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Request;
use App\Models;

class ".$fileName."Service extends Service {

}";

        Storage::disk('app')->put($file, $content);

        return;

        // if(!$files->put($file, $contents)) 
        //     return $this->error('Something went wrong!');
    
        
    }
}
