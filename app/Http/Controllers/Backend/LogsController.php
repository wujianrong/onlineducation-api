<?php

namespace App\Http\Controllers\Backend;


use App\Http\Resources\RevisionCollection;
use App\Models\Revision;
use App\Http\Controllers\Controller;
use App\Models\Advert;
use App\Models\Discusse;
use App\Models\Educational;
use App\Models\Genre;
use App\Models\Guest;
use App\Models\Label;
use App\Models\Lesson;
use App\Models\Message;
use App\Models\Order;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Video;
use App\Models\Vip;
use App\Models\Nav;
use Illuminate\Support\Facades\Cache;


class LogsController extends Controller
{

    /*判断模块*/
    private $revisionable_types = [
        'App\\Models\\User'        => [ '账号管理', 'User' ],
        'App\\Models\\Advert'      => [ '广告管理', 'Advert' ],
        'App\\Models\\Discusse'    => [ '评论管理', 'Discusse' ],
        'App\\Models\\Educational' => [ '教务设置', 'Educational' ],
        'App\\Models\\Genre'       => [ '分类管理', 'Genre' ],
        'App\\Models\\Guest'       => [ '用户管理', 'Guset' ],
        'App\\Models\\Label'       => [ '标签管理', 'Label' ],
        'App\\Models\\Lesson'      => [ '课程管理', 'Lesson' ],
        'App\\Models\\Message'     => [ '消息管理', 'Message' ],
        'App\\Models\\Nav'         => [ '栏目管理', 'Nav' ],
        'App\\Models\\Order'       => [ '订单管理', 'Order' ],
        'App\\Models\\Permission'  => [ '权限管理', 'Permission' ],
        'App\\Models\\Role'        => [ '角色管理', 'Role' ],
        'App\\Models\\Section'     => [ '章节管理', 'Section' ],
        'App\\Models\\Setting'     => [ '主页信息管理', 'Setting' ],
        'App\\Models\\Teacher'     => [ '讲师管理', 'Teacher' ],
        'App\\Models\\Video'       => [ '视频库管理', 'Video' ],
        'App\\Models\\Vip'         => [ 'Vip管理', 'Vip' ],
    ];

    private function getData( $logs )
    {
        foreach( $logs as $log ) {


            $content = null;
            $user_name = null;
            $key = '未知操作';
            $model_name = null;

            $log_keys = [
                'login'      => '登陆',
                'created_at' => '新建',
                'deleted_at' => '删除',
            ];

            /*判断操作方式*/
            if( array_key_exists( $log->key, $log_keys ) ){
                $key = $log_keys[$log->key];
            } elseif( $log->key == 'frozen' && $log->new_value == 0 ){
                $key = '解冻';
            } elseif( $log->key == 'frozen' && $log->new_value == 1 ){
                $key = '冻结';
            } elseif( $log->old_value !== null ){
                $key = '更新';
            }

            /*日志内容*/
            $log_type = $this->revisionable_types[$log->revisionable_type][1];
            switch($log_type) {
                case 'User':
                    $model_name = User::withTrashed()->whereId( $log->revisionable_id )->firstOrFail()->nickname;
                    break;

                case  'Advert':
                    $model_name = Advert::withTrashed()->whereId( $log->revisionable_id )->firstOrFail()->name;
                    break;

                case  'Discusse':
                    $model_name = Discusse::withTrashed()->whereId( $log->revisionable_id )->firstOrFail()->lesson->name;
                    break;

                case 'Educational':
                    $model_name = Educational::withTrashed()->whereId( $log->revisionable_id )->firstOrFail()->name;
                    break;

                case 'Genre':
                    $model_name = Genre::withTrashed()->whereId( $log->revisionable_id )->firstOrFail()->name;
                    break;

                case  'Guset':
                    $model_name = Guest::whereId( $log->revisionable_id )->firstOrFail()->name;
                    break;

                case 'Label':
                    $model_name = Label::withTrashed()->whereId( $log->revisionable_id )->firstOrFail()->name;
                    break;

                case 'Lesson':
                    $model_name = Lesson::withTrashed()->whereId( $log->revisionable_id )->firstOrFail()->name;
                    break;

                case 'Message':
                    $model_name = Message::whereId( $log->revisionable_id )->firstOrFail()->name;
                    break;

                case  'Nav':
                    $model_name = Nav::withTrashed()->whereId( $log->revisionable_id )->firstOrFail()->name;
                    break;

                case  'Order':
                    $model_name = Order::withTrashed()->whereId( $log->revisionable_id )->firstOrFail()->name;
                    break;

                case  'Permission':
                    $model_name = Permission::withTrashed()->whereId( $log->revisionable_id )->firstOrFail()->display_name;
                    break;

                case  'Role':
                    $model_name = Role::withTrashed()->whereId( $log->revisionable_id )->firstOrFail()->display_name;
                    break;

                case  'Section':
                    $section = Section::withTrashed()->whereId( $log->revisionable_id )->firstOrFail();
                    $lesson_name = $section->lesson_id ? $section->lesson->name . '】 - 【' : '未知课程 】 - 【';
                    $model_name = $lesson_name . $section->name;
                    break;

                case  'Setting':
                    $model_name = Setting::whereId( $log->revisionable_id )->firstOrFail()->name;
                    break;

                case 'Teacher':
                    $model_name = Teacher::withTrashed()->whereId( $log->revisionable_id )->firstOrFail()->name;
                    break;

                case  'Video':
                    $model_name = Video::withTrashed()->whereId( $log->revisionable_id )->firstOrFail()->name;
                    break;

                case  'Vip':
                    $model_name = Vip::withTrashed()->whereId( $log->revisionable_id )->firstOrFail()->name;
                    break;
            }

            if( $log->user_id ){
                $user_name = User::withTrashed()->findOrFail( $log->user_id )->nickname;
            } elseif( $log->guest_id ){
                $user_name = Guest::findOrFail( $log->guest_id )->nickname;
            } elseif( $key == '新建' && $log_type == 'Guset' ){
                $user_name = Guest::findOrFail( $log->revisionable_id )->nickname;
            } else{
                $user_name = '系统操作';
            }

            if( $key == '登陆' ){
                $content = ' 登陆了系统后台';
            }

            if( $key == '更新' ){
                if( $log_type == 'Discusse' && $log->key == 'is_better' ){
                    if( $log->new_value == 1 ){
                        $content = '评论设置为精选';
                    } else{
                        $content = '评论取消精选';
                    }

                } elseif( $log_type == 'Vip' && $log->key == 'status' ){
                    if( $log->new_value == 1 ){
                        $content = '上架';
                    } elseif( $log->new_value == 3 ){
                        $content = '下架';
                    }
                } elseif( $log_type == 'Lesson' ){
                    if( $log->key == 'status' ){
                        if( $log->new_value == 3 ){
                            $content = '上架';
                        } elseif( $log->new_value == 2 ){
                            $content = '下架';
                        }
                    } elseif( $log->key == 'type' ){
                        if( $log->new_value == 1 ){
                            $content = '修改为免费课程';
                        } elseif( $log->new_value == 2 ){
                            $content = '修改为精品课程';
                        } elseif( $log->new_value == 2 ){
                            $content = '修改为VIP课程';
                        }
                    } elseif( $log->key == 'is_top' ){
                        if( $log->new_value == 1 ){
                            $content = '置顶课程';
                        } else{
                            $content = '取消置顶课程';
                        }
                    } elseif( $log->key == 'is_nav' ){
                        if( $log->new_value == 1 ){
                            $content = '栏目推荐课程';
                        } else{
                            $content = '取消栏目推荐课程';
                        }
                    } else{
                        $content = '【' . $model_name . '】 字段' . $log->key . ' 【' . $log->old_value . '】 更新为 【' . $log->new_value . '】';
                    }

                } else{
                    $content = '【' . $model_name . '】 字段' . $log->key . ' 【' . $log->old_value . '】 更新为 【' . $log->new_value . '】';
                }
            }

            if( $key == '新建' ){
                if( $log_type == 'Guset' ){
                    $content = '加入了供应链微课堂';
                    $key = '登录';
                } elseif( $log_type == 'Discusse' ){
                    $content = '评论了课程【' . $model_name . '】';
                } else{
                    $content = $key . '了 【' . $model_name . '】';
                }
            }

            if( $key == '删除' ){
                $content = $key . '了 【' . $model_name . '】';
            }

            if( $key == '冻结' ){
                $content = $key . '了 账号【' . $model_name . '】';
            }

            if( $key == '解冻' ){
                $content = $key . '了 【' . $model_name . '】';
            }

            if( $user_name == '系统操作' ){
                if( $log_type == 'Video' ){
                    if( $log->key == 'status' ){
                        if( $log->new_value == 7 ){
                            $content = '视频转码失败';
                        } elseif( $log->new_value == 2 ){
                            $content = '视频转码成功';
                        }
                    }
                }
            }

            if( $log_type == 'Message' ){
                if( $user_name == '系统操作' ){
                    $content = '模板消息提醒发送';
                } else{
                    $content = '群发消息';
                }
            }

            $log->revisionable_type = $this->revisionable_types[$log->revisionable_type][0];
            $log->user = $user_name;
            $log->key = $key;
            $log->content = strip_tags( html_entity_decode( stripslashes( $content ) ) );

        }

        return $logs;

    }

    /**
     *系统日志
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists()
    {

        $logs = Cache::get( 'log_list' );
        if( !$logs ){
            $datas = Revision::orderBy( 'created_at', 'desc' )->paginate(1000);
            $logs = $this->getData( $datas );

            Cache::tags( 'logs' )->put( 'log_list', $logs, 21600 );
        }

        return response()->json( $logs  );
    }
}
