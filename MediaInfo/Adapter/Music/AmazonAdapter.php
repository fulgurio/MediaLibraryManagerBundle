<?php

namespace Fulgurio\MediaLibraryManagerBundle\MediaInfo\Adapter\Music;

use Nass600\MediaInfoBundle\MediaInfo\Adapter\Music\AmazonAdapter as Adapter;

class AmazonAdapter extends Adapter
{
    /**
     * Get album infos
     *
     * @param array $parameters
     * @return StdClass
     */
    public function getAlbumInfo(array $parameters)
    {
        $parameters['format'] = 'json';
        $json = parent::getAlbumInfo($parameters);
        return $this->formatData(json_decode($json));
    }

    /**
     * Format data for a specified format
     *
     * @param StdClass $data
     * @return StdClass
     */
    private function formatData($data)
    {
        if (isset($data->error))
        {
            //@todo : use translation ?
            return (object) array('error' => $data->error, 'message' => $data->message);
        }
        if (is_array($data))
        {
            return ($this->formatAlbumsList($data));
        }
        else
        {
            return ($this->formatAlbumData($data));
        }
    }

    /**
     * Format data albums list for a specified format
     *
     * @param array $albums
     * @return StdClass
     */
    private function formatAlbumsList($albums)
    {
        $formatedData = array();
        foreach($albums as $album)
        {
            $formatedData[] = (object) array(
                'artist' => isset($album->ItemAttributes->Artist) ? trim($album->ItemAttributes->Artist) : '',
                'title' => trim($album->ItemAttributes->Title),
                'ean' => isset($album->ItemAttributes->EAN) ? $album->ItemAttributes->EAN : '',
                'releaseYear' => isset($album->ItemAttributes->ReleaseDate) ? substr($album->ItemAttributes->ReleaseDate, 0, 4) : '',
                'publisher' => isset($album->ItemAttributes->Label) ? $album->ItemAttributes->Label : '',
                'releaseDate' => isset($album->ItemAttributes->ReleaseDate) ? trim($album->ItemAttributes->ReleaseDate) : '',
                'image' => isset($album->LargeImage->URL) ? $album->LargeImage->URL : '',
                'thumbnail' => isset($album->SmallImage->URL) ? $album->SmallImage->URL : ''
            );
        }
        return ($formatedData);
    }

    /**
     * Format data album for a specified format
     *
     * @param StdClass $data
     * @return StdClass
     */
    private function formatAlbumData($data)
    {
        $formatedData = array(
            'artist' => trim($data->ItemAttributes->Artist),
            'title' => trim($data->ItemAttributes->Title),
            'ean' => $data->ItemAttributes->EAN,
            'releaseYear' => substr($data->ItemAttributes->ReleaseDate, 0, 4),
            'publisher' => $data->ItemAttributes->Label,
            'releaseDate' => trim($data->ItemAttributes->ReleaseDate),
            'image' => $data->LargeImage->URL,
            'thumbnail' => $data->SmallImage->URL,
            'tracks' => array(array())
        );
        if ($data->ItemAttributes->NumberOfDiscs == 1)
        {
            if (isset($data->Tracks))
            {
                foreach ($data->Tracks->Disc->Track as $track)
                {
                    $track = (array) $track;
                    $formatedData['tracks'][0][] = array(
                        'title' => trim($track['_']),
                        'duration' => '',
                        'lyrics' => ''
                    );
                }
            }
        }
        else if (isset($data->Tracks->Disc))
        {
            foreach ($data->Tracks->Disc as $disc)
            {
                foreach ($disc->Track as $track)
                {
                    $track = (array) $track;
                    $formatedData['tracks'][0][] = array(
                        'title' => trim($track['_']),
                        'duration' => '',
                        'lyrics' => ''
                    );
                }
            }
        }
        return ($formatedData);
    }
}