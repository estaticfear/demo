<?php

namespace Cmat\Faq;

use Cmat\Faq\Contracts\Faq as FaqContract;
use Theme;

class FaqSupport implements FaqContract
{
    public function registerSchema(FaqCollection $faqs): void
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [],
        ];

        foreach ($faqs->toArray() as $faq) {
            $schema['mainEntity'][] = [
                '@type' => 'Question',
                'name' => $faq->getQuestion(),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq->getAnswer(),
                ],
            ];
        }

        $schema = json_encode($schema);

        Theme::asset()
            ->container('header')
            ->writeScript('faq-schema', $schema, attributes: ['type' => 'application/ld+json']);
    }
}
