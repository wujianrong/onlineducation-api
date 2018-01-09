<?php

namespace App\Http\Controllers\Backend;

use App\Http\Resources\VideoCollection;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\Video;
use App\Models\VideoUrl;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Storage;
use Vod\VodApi;

/**
 * Class VideoController
 * @package App\Http\Controllers\Backend
 */
class VideoController extends Controller
{

    /**
     * @var string
     */
    protected $cache_key;


    /**
     * VideoController constructor.
     * @param mixed $secretId
     * @param mixed $secretKey
     */
    public function __construct()
    {
        $this->cache_key = 'videos';
    }

    /**
     * 视频列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists()
    {

        $videos = Video::recent( $this->cache_key );

        return new VideoCollection( $videos );
    }

	/**
	 * 视频名称
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function names()
	{
		$video_names = Cache::get( 'video_names' );
		if( !$video_names ){
			$video_names = Video::all()->pluck('name')->toArray();
			Cache::tags( 'videos' )->put( 'video_names', $video_names, 21600 );
		}

		return response()->json( $video_names );
	}

    /**
     * 返回转码成功，并且没有被章节使用的视频列表
	 *
     * @return VideoCollection
     */
    public function successList()
    {
    	$section_video_ids = Section::all()->pluck('video_id')->toArray();
        $videos = Cache::get( 'video_success_list' );
        if( !$videos ){
            $videos = Video::with( [ 'video_urls' ] )->orderBy( 'created_at', 'desc' )->whereStatus( 2 )->whereNotIn('id',$section_video_ids)->get();
            Cache::tags( $this->cache_key )->put( 'video_success_list', $videos, 21600 );
        }

        return new VideoCollection( $videos );
    }

    /**
     * 视频查看
     * @param Video $video
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit( $id )
    {
		$video_names = Cache::get( 'video_names' );
		if( !$video_names ){
			$video_names = Video::where('id','!=',$id)->get()->pluck('name')->toArray();
			Cache::tags( 'videos' )->put( 'video_names', $video_names, 21600 );
		}

        $video = Video::getCache( $id, $this->cache_key );

        return response()->json(['video' => new \App\Http\Resources\Video( $video ),'video_names' => $video_names]) ;
    }

    /**
     * 获取视频url数据，并格式化
     * @param $id
     * @return array
     */
    public function getVideoUrlData( $video_data, $fileId )
    {

        $parms = [
            'fileId' => $fileId,
        ];

        $video = videoCommon( 'GetVideoInfo', $parms, Carbon::now()->timestamp );

        if( $video['code'] == 0 ){
            foreach( $video['transcodeInfo']['transcodeList'] as $index => $transImage ) {
                $size = round( $transImage['size'] / 1024 / 1024, 2 ) . 'M';

                /*格式化时长*/
                $hour = floor( $transImage['duration'] / 3600 );
                $minute = floor( ($transImage['duration'] - $hour * 3600) / 60 );
                $second = $transImage['duration'] - ($hour * 3600 + $minute * 60);

                $duration = $hour . '时' . $minute . '分' . $second . '秒';
                $url = $transImage['url'];
                $video_id = $video_data->id;

                $data_url = [
                    'size'     => $size,
                    'duration' => $duration,
                    'url'      => $url,
                    'video_id' => $video_id,
                ];

                $video_urls = $video_data->video_urls()->get();
                if( $video_urls->count() && $index <= $video_urls->count() - 1 ){
                    VideoUrl::updateCache( $video_urls[$index]->id, [
                        'size'     => $size,
                        'duration' => $duration,
                        'url'      => $url,
                    ], 'video_urls' );

                } else{
                    VideoUrl::storeCache( $data_url, 'video_urls' );
                }
            }

        } else{
            return false;
        }
    }

    /**
     * 更新视频数据
     * @param $video_id
     * @param $data
     */
    public function updateVideo( $video, $data )
    {
        $video->update( $data );
        Video::updateCache( $video->id, $data, $this->cache_key );
    }

    /** 更新视频*/
    public function update( $id, Request $request )
    {
        $newName = $request->get( 'name' );
        $fileId = Video::getCache( $id, 'vides' )->fileId;

        $parms = [
            'fileId'   => $fileId,
            'fileName' => $newName,
        ];

        $video = videoCommon( 'ModifyVodInfo', $parms, Carbon::now()->timestamp );

        if( $video['code'] == 0 && $video['codeDesc'] == 'Success' ){

            Video::updateCache( $id, [ 'name' => $newName ], $this->cache_key );

            return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
        } else{
            return response()->json( [ 'status' => false, 'message' => '操作失败' ] );
        }
    }

    /** 创建视频*/
    public function store( Request $request )
    {
        $file = $request->file( 'video' );
        $totalChunks = $request->get( 'totalChunks' );//总块数
        $chunkNumber = $request->get( 'chunkNumber' );//当前顺序
        $identifier = $request->get( 'identifier' );//每个文件的唯一标示

        //判断文件是否上传成功
        if( $file->isValid() ){
            //获取原文件名
            $originalName = $file->getClientOriginalName();
            if( $identifier !== Cache::get( 'identifier' ) ){
                Cache::forget( 'meragefilename' );
                Cache::forget( 'identifier' );
            }
            //扩展名
            $ext = $file->getClientOriginalExtension();

            if( !in_array( $ext, [ 'mp4' ] ) ){
                return response()->json( [
                    'status'  => false,
                    'message' => '视频格式必须为mp4格式',
                ] );
            }

            //文件类型
//            $type = $file->getClientMimeType();
            //临时绝对路径
            $realPath = $file->getRealPath();
            $basefilename = str_replace( ' ', '', rtrim( $originalName, '.' . $ext ) );

            $meragefilename = Cache::get( 'meragefilename' );
            if( !$meragefilename ){
                Cache::forever( 'meragefilename', $basefilename . '-' . uniqid() );
                Cache::forever( 'identifier', $identifier );
                $meragefilename = Cache::get( 'meragefilename' );
            }

            $filename = $basefilename . '-' . uniqid() . '.' . $ext;
            $current_filename = $basefilename . '-' . $chunkNumber . '.' . $ext;

            Storage::disk( 'tem_videos' )->putFileAs( $meragefilename, new File( $realPath ), $current_filename );

            $files = Storage::disk( 'tem_videos' )->files( $meragefilename );

            if( count( $files ) == $totalChunks ){
                $bool_merge = $this->fileMerge( $filename, $meragefilename, $files );
                if( $bool_merge ){
                    //上传到腾讯服务器
                    $fileId = $this->upload( public_path() . '/storage/uploads/videos/' . $filename, $filename );
                    if( $fileId ){
                        $this->encodeTransVideo( $fileId );//转码
                        Video::storeCache( [
                            'name'   => $filename,
                            'fileId' => $fileId
                        ], $this->cache_key );
                    } else{
                        return response()->json( [ 'status' => false, 'message' => '操作失败' ] );
                    }
                    return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
                } else{
                    return response()->json( [ 'status' => false, 'message' => '文件合并失败' ] );
                }
            }
        } else{
            return response()->json( [ 'status' => false, 'message' => '临时文件夹生成失败' ] );
        }
    }

    /**
     * @test
     * @param $chunkNumber
     * @param $totalChunks
     * @param $filename
     */
    private function fileMerge( $filename, $meragefilename, $files )
    {

        foreach( $files as $file ) {
            $blob = file_get_contents( public_path() . '/storage/uploads/temVideos/' . $file );
            file_put_contents( public_path() . '/storage/uploads/videos/' . $filename, $blob, FILE_APPEND );
        }

        Storage::disk( 'tem_videos' )->deleteDirectory( $meragefilename );
        Cache::forget( 'meragefilename' );

        return Storage::disk( 'videos' )->exists( $filename ) ? true : false;
    }

    /** 上传视频 */
    private function upload( $videoPath, $filename )
    {

        VodApi::initConf( env( 'QCLOUD_SECRETID', null ), env( 'QCLOUD_SECRETKEY', null ) );

        $result = VodApi::upload(
            array(
                'videoPath' => $videoPath,
            ),
            array(
                'videoName'     => env( 'VIDEO_EXT_NAME', 'weClass_' ) . $filename,
                'procedure'     => 'myProcedure',
                'sourceContext' => 'test',
            )
        );

        if( $result['code'] == 0 && $result['message'] == 'success' ){
            return $result['data']['fileId'];
        } else{
            return false;
        }
    }

    /**
     * 删除视频
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy( Video $video )
    {

        $fileId = Video::getCache( $video->id, 'vides' )->fileId;

        $parms = [
            'fileId'   => $fileId,
            'priority' => 0,
        ];

        $result = videoCommon( 'DeleteVodFile', $parms, Carbon::now()->timestamp );
        if( $result['code'] == 0 || ($result['code'] == 4000 && $result['message'] == '(10008)视频不存在') ){

            VideoUrl::whereIn( 'id', $video->video_urls()->get()->pluck( 'id' )->toArray() )->delete();
            clear_cache( 'video_urls' );

            if( $video->section ){
                return response()->json( [ 'status' => false, 'message' => '不能删除使用中的视频' ] );
            } else{
                Video::deleteCache( $video->id, $this->cache_key );
                return response()->json( [ 'status' => true, 'message' => '操作成功' ] );
            }

        } else{
            return response()->json( [ 'status' => false, 'code' => $result['code'], 'message' => $result['message'] ] );
        }
    }

    /**
     * 视频加密转码
     * @param $fileId
     * @return \Illuminate\Http\JsonResponse
     */
    private function encodeTransVideo( $fileId )
    {

        $parms = [
            'fileId'                         => $fileId,
            'notifyMode'                     => 'Finish',
            'transcode.definition.0'         => 210,
            'transcode.definition.1'         => 220,
            'transcode.definition.3'         => 230,
            'transcode.definition.4'         => 240,
            'transcode.disableHigherBitrate' => 1,
            'transcode.drm.definition'       => 10,
        ];

        $result = videoCommon( 'ProcessFile', $parms, Carbon::now()->timestamp );

        if( $result['code'] == 0 ){
            Log::info( 'vodTaskId  ' . $result['vodTaskId'] . ' ProcessFile ----' . Carbon::now()->toDateTimeString() );
        } else{
            Log::info( $result['message'] . '  ProcessFile  ----' . Carbon::now()->toDateTimeString() );
        }
    }

    /**
     *视频加密密钥
     */
    public function getKeyUrl()
    {
        $edk = request( 'edk' );
        $fileId = request( 'fileId' );
        $keySource = request( 'keySource' );
        $token = request( 'token' );

        $video = Video::where( 'fileId', $fileId )->firstOrFail();
        if( optional( $video )->edk_key == $edk && $keySource == 'VodBuildInKMS' ){

//            if( auth()->guard('api')->user() && auth()->guard('api')->user()->tokens()->firstOrFail()->id == $token ){
            $parms = [
                'edkList.0' => $edk,
            ];

            $result = videoCommon( 'DescribeDrmDataKey', $parms, Carbon::now()->timestamp );
            if( $result['code'] == 0 ){
                $dk = base64_decode( $result['data']['keyList'][0]['dk'] );//dkData为Base64编码的密钥数据
                return $dk;
            } else{
                Log::info( $result['message'] . '  ProcessFile  ----' . Carbon::now()->toDateTimeString() );
            }
//            }
        }

    }

    public function getTaskList()
    {
        $parms = [
            'endTime'   => Carbon::now()->timestamp,
            'maxCount'  => 100,
            'startTime' => Carbon::now()->setDateTime( 2017, 12, 1, 0, 0, 0 )->timestamp,
            'status'    => 'FINISH',

        ];

        $result = videoCommon( 'GetTaskList', $parms, Carbon::now()->timestamp );

        dd( $result );
    }
}
