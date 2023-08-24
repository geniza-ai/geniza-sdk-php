<?php

declare(strict_types=1);

use CodingStandard\GenizaCodingStandard;
use Nexus\CsConfig\Factory;
use PhpCsFixer\Finder;

$finder = Finder::create()
	->files()
	->in([
		__DIR__ . '/src/',
		__DIR__ . '/tests/',
	])
	->exclude('build')
	->append([
		__FILE__,
		__DIR__ . '/rector.php',
	]);

$overrides = [
	'declare_strict_types'  => true,
	'void_return'           => true,
	'curly_braces_position' => [
		'control_structures_opening_brace'  => 'same_line',
		'anonymous_functions_opening_brace' => 'same_line',
		'functions_opening_brace'           => 'same_line',
		'classes_opening_brace'             => 'same_line',
		'anonymous_classes_opening_brace'   => 'same_line',
	],
	'no_superfluous_phpdoc_tags' => [
		'allow_mixed'         => true,
		'allow_unused_params' => true,
	],
];

$options = [
	'finder'    => $finder,
	'cacheFile' => 'build/.php-cs-fixer.cache',
	'indent'    => "\t",
];

return Factory::create(new GenizaCodingStandard(), $overrides, $options)->forProjects();
