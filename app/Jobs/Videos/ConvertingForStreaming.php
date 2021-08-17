<?php

namespace App\Jobs\Videos;

use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Video\X264;
use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConvertingForStreaming implements ShouldQueue
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
        $low    = (new X264())->setKiloBitrate(250);
        $medium = (new X264())->setKiloBitrate(500);
        $high   = (new X264())->setKiloBitrate(1000);

        FFMpeg::fromDisk('local')
            ->open($this->video->path)
            ->exportForHLS()
            ->onProgress(function ($percentage) {
                $this->video->update([
                    'percentage' => (int) $percentage
                ]);
            })
            ->addFormat($low)
            ->addFormat($medium)
            ->addFormat($high)
            ->save("public/videos/{$this->video->id}/{$this->video->id}.m3u8");
    }
}
