<?php

// +----------------------------------------------------------------------
// | NextStack  
// +----------------------------------------------------------------------
// | Copyright (c) Lyove https://github.com/lyove All rights reserved.
// +----------------------------------------------------------------------
// | Author: lyove
// +----------------------------------------------------------------------


return [
	/**
	 * 
	 *	['正则url','系统内真实链接','传输方式POST/GET,或者为空,则表示POST/GET都接收']
	 *	如果有多条匹配，默认第一条有效
	 *	demo：
	 *	['/\/base\/([0-9]+)\.html$/','Home/test/id/$1','GET'],
	 *	['/\/xbase\/([0-9]+)\/(\w+)\.html$/','Home/test/id/$1/sq/$2','POST'],
	 *	['/\/test_([0-9]+)\.html$/','Home/test/id/$1','GET'],
	 *	['/\/abc\.html$/','/shangpin.html',''],
	**/
	//以下规则不可删除，否则会报错！
	['/^\/screen-(\w+)-([0-9]+)-(.*)/','Screen/index/molds/$1/tid/$2/next_screen/$3',''],
	['/^\/searchAll(.*)/','Home/searchAll','GET'],
	['/^\/search(.*)/','Home/search','GET'],
];













