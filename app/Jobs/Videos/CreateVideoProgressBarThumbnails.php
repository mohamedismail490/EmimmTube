<?php

namespace App\Jobs\Videos;

use FFMpeg\Filters\Frame\FrameFilters;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateVideoProgressBarThumbnails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $videoFile = FFMpeg::fromDisk('local')
            ->open($this->video->path);
        $duration = $videoFile->getDurationInSeconds();


        $v = FFMpeg::fromDisk('local')
            ->open($this->video->path);
        for ($i = 1; $i <= intval($duration); $i++) {
            $v = $v->getFrameFromSeconds($i)
                ->export()
                ->addFilter(function (FrameFilters $filters) {
                    $filters->custom('scale=150:84');
                })
                ->toDisk('local')
                ->save("public/thumbnails/{$this->video->id}/{$i}/{$this->video->id}_{$i}.png");
            $this->video->progressbar_thumbnails()->create([
                'duration'  => $i,
                'thumbnail' => Storage::url("public/thumbnails/{$this->video->id}/{$i}/{$this->video->id}_{$i}.png")
            ]);
        }
    }
}
