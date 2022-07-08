<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Post;

class ImportOldPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:old_posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $admin = User::where('username', 'admin')
            ->first();

        if (!$admin) {
                $admin = User::create([
                    'name'  => 'Admin',
                    'username'  => 'admin',
                    'email' => 'admin@system.com',
                    'password'  => ''
                ]);

                try {
                    $client = new Client();
                    $request = $client->get('https://sq1-api-test.herokuapp.com/posts');
                    $response = $request->getBody()->getContents();
                    $response = json_decode($response);
        
                    foreach ($response->data as $post) {
                        $post = Post::create([
                            'title' => $post->title,
                            'description' => $post->description,
                            'publication_date' => $post->publication_date,
                            'user_id'   => $admin->id
                        ]);
                    }
        
                    $this->output->success(count($response->data) . " posts imported\n");
                    exit;
                } catch (\Exception $e) {
                    throw $e;
                };
        }
    }
}
