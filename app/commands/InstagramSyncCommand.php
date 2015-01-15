<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstagramSyncCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'instagram:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Instagram Feed.';

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
     * @return void
     */
    public function fire()
    {
        $this->info('Syncing Instagram photos...');

        $ids = array();
        $next_url = null;

        $client = new Guzzle\Http\Client('https://api.instagram.com/{version}', array(
            'version' => 'v1',
            'tag_name' => 'eesoc',
            'request.options' => array(
                'query' => array(
                    'client_id' => Config::get('instagram.client_id'),
                    'access_token' => Config::get('instagram.access_token'),
                ),
            ),
        ));

        do {
            if (isset($next_url)) {
                $this->info('Got next page');
                $next_url_params = parse_url($next_url);
                $request = $client->get($next_url_params['path'] . '?' . $next_url_params['query']);
            } else {
                $this->info('Fetch first page');
                $request = $client->get('tags/{tag_name}/media/recent');
            }

            $response = $request->send();

            if ($response->isSuccessful()) {
                $result = $response->json();
                foreach ($result['data'] as $photo) {
                    if (in_array($photo['id'], $ids)) {
                        continue;
                    }

                    $ids[] = $photo['id'];
                    InstagramPhoto::refresh($photo);
                    $this->info(sprintf('Got photo `%s`', $photo['id']));
                }


                $next_url = array_get($result, 'pagination.next_url');
            } else {
                $next_url = null;
            }

        } while (isset($next_url));

        $this->info('Successfully synchronized Instagram photos');
    }

}
