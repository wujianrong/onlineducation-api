<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Resources\DiscusseCollection;
use App\Models\Discusse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class DiscusseController extends Controller
{

    /**
     * @var \GuzzleHttp\Client
     */
    protected $http;

    /**
     * TokenProxy constructor.
     *
     * @param $http
     */
    public function __construct( \GuzzleHttp\Client $http )
    {
        $this->http = $http;
    }

    /**
     * 课程评论
     * @param $lesson_id
     * @return DiscusseCollection
     */
    public function lessonDiscusse( $lesson_id )
    {
        $discusses = Cache::get( 'guest_lesson_descusse_list' );
        if( !$discusses ){
            $discusses = Discusse::where( 'lesson_id', $lesson_id )->where( 'is_better', 1 )->orderBy( 'created_at', 'desc' )->paginate( 8 );
            Cache::tags( 'discusses' )->put( 'guest_lesson_descusse_list', $discusses, 21600 );
        }

        return new DiscusseCollection( $discusses );
    }

    /**
     * 评论课程
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( Request $request, $id )
    {

        $content = htmlentities( addslashes( $request->get( 'content' ) ) );
        $contents = $this->mbStrSplit( $content, 200 );

        $reslut = $this->checkContent( $contents );
        $data = [
            'content'   => $content,
            'guest_id'  => guest_user()->id,
            'lesson_id' => $id,
        ];

        if( $reslut ){
            return response()->json( [ 'status' => false, 'word' => $reslut ] );
        } else{
            Discusse::storeCache( $data, 'discusses' );//发送成功

            return response()->json( [ 'status' => true ] );
        }

    }

    /**
     * 对中英文混杂字符进行分割
     *
     * @param $string
     * @param int $len
     * @return array
     */
    function mbStrSplit( $string, $len = 1 )
    {
        $start = 0;
        $strlen = mb_strlen( $string );
        while($strlen) {
            $array[] = mb_substr( $string, $start, $len, "utf8" );
            $string = mb_substr( $string, $len, $strlen, "utf8" );
            $strlen = mb_strlen( $string );
        }
        return $array;
    }

    /**
     * 对关键词过滤
     * @param $contents
     * @return bool|string
     */
    private function checkContent( $contents )
    {
        $word = '';
        foreach( $contents as $content ) {
            $data = [
                'str'   => $content,
                'token' => env( 'HOAPI_TOKEN', '01fb627d4160bffb5a92fe5e334846a3' ),
            ];

            $response = $this->http->post( 'http://www.hoapi.com/index.php/Home/Api/check', [
                'form_params' => $data
            ] );

            $token = json_decode( (string)$response->getBody(), true );

            if( !$token['status'] ){
                $word .= implode( '、', array_pluck( $token['data']['error'], 'word' ) );
            }

        }

        if( empty( $word ) ){
            return false;
        } else{
            return $word;
        }
    }
}
