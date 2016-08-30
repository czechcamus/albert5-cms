<?php
return [
	[
		'pattern' => 'install',
		'route' => 'install/default/index'
	],
	[
		'pattern' => '<name>,<id:\d+>/<article>,<ida:\d+>/<web>/<language:\w{2}>',
		'route' => 'page/article'
	],
	[
		'pattern' => '<name>,<id:\d+>/<article>,<ida:\d+>/<language:\w{2}>',
		'route' => 'page/article'
	],
	[
		'pattern' => '<name>,<id:\d+>/<article>,<ida:\d+>/<web>',
		'route' => 'page/article'
	],
	[
		'pattern' => '<name>,<id:\d+>/<article>,<ida:\d+>',
		'route' => 'page/article'
	],
	[
		'pattern' => '<name>,<id:\d+>/<web>/<language:\w{2}>',
		'route' => 'page/menu'
	],
	[
		'pattern' => '<name>,<id:\d+>/<language:\w{2}>',
		'route' => 'page/menu'
	],
	[
		'pattern' => '<name>,<id:\d+>/<web>',
		'route' => 'page/menu'
	],
	[
		'pattern' => '<name>,<id:\d+>',
		'route' => 'page/menu'
	],
	[
		'pattern' => 'site/<action>/<web>/<language:\w{2}>',
		'route' => 'site/<action>'
	],
	[
		'pattern' => 'site/<action>/<language:\w{2}>',
		'route' => 'site/<action>'
	],
	[
		'pattern' => 'site/<action>/<web>',
		'route' => 'site/<action>'
	],
	[
		'pattern' => 'site/<action>',
		'route' => 'site/<action>'
	],
	[
		'pattern' => 'site/<action>,<id:\d+>/<web>/<language:\w{2}>',
		'route' => 'site/<action>'
	],
	[
		'pattern' => 'site/<action>,<id:\d+>/<language:\w{2}>',
		'route' => 'site/<action>'
	],
	[
		'pattern' => 'site/<action>,<id:\d+>/<web>',
		'route' => 'site/<action>'
	],
	[
		'pattern' => 'site/<action>,<id:\d+>',
		'route' => 'site/<action>'
	],
	[
		'pattern' => 'page/search/<web>/<language:\w{2}>',
		'route' => 'page/search'
	],
	[
		'pattern' => 'page/search/<language:\w{2}>',
		'route' => 'page/search'
	],
	[
		'pattern' => 'page/search/<web>',
		'route' => 'page/search'
	],
	[
		'pattern' => 'page/tag/<tag:\w+>/<web>/<language:\w{2}>',
		'route' => 'page/tag'
	],
	[
		'pattern' => 'page/tag/<tag:\w+>/<language:\w{2}>',
		'route' => 'page/tag'
	],
	[
		'pattern' => 'page/tag/<tag:\w+>/<web>',
		'route' => 'page/tag'
	],
	[
		'pattern' => 'page/tag/<tag:\w+>',
		'route' => 'page/tag'
	],
	[
		'pattern' => 'page/rss/<web>/<language:\w{2}>',
		'route' => 'page/rss'
	],
	[
		'pattern' => 'page/rss/<language:\w{2}>',
		'route' => 'page/rss'
	],
	[
		'pattern' => 'page/rss/<web>',
		'route' => 'page/rss'
	],
	[
		'pattern' => '<web>/<language:\w{2}>',
		'route' => 'page/home'
	],
	[
		'pattern' => '<language:\w{2}>',
		'route' => 'page/home'
	],
	[
		'pattern' => '<web>',
		'route' => 'page/home'
	],
	[
		'pattern' => '',
		'route' => 'page/home'
	],
];