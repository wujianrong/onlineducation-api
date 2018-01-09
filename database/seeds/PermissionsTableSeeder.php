<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                'id' => 26,
                'name' => 'role_lists',
                'display_name' => '角色列表',
                'parent_id' => 22,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 27,
                'name' => 'role_edit',
                'display_name' => '角色编辑',
                'parent_id' => 22,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 28,
                'name' => 'role_create',
                'display_name' => '角色新建',
                'parent_id' => 22,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 29,
                'name' => 'role_del',
                'display_name' => '角色删除',
                'parent_id' => 22,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]


            , [
                'id' => 30,
                'name' => 'perm_lists',
                'display_name' => '权限列表',
                'parent_id' => 21,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 31,
                'name' => 'perm_edit',
                'display_name' => '权限编辑',
                'parent_id' => 21,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 32,
                'name' => 'perm_del',
                'display_name' => '权限删除',
                'parent_id' => 21,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]


            , [
                'id' => 33,
                'name' => 'edu_lists',
                'display_name' => '教务模板列表',
                'parent_id' => 24,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 34,
                'name' => 'edu_edit',
                'display_name' => '教务编辑',
                'parent_id' => 24,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 35,
                'name' => 'edu_del',
                'display_name' => '教务删除',
                'parent_id' => 24,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]

            , [
                'id' => 36,
                'name' => 'user_lists',
                'display_name' => '账号列表',
                'parent_id' => 23,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 37,
                'name' => 'user_edit',
                'display_name' => '账号编辑',
                'parent_id' => 23,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 38,
                'name' => 'user_del',
                'display_name' => '账号删除',
                'parent_id' => 23,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 39,
                'name' => 'user_frozen',
                'display_name' => '账号冻结',
                'parent_id' => 23,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 40,
                'name' => 'user_refrozen',
                'display_name' => '账号解冻',
                'parent_id' => 23,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]


            , [
                'id' => 41,
                'name' => 'guest_lists',
                'display_name' => '用户列表',
                'parent_id' => 19,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 42,
                'name' => 'guest_edit',
                'display_name' => '用户编辑',
                'parent_id' => 19,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 43,
                'name' => 'guest_frozen',
                'display_name' => '用户冻结',
                'parent_id' => 19,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 44,
                'name' => 'guest_refrozen',
                'display_name' => '用户解冻',
                'parent_id' => 19,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 45,
                'name' => 'guest_del',
                'display_name' => '删除用户',
                'parent_id' => 19,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]

            , [
                'id' => 46,
                'name' => 'video_lists',
                'display_name' => '视频列表',
                'parent_id' => 9,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 47,
                'name' => 'video_edit',
                'display_name' => '视频编辑',
                'parent_id' => 9,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 48,
                'name' => 'video_del',
                'display_name' => '视频删除',
                'parent_id' => 9,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]

            , [
                'id' => 49,
                'name' => 'genre_lists',
                'display_name' => '分类列表',
                'parent_id' => 11,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 50,
                'name' => 'genre_edit',
                'display_name' => '分类编辑',
                'parent_id' => 11,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 51,
                'name' => 'genre_del',
                'display_name' => '分类删除',
                'parent_id' => 11,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]


            , [
                'id' => 52,
                'name' => 'nav_lists',
                'display_name' => '栏目列表',
                'parent_id' => 10,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 53,
                'name' => 'nav_edit',
                'display_name' => '栏目编辑',
                'parent_id' => 10,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 54,
                'name' => 'nav_del',
                'display_name' => '栏目删除',
                'parent_id' => 10,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]


            , [
                'id' => 55,
                'name' => 'lesson_lists',
                'display_name' => '课程列表',
                'parent_id' => 8,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 56,
                'name' => 'lesson_up',
                'display_name' => '课程上架',
                'parent_id' => 8,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 57,
                'name' => 'lesson_down',
                'display_name' => '课程下架',
                'parent_id' => 8,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
//            [
//                'id' => 58,
//                'name' => 'lesson_pre',
//                'display_name' => '课程预览',
//                'parent_id' => 8,
//                'created_at' => \Carbon\Carbon::now(),
//                'updated_at' => \Carbon\Carbon::now()
//            ],
            [
                'id' => 59,
                'name' => 'lesson_edit',
                'display_name' => '课程编辑',
                'parent_id' => 8,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 60,
                'name' => 'lesson_del',
                'display_name' => '课程删除',
                'parent_id' => 8,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 61,
                'name' => 'lesson_student_lists',
                'display_name' => '学员列表',
                'parent_id' => 8,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 62,
                'name' => 'lesson_section_lists',
                'display_name' => '课程章节列表',
                'parent_id' => 8,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 63,
                'name' => 'lesson_section_edit',
                'display_name' => '课程章节编辑',
                'parent_id' => 8,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 64,
                'name' => 'lesson_section_del',
                'display_name' => '课程章节删除',
                'parent_id' => 8,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]

            , [
                'id' => 65,
                'name' => 'label',
                'display_name' => '标签管理',
                'parent_id' => 19,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
//            , [
//                'id' => 66,
//                'name' => 'label_lists',
//                'display_name' => '标签列表',
//                'parent_id' => 19,
//                'created_at' => \Carbon\Carbon::now(),
//                'updated_at' => \Carbon\Carbon::now()
//            ]
            , [
                'id' => 67,
                'name' => 'label_edit',
                'display_name' => '标签编辑',
                'parent_id' => 19,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 68,
                'name' => 'label_del',
                'display_name' => '标签删除',
                'parent_id' => 19,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]

            , [
                'id' => 69,
                'name' => 'discusse_lists',
                'display_name' => '评论列表',
                'parent_id' => 12,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 70,
                'name' => 'discusse_del',
                'display_name' => '评论删除',
                'parent_id' => 12,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 71,
                'name' => 'discusse_better',
                'display_name' => '精选评论',
                'parent_id' => 12,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 72,
                'name' => 'discusse_un_better',
                'display_name' => '取消精选评论',
                'parent_id' => 12,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]


            , [
                'id' => 73,
                'name' => 'teacher_lists',
                'display_name' => '讲师列表',
                'parent_id' => 16,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 74,
                'name' => 'teacher_edit',
                'display_name' => '讲师编辑',
                'parent_id' => 16,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 75,
                'name' => 'teacher_del',
                'display_name' => '讲师删除',
                'parent_id' => 16,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]


            , [
                'id' => 76,
                'name' => 'advert_lists',
                'display_name' => '广告列表',
                'parent_id' => 14,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 77,
                'name' => 'advert_edit',
                'display_name' => '广告编辑',
                'parent_id' => 14,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 78,
                'name' => 'advert_del',
                'display_name' => '广告删除',
                'parent_id' => 14,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]


            , [
                'id' => 79,
                'name' => 'message_lists',
                'display_name' => '消息列表',
                'parent_id' => 13,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 80,
                'name' => 'sys_lists',
                'display_name' => '系统消息列表',
                'parent_id' => 13,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]

            , [
                'id' => 81,
                'name' => 'vip_lists',
                'display_name' => 'vip列表',
                'parent_id' => 15,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 82,
                'name' => 'vip_edit',
                'display_name' => 'vip编辑',
                'parent_id' => 15,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 83,
                'name' => 'vip_up',
                'display_name' => 'vip上架',
                'parent_id' => 15,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 84,
                'name' => 'vip_down',
                'display_name' => 'vip下架',
                'parent_id' => 15,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 85,
                'name' => 'vip_del',
                'display_name' => 'vip删除',
                'parent_id' => 15,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 86,
                'name' => 'vip_order_list',
                'display_name' => '订单列表',
                'parent_id' => 15,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 87,
                'name' => 'order_lists',
                'display_name' => '订单列表',
                'parent_id' => 20,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
//            , [
//                'id' => 88,
//                'name' => 'order_edit',
//                'display_name' => '订单编辑',
//                'parent_id' => 20,
//                'created_at' => \Carbon\Carbon::now(),
//                'updated_at' => \Carbon\Carbon::now()
//            ]
            , [
                'id' => 89,
                'name' => 'order_del',
                'display_name' => '订单删除',
                'parent_id' => 20,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 90,
                'name' => 'index_index',
                'display_name' => '后台主页',
                'parent_id' => 7,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 91,
                'name' => 'setting_index',
                'display_name' => '首页设置',
                'parent_id' => 17,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 92,
                'name' => 'wechat',
                'display_name' => '公众号关注设置',
                'parent_id' => 18,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 93,
                'name' => 'log_lists',
                'display_name' => '系统日志管理',
                'parent_id' => 25,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]



            , [
                'id' => 7,
                'name' => 'index',
                'display_name' => '主页',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 8,
                'name' => 'lesson',
                'display_name' => '课程管理',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 9,
                'name' => 'video',
                'display_name' => '视频管理',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 10,
                'name' => 'nav',
                'display_name' => '栏目管理',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 11,
                'name' => 'genre',
                'display_name' => '分类管理',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 12,
                'name' => 'discusse',
                'display_name' => '评论管理',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 13,
                'name' => 'message',
                'display_name' => '消息管理',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 14,
                'name' => 'advert',
                'display_name' => '广告管理',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 15,
                'name' => 'vip',
                'display_name' => 'vip管理',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 16,
                'name' => 'teacher',
                'display_name' => '讲师管理',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 17,
                'name' => 'setting_index',
                'display_name' => '首页设置管理',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 18,
                'name' => 'wechat',
                'display_name' => '公众号管理',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 19,
                'name' => 'guest',
                'display_name' => '用户管理',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 20,
                'name' => 'order',
                'display_name' => '订单管理',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 21,
                'name' => 'perm',
                'display_name' => '权限管理',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ] , [
                'id' => 22,
                'name' => 'role',
                'display_name' => '角色管理',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 23,
                'name' => 'user',
                'display_name' => '账号管理',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 24,
                'name' => 'edu',
                'display_name' => '教务管理',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ], [
                'id' => 25,
                'name' => 'log_index',
                'display_name' => '系统日志',
                'parent_id' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        ]);
    }
}
