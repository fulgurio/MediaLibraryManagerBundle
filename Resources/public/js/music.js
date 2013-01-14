function getAlbumInfos(e)
{
	e.preventDefault();
	e.currentTarget.disabled = true;
	$.ajax({
		url: searchUrl,
		data: { artist: $('#inputArtist').attr('value'), title: $('#inputTitle').attr('value') },
		success: function(d)
		{
			if (d.error)
			{

				return;
			}
			$('#inputArtist').attr('value', d.artist);
			$('#inputTitle').attr('value', d.title);
			$('#inputEan').attr('value', d.ean);
			$('#inputPublicationDate').attr('value', d.releaseYear);
			$('#inputPublisher').attr('value', d.publisher);
			$('#inputCoverUrl').attr('value', d.image);
			if (d.tracks)
			{
				for(var i = 0; i < d.tracks.length; i++)
				{
					for(var j = 0; j < d.tracks[i].length; j++)
					{
						var elt = addTagForm(1, j + 1);
						elt.find('input#inputTrackTitle_' + (i+1) + '_' + (j+1)).attr('value', d.tracks[i][j].title);
						elt.find('input#inputTrackDuration_' + (i+1) + '_' + (j+1)).attr('value', d.tracks[i][j].duration);
						elt.find('input#inputTrackLyrics_' + (i+1) + '_' + (j+1)).attr('value', d.tracks[i][j].lyrics);
					}
				}
			}
		}
	});
}
