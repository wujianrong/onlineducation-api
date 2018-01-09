<template>
	<data-tables v-loading="loading" :data="data" :show-action-bar="false" :custom-filters="customFilters">

		<el-row slot="custom-tool-bar" style="margin-bottom: 10px">

			<el-col :span="2" class="delete_comment">
				<el-button type="primary">新建课程</el-button>
			</el-col>
			<el-col :span="2" :offset="10">
				<el-select v-model="customFilters[1].vals" multiple="multiple" placeholder="课程栏目">
					<el-option label="免费" value="免费"></el-option>
					<el-option label="VIP" value="VIP"></el-option>
					<el-option label="精品课程" value="精品课程"></el-option>
				</el-select>
			</el-col>
			<el-col :span="2" :offset="1">
				<el-select v-model="customFilters[2].vals" multiple="multiple" placeholder="收费类型">
					<el-option label="免费视频" value="免费视频"></el-option>
					<el-option label="VIP视频" value="VIP视频"></el-option>
					<el-option label="付费视频" value="付费视频"></el-option>
				</el-select>
			</el-col>
			<el-col :span="2" :offset="1">
				<el-select v-model="customFilters[3].vals" multiple="multiple" placeholder="课程状态">
					<el-option label="已上架" value="已上架"></el-option>
					<el-option label="已下架" value="已下架"></el-option>
					<el-option label="未上架" value="未上架"></el-option>
				</el-select>
			</el-col>
			<el-col :span="3" :offset="1">
				<el-input v-model="customFilters[0].vals" icon="search" placeholder="搜索标题/创建者">
				</el-input>
			</el-col>
		</el-row>
		<el-table-column width="85" prop="number" label="编号" key="number" sortable="custom">
		</el-table-column>
		<el-table-column width="255" prop="course" label="课程名称" key="course" sortable="custom">
			<template slot-scope="scope">
				<h1 class="blue">{{scope.row.course.cour_one}}</h1>
				<span>{{scope.row.course.cour_two}}</span>
			</template>
		</el-table-column>
		<el-table-column prop="charge" label="收费类型" key="charge" sortable="custom">
		</el-table-column>
		<el-table-column prop="column" label="栏目" key="column" sortable="custom">
		</el-table-column>
		<el-table-column prop="state" label="状态" key="state" sortable="custom">
			<template slot-scope="scope">
				<p v-if="scope.row.state == '已上架'" class="green">{{scope.row.state}}</p>
				<p v-if="scope.row.state == '已下架'" class="gray">{{scope.row.state}}</p>
				<p v-if="scope.row.state == '未上架'" class="red">{{scope.row.state}}</p>
			</template>
		</el-table-column>
		<el-table-column prop="price" label="价格" key="price" sortable="custom">
		</el-table-column>
		<el-table-column prop="trainees" label="学员数" key="trainees" sortable="custom">
		</el-table-column>
		<el-table-column prop="founder" label="创建人" key="founder" sortable="custom">
			<template slot-scope="scope">
				<p class="blue">{{scope.row.founder.founder_one}}</p>
				<span>{{scope.row.founder.founder_two}}</span>
			</template>
		</el-table-column>
		<el-table-column label="操作" width="135">
			<template slot-scope="scope">
				<el-dropdown split-button @click="customButtonsForRowone(scope.row)" @command="customButtonsForRowtwo">
					管理
					<el-dropdown-menu slot="dropdown">
						<el-dropdown-item :command='{command: "a",scope:scope}'>预览</el-dropdown-item>
						<el-dropdown-item :command='{command: "b",scope:scope.row}'>复制课程</el-dropdown-item>
						<el-dropdown-item :command='{command: "b",scope:scope.row}' v-if="scope.row.state == '未上架'">上架课程</el-dropdown-item>
						<el-dropdown-item :command='{command: "b",scope:scope.row}' v-if="scope.row.state == '已上架'">下架课程</el-dropdown-item>
						<el-dropdown-item :command='{command: "b",scope:scope.row}' v-if="scope.row.state == '未上架' || scope.row.state == '已下架'">删除课程</el-dropdown-item>
					</el-dropdown-menu>
				</el-dropdown>
			</template>
		</el-table-column>
	</data-tables>
</template>

<script>
	export default {
		data() {
			return {
				data: [{
					'number': '6',//编号
					'courses': 'AEO政策解读与实地认证分类：预归类学习',//课程名称，配合搜索使用
					'course': {'cour_one':'AEO政策解读与实地认证','cour_two':'分类：预归类学习'},//课程名称，换行使用
					'charge': '免费视频',//收费类型
					'column': '免费',//栏目
					'state': '已上架',//状态
					'price': '0.00',//价格
					'trainees': '100',//学员数
					'founders': 'Admin,2017-11-10 15:56',//创建人，配合搜索使用
					'founder': {'founder_one':'Admin','founder_two':'2017-11-10 15:56'}//创建人，换行使用
				},{
					'number': '5',
					'courses': 'AEO政策解读与实地认证分类：AEO认证',
					'course': {'cour_one':'AEO政策解读与实地认证','cour_two':'分类：AEO认证'},
					'charge': 'VIP视频',
					'column': 'VIP',
					'state': '已下架',
					'price': '0.00',
					'trainees': '88',
					'founders': 'Admin,2017-11-15 15:56',
					'founder': {'founder_one':'Admin','founder_two':'2017-11-15 15:56'}
				},{
					'number': '4',
					'courses': 'AEO政策解读与实地认证分类：政策解读',
					'course': {'cour_one':'AEO政策解读与实地认证','cour_two':'分类：政策解读'},
					'charge': '付费视频',
					'column': '精品课程',
					'state': '未上架',
					'price': '0.00',
					'trainees': '10',
					'founders': 'Admin,2017-11-12 15:56',
					'founder': {'founder_one':'Admin','founder_two':'2017-11-12 15:56'}
				},{
					'number': '6',
					'courses': 'AEO政策解读与实地认证分类：预归类学习',
					'course': {'cour_one':'AEO政策解读与实地认证','cour_two':'分类：预归类学习'},
					'charge': '免费视频',
					'column': '免费',
					'state': '已上架',
					'price': '0.00',
					'trainees': '100',
					'founders': 'Admin,2017-11-17 15:56',
					'founder': {'founder_one':'Admin','founder_two':'2017-10-10 15:56'}
				},{
					'number': '6',
					'courses': 'AEO政策解读与实地认证分类：预归类学习',
					'course': {'cour_one':'AEO政策解读与实地认证','cour_two':'分类：预归类学习'},
					'charge': '免费视频',
					'column': '免费',
					'state': '已上架',
					'price': '0.00',
					'trainees': '100',
					'founders': 'Admin,2017-11-19 15:56',
					'founder': {'founder_one':'Admin','founder_two':'2017-10-10 15:56'}
				},{
					'number': '6',
					'courses': 'AEO政策解读与实地认证分类：预归类学习',
					'course': {'cour_one':'AEO政策解读与实地认证','cour_two':'分类：预归类学习'},
					'charge': '免费视频',
					'column': '免费',
					'state': '已上架',
					'price': '0.00',
					'trainees': '100',
					'founders': 'Admin,2017-11-12 15:56',
					'founder': {'founder_one':'Admin','founder_two':'2017-10-10 15:56'}
				}],
				customFilters: [{
					vals: '',
					props: ['number', 'courses', 'charge', 'column', 'state', 'price', 'trainees', 'founders'],
				}, {
					vals: []
				}, {
					vals: []
				}, {
					vals: []
				}, {
					vals: []
				}],
				loading: false,
				multipleSelection: [],
			}
		},
		methods: {
			customButtonsForRowone(row) {
				this.$message(`repairing ${row.评论内容}`)
			},
			customButtonsForRowtwo(row) {
				if(row.command == "a") {
					this.data.splice(row.scope.$index, 1)
				} else if(row.command == "b") {
					//this.$message(`repairing ${row.scope.id}`);
					let ids = []
					this.multipleSelection.map((item) => {
						ids.push(item.id)
					});
					console.log(ids);
				}
				//this.$message(`repairing ${command.id}`);
			}
		},
		mounted: function() {

		}
	}
</script>
<style>
	.delete_comment {
		text-align: left;
	}
	
	.xuan {
		line-height: 1;
		padding: 10px 15px;
		display: inline-block;
	}
	
	.demo-table-expand {
		font-size: 0;
	}
	
	.demo-table-expand label {
		width: 90px;
		color: #99a9bf;
	}
	
	.demo-table-expand .el-form-item {
		margin-right: 0;
		margin-bottom: 0;
		width: 50%;
	}
	
	.el-row {
		margin-bottom: 10px;
		padding: 10px;
		background: #F5F5F5;
		border-radius: 4px;
	}
	
	.el-table td,
	.el-table th {
		padding: 12px 0 12px 10px;
	}
</style>