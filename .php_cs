<?php

return PhpCsFixer\Config::create()
    ->setRules(array(
        '@Symfony' => true,
        'concat_space' => array('spacing' => 'one'),
        'simplified_null_return' => false,
        'phpdoc_align' => false,
        'phpdoc_separation' => false,
        'phpdoc_to_comment' => false,
        'cast_spaces' => false,
        'blank_line_after_opening_tag' => false,
        'phpdoc_annotation_without_dot' => false,
    ))
    ->setUsingCache(true)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
            ->exclude([
                'vendor',
                'bin/.ci',
                'bin/.travis',
                'doc',
                'app/cache',
                'ezpublish_legacy',
            ])
    )
;
