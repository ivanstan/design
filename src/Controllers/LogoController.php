<?php

namespace App\Controllers;

use App\Services\ColorUtil;
use App\Services\Palette;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LogoController extends AbstractController
{
    public function hexagon(string $color): Response
    {
        return $this->render(
            'hexagon.svg.twig',
            [
                'primary' => $color,
                'delta1' => ColorUtil::subtract($color, Palette::DELTA[0]),
                'delta2' => ColorUtil::subtract($color, Palette::DELTA[2]),
            ]
        );
    }
}
