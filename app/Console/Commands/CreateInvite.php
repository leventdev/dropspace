<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invite;
use Illuminate\Support\Str;

class CreateInvite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dropspace:invite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a one-time invite link';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $confirmation = $this->ask('Are you sure you want to create an invite? (y/n)');
        if ($confirmation == 'y') {
            $invite = new Invite();
            $invite->created_by = $this->ask('Who is creating the invite?');
            $invite->used = false;
            $invite->code = Str::random(16);
            $invite->save();
            $this->info('Invite created successfully.');
            $this->info('Invite link: ' . env('APP_URL') . '/invite/' . $invite->code);
        } else {
            $this->info('Invite not created.');
            return 0;
        }
    }
}
