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
        $super  = (new X264())->setKiloBitrate(1500);

        $videoFile = FFMpeg::fromDisk('local')
            ->open($this->video->path);
        $height = $videoFile->getVideoStream()->getDimensions()->getHeight();
        $width  = $videoFile->getVideoStream()->getDimensions()->getWidth();
        $ratio  = $videoFile->getVideoStream()->getDimensions()->getRatio()->getValue();

        FFMpeg::fromDisk('local')
            ->open($this->video->path)
            ->exportForHLS()
            ->onProgress(function ($percentage) {
                $this->video->update([
                    'percentage' => (int) $percentage
                ]);
            })
            ->addFormat($low, function ($media) use ($height, $width, $ratio) {
                if ($height >= 360) {
                    $lowHeight = 360;
                    $lowWidth  = intval(round(($lowHeight * $ratio)));
                }else {
                    $lowHeight = $height;
                    $lowWidth  = $width;
                }
                $media->scale($lowWidth,$lowHeight);
            })
            ->addFormat($medium, function ($media) use ($height, $width, $ratio) {
                if ($height >= 480) {
                    $mediumHeight = 480;
                    $calculatedMediumWidth = intval(round(($mediumHeight * $ratio)));
                    $mediumWidth  = ($calculatedMediumWidth == 853 ? 854 : $calculatedMediumWidth);
                }else {
                    $mediumHeight = $height;
                    $mediumWidth  = $width;
                }
                $media->scale($mediumWidth,$mediumHeight);
            })
            ->addFormat($high, function ($media) use ($height, $width, $ratio) {
                if ($height >= 720) {
                    $highHeight = 720;
                    $highWidth  = intval(round(($highHeight * $ratio)));
                }else {
                    $highHeight = $height;
                    $highWidth  = $width;
                }
                $media->scale($highWidth,$highHeight);
            })
            ->addFormat($super, function ($media) use ($height, $width, $ratio) {
                if ($height >= 1080) {
                    $superHeight = 1080;
                    $superWidth  = intval(round(($superHeight * $ratio)));
                }else {
                    $superHeight = $height;
                    $superWidth  = $width;
                }
                $media->scale($superWidth,$superHeight);
            })
            ->save("public/videos/{$this->video->id}/{$this->video->id}.m3u8");
    }
}
