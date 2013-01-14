function getAlbumsList(e)
{
	e.preventDefault();
	e.currentTarget.disabled = true;
	$('#searchResult').empty();
	getAlbumInfo({ artist: $('#inputArtist').attr('value'), title: $('#inputTitle').attr('value') });
}
function getAlbumInfo(data)
{
	$.ajax({
		url: searchUrl,
		data: data,
		success: function(d)
		{
			if (d.error)
			{
				$("#retrieveBtn").attr('disabled', false);
				return;
			}
			else if (d instanceof Array)
			{
				if(d.length <= 0)
				{
					//@todo
					$('#searchResult').append('<p>No result found</p>');
				}
				else
				{
					var ul = $('<ul></ul>');
					for (var i = 0; i < d.length; i++)
					{
						var li = $('<li data-prototype="' + d[i].ean + '"></li>');
						if (d[i].thumbnail)
						{
							$(li).append('<img src="' + d[i].thumbnail + '" alt="" />');
						}
						$(li).append('<div><a>' + d[i].artist + ' - ' + d[i].title + '</a><br />' + d[i].publisher + '<br />' + d[i].ean + '</div>');
						$(li).find('a').click(function() {
							getAlbumInfo({ ean: $(this).parent().parent().attr('data-prototype') });
							$('#searchResult').empty();
						});
						$(ul).append($(li));
					}
					$('#searchResult').append($(ul));
				}
				return;
			}
			setAlbumInfo(d);
		}
	});
}
function setAlbumInfo(d)
{
	$('#inputArtist').attr('value', d.artist);
	$('#inputTitle').attr('value', d.title);
	$('#inputEan').attr('value', d.ean);
	$('#inputPublicationDate').attr('value', d.releaseYear);
	$('#inputPublisher').attr('value', d.publisher);
	$('#inputCoverUrl').attr('value', d.image);
	if (d.thumbnail != '')
	{
		$('#thumbnail img:first').attr('src', d.thumbnail);
	}
	$('#tracks').empty();
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
