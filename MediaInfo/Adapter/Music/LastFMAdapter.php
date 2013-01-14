<?php

namespace Fulgurio\MediaLibraryManagerBundle\MediaInfo\Adapter\Music;

use Nass600\MediaInfoBundle\MediaInfo\Adapter\Music\LastFMAdapter as Adapter;

class LastFMAdapter extends Adapter
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
        else if ($data->album->mbid === '')
        {
            //@todo : use translation ?
            return (object) array('error' => 1, 'message' => '');
        }
        $album = $data->album;
        $formatedData = array(
            'artist' => trim($album->artist),
            'title' => trim($album->name),
            'ean' => '',
            'releaseYear' => date('Y', strtotime(trim($album->releasedate))),
            'publisher' => '',
            'releaseDate' => date('Y-m-d', strtotime(trim($album->releasedate))),
            'image' => '',
            'tracks' => array(array())
        );
        $image = (array) $album->image[4]; //'mega'
        $formatedData['image'] = $image['#text'];
        $image = (array) $album->image[1]; //'mega'
        $formatedData['thumbnail'] = $image['#text'];
        if (isset($album->tracks->track))
        {
            foreach ($album->tracks->track as $track)
            {
                $formatedData['tracks'][0][] = array(
                    'title' => trim($track->name),
                    'duration' => intval($track->duration),
                    'lyrics' => ''
                );
            }
        }
        return ($formatedData);
    }
}