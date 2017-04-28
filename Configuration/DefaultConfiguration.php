<?php


return [

	'SYS'=>[
		'caching'=>[
			'cacheConfigurations'=>[
				'sudhaus7fetchcontent_cache'=>[
					'backend'=>\TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class,
					'frontend'=>\TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
					'groups'=>['pages'],
					'options'=>[
						'defaultLifetime'=>3600,
					]
				],
			],
		]
	]
];
