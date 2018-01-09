<?php

namespace App\Jobs;

use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SetVideoPlayTime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lesson, $section;
    public $tries = 5;//任务最大尝试次数。
    public $timeout = 120;//任务运行的超时时间。

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( Lesson $lesson, Section $section )
    {
        $this->lesson = $lesson;
        $this->section = $section;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $play_times = $this->section->play_times;
        $data_section = [
            'play_times' => $play_times + 1
        ];
        Section::updateCache( $this->section->id, $data_section, 'sections' );

        sleep( 1 );

        $data_lesson = [
            'play_times' => array_sum( $this->lesson->sections()->get()->pluck( 'play_times' )->toArray() )//所有视频播放次数总和
        ];
        Lesson::updateCache( $this->section->id, $data_lesson, 'sections' );

    }
}
