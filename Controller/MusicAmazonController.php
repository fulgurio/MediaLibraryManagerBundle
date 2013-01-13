<?php
namespace Fulgurio\MediaLibraryManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class MusicAmazonController extends Controller {

    public function searchAction()
    {
        if ($this->get('request')->isXmlHttpRequest() == FALSE)
        {
            throw new AccessDeniedException();
        }
        if ($this->get('request')->get('ean') != '')
        {
            return ($this->searchItemAction($this->get('request')->get('ean')));
        }
        else if ($this->get('request')->get('asin') != '')
        {
            return ($this->searchItemAction(NULL, $this->get('request')->get('asin')));
        }
        $title = trim($this->get('request')->get('title'));
        if ($title != '')
        {
            $artist = trim($this->get('request')->get('artist'));
            $manufacturer = trim($this->get('request')->get('manufacturer'));
            $params = array(
                'AWSAccessKeyId' => $this->container->getParameter('fulgurio_media_library_manager.amazon.access_key_id'),
                'AssociateTag' => $this->container->getParameter('fulgurio_media_library_manager.amazon.associate_tag'),
                'Request' => array(
                    'SearchIndex' => 'Music',
                    'ResponseGroup' => 'Images,ItemAttributes',
                    'Title' => $title
                )
            );
            if ($artist != '')
            {
                $params['Request']['Artist'] = $artist;
            }
            if ($manufacturer != '')
            {
                $params['Request']['Manufacturer'] = $manufacturer;
            }
            $res = $this->getClient('ItemSearch')->ItemSearch($params);
            if ($res->Items->Request->IsValid == 'True')
            {
                return $this->render(
                    'FulgurioMediaLibraryManagerBundle:Music:amazonSearch.html.twig',
                    array(
                        'TotalResults' => $res->Items->TotalResults,
                        'TotalPages' => $res->Items->TotalPages,
                        'items' => $res->Items
                    )
                );
            }
        }
        throw new NotFoundHttpException();
    }

    public function searchItemAction($ean = NULL, $asin = NULL)
    {
        if ($this->get('request')->isXmlHttpRequest() == FALSE || (is_null($asin) && is_null($ean)))
        {
            throw new AccessDeniedException();
        }
        $params = array(
            'AWSAccessKeyId' => $this->container->getParameter('fulgurio_media_library_manager.amazon.access_key_id'),
            'AssociateTag' => $this->container->getParameter('fulgurio_media_library_manager.amazon.associate_tag'),
            'Request' => array('ResponseGroup' => 'Images,ItemAttributes,Tracks')
        );
        if (is_null($ean))
        {
            $params['Request']['IdType'] = 'ASIN';
            $params['Request']['ItemId'] = $asin;
        }
        else
        {
            $params['Request']['SearchIndex'] = 'Music';
            $params['Request']['IdType'] = 'EAN';
            $params['Request']['ItemId'] = $ean;
        }
        $res = $this->getClient('ItemLookup')->ItemLookup($params);
        if ($res->Items->Request->IsValid == 'True' && isset($res->Items->Item))
        {
            $response = new Response(json_encode($res->Items->Item));
            $response->headers->set('Content-Type', 'application/json');
            return ($response);
        }
        throw new NotFoundHttpException();
    }

    /**
     * Get AWS client
     * @param string $action
     * @return \SoapClient
     */
    private function getClient($action)
    {
        $time = gmstrftime('%Y-%m-%dT%H:%M:%S.00Z');
        $signature = base64_encode(
            hash_hmac(
                'sha256',
                $action . $time,
                $this->container->getParameter('fulgurio_media_library_manager.amazon.secret_key'),
                TRUE
            )
        );
        $client = new \SoapClient($this->container->getParameter('fulgurio_media_library_manager.amazon.aws_url'), array('trace' => 1));
        $header_arr = array(
            new \SoapHeader($this->container->getParameter('fulgurio_media_library_manager.amazon.aws_security_url'), 'AWSAccessKeyId', $this->container->getParameter('fulgurio_media_library_manager.amazon.access_key_id')),
            new \SoapHeader($this->container->getParameter('fulgurio_media_library_manager.amazon.aws_security_url'), 'Timestamp', $time),
            new \SoapHeader($this->container->getParameter('fulgurio_media_library_manager.amazon.aws_security_url'), 'Signature', $signature)
        );
        $client->__setSoapHeaders($header_arr);
        return ($client);
    }
}