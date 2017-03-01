<?php
return [
	'backendModules' => [],
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'offset' => 'col-sm-offset-2',
            'label' => 'col-sm-2',
            'wrapper' => 'col-sm-9',
            'error' => '',
            'hint' => 'col-sm-2'
        ]
    ],
	'imageEdgeLimit' => 1920,
    'cmsWebTitle' => 'cms_web_title',  // filled by install script
    'changeChars' => [
	    'Ř' => 'R',
	    'Š' => 'S',
	    'Ž' => 'Z',
	    'Ť' => 'T',
	    'Č' => 'C',
	    'Ů' => 'U',
	    'Ň' => 'N',
	    'Ě' => 'E',
	    'Ď' => 'D',
	    'ř' => 'r',
	    'š' => 's',
	    'ž' => 'z',
	    'ť' => 't',
	    'č' => 'c',
	    'ů' => 'u',
	    'ň' => 'n',
	    'ě' => 'e',
	    'ď' => 'd',
	    ' ' => '_',
	    ',' => '_',
	    ';' => '_',
	    '"' => '',
	    '\'' => '',
	    '–' => '-'
    ],
	'kcfDefaultOptions' => [
		'disabled' => false,
		'denyZipDownload' => true,
		'denyUpdateCheck' => true,
		'denyExtensionRename' => true,
		'theme' => 'default',
		'access' =>[    // @link http://kcfinder.sunhater.com/install#_access
			'files' =>[
				'upload' => true,
				'delete' => true,
				'copy' => true,
				'move' => false,
				'rename' => false,
			],
			'dirs' =>[
				'create' => true,
				'delete' => false,
				'rename' => false,
			],
		],
		'types'=>[  // @link http://kcfinder.sunhater.com/install#_types
			'files' => [
				'type' => '',
			],
			'images' => [
				'type' => '*img',
			],
		],
		'thumbsDir' => '.thumbs',
		'thumbWidth' => 100,
		'thumbHeight' => 100,
		'maxImageWidth' => 1920,
		'maxImageHeight' => 1920
	],
    'newsletterArticleImage' => [
	    'width' => 200,
	    'height' => 200
    ],
    'maxEmailsCount' => 98
];