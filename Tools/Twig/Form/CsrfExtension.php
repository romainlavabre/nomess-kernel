<?php

namespace Nomess\Tools\Twig\Form;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CsrfExtension extends AbstractExtension
{
    private const CACHE         = ROOT . 'var/cache/routes/route.php';

    public function getFunctions()
    {
        return [
            new TwigFunction('csrf', [$this, 'csrf']),
        ];
    }

    public function csrf(): void
    {
        echo '<input type="hidden" name="_token" value="' . $_SESSION['app']['_token'] . '">';
    }

}
