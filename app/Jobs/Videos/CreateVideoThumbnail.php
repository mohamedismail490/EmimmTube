<?php

namespace App\Jobs\Videos;

use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateVideoThumbnail implements ShouldQueue
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
        $thumbnailFrameSecond = (int) ($duration / 2);
        $videoFile->getFrameFromSeconds($thumbnailFrameSecond)
            ->export()
            ->toDisk('local')
            ->save("public/thumbnails/{$this->video->id}.png");

        $this->video->update([
            'thumbnail' => Storage::url("public/thumbnails/{$this->video->id}.png")
        ]);
    }
}
