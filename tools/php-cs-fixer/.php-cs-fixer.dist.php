<?php

declare(strict_types=1);
use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfig;

$finder = Finder::create()
    ->in([
        realpath(__DIR__.'/../../src'),
        realpath(__DIR__.'/../../tests'),
    ]);
// ->exclude(['dir'])
// ->notPath('DependencyInjection/Configuration.php')

$config = (new Config())->setParallelConfig(new ParallelConfig(4, 20));

$rules = [
    '@Symfony' => true,
    'declare_strict_types' => true,
    '@PhpCsFixer' => true,
    'php_unit_test_class_requires_covers' => false,
    'php_unit_internal_class' => false,
    'array_syntax' => ['syntax' => 'short'],
    'array_indentation' => true,
    'no_superfluous_phpdoc_tags' => false,
    'ordered_imports' => true,
    'phpdoc_add_missing_param_annotation' => false,
    'phpdoc_order' => true,
    'phpdoc_to_comment' => false, // Needed to support @Desc annotation
    'single_line_throw' => false,
    'return_assignment' => false,
    'php_unit_method_casing' => ['case' => 'snake_case'],
    'blank_line_before_statement' => [
        'statements' => [
            'break',
            'continue',
            'declare',
            'return',
            'throw',
            'try',
            'if',
        ],
    ],
    'ordered_class_elements' => false,
    'multiline_whitespace_before_semicolons' => true,
    'phpdoc_annotation_without_dot' => true,
    'no_unused_imports' => true,
    'trailing_comma_in_multiline' => [
        'elements' => [
            'arrays',
            'arguments',
            'parameters',
            'match',
        ],
    ],
    'nullable_type_declaration' => ['syntax' => 'union'],
];

return $config->setRules($rules)
    ->setRiskyAllowed(true)
    ->setLineEnding("\n")
    ->setFinder($finder);
