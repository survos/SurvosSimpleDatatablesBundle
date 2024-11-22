<?php

namespace Survos\SimpleDatatables\Twig;

use Symfony\WebpackEncoreBundle\Twig\StimulusTwigExtension;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use function Symfony\Component\String\u;

class TwigExtension extends AbstractExtension
{
    public function __construct()
    {
    }

    private function isValidUrl(string $url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('urlize', fn($x, $target='blank', string|null $label=null) => $this->isValidUrl($x)
                ? sprintf('<a target="%s" href="%s">%s</a>', $target, $x, $label ?: $x)
                : $x, [
                    'is_safe' => ['html'],
            ]),

            new TwigFilter('datatable', $this->datatable(...), [
                'needs_environment' => true,
                'is_safe' => ['html'],
            ]),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('reverseRange', fn ($x, $y): string => sprintf("%s-%s", $x, $y)),
            new TwigFunction('is_array', fn($x): bool => is_array($x)),
            new TwigFunction('is_object', fn($x): bool => is_object($x)),
            new TwigFunction('is_json', fn($x): bool => json_validate($x)),
            new TwigFunction('is_scalar', fn($x): bool => is_string($x) || is_int($x) || is_numeric($x)),
            new TwigFunction('is_list', fn($x): bool => is_array($x) && array_is_list($x)),

        ];
    }

    public function datatable($data): string
    {
        return "For now, call grid instead.";
    }

}
