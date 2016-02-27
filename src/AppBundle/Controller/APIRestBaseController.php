<?php

/*
 *
 * (c) Cristian Angulo <cristianangulonova@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * API to Response Object of Symfony.
 */

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

abstract class APIRestBaseController extends Controller
{
    private $data;
    private $format = 'json';
    private $groups = array();
    private $statusCode = 200;
    private $statusText = 'OK';

    protected function apiResponse($data)
    {
        $this->data = $data;

        return $this;
    }

    protected function format($format)
    {
        $this->format = $format;

        return $this;
    }

    protected function groups($groups = array())
    {
        $this->groups = $groups;

        return $this;
    }

    protected function statusCode($statusCode = 200)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    protected function statusText($statusText = 'OK')
    {
        $this->statusText = $statusText;
        return $this;
    }

    protected function serialize($data, $groups = array(), $format = 'json')
    {
        return $this->get('serializer')->serialize($data, $format, array('groups' => $this->groups));
    }


    protected function response()
    {
        $data = $this->serialize($this->data, $this->groups, $this->format);

        $response = new Response();

        $response->setContent($data);
        $response->setStatusCode($this->statusCode, $this->statusText);

        return $response;

    }

    /**
    * @return EntityManager
    */

    protected function em()
    {
        $em = $this->getDoctrine()->getManager();
        return $em;
    }

    /**
    * @return Array FormErrorMessages
    */

    protected function getErrorMessages(\Symfony\Component\Form\Form $form) {
        $errors = array();
        foreach ($form->getErrors() as $key => $error) {
            $template = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();

            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }

            $errors[$key] = $template;
        }
        if ($form->count()) {
            foreach ($form as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }
        }
        return $errors;
    }
}


?>
