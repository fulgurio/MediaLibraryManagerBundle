<?php

namespace Fulgurio\MediaLibraryManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller
{
    public function indexAction()
    {
        return $this->render('FulgurioMediaLibraryManagerBundle:Front:index.html.twig');
    }
}
