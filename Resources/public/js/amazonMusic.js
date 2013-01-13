var searchInterval = null;
function amazonSearch(e)
{
	if ($('#inputArtist').attr('value').length > 2 && $('#inputTitle').attr('value').length > 2)
	{
		clearInterval(searchInterval);
		searchInterval = setInterval(function() {
			clearInterval(searchInterval);
			$('#searchResult').load(amazonSearchUrl, {artist: $('#inputArtist').attr('value'), title: $('#inputTitle').attr('value')}, function() {
				$(this).find('a').click(function() {
					eval('var data = ' + $(this).parent().parent().attr('data-prototype'));
					amazonGetDataItem(data);
				});
			});
		}, 1000);
	}
}
function amazonGetDataItem(data)
{
	$('#searchResult').html();
	$.ajax({
		url: amazonSearchUrl,
		data: data,
		success: function(d) {
			$('#inputArtist').attr('value', d.ItemAttributes.Artist);
			$('#inputTitle').attr('value', d.ItemAttributes.Title);
			$('#inputEan').attr('value', d.ItemAttributes.EAN);
			$('#inputPublicationDate').attr('value', d.ItemAttributes.ReleaseDate.substr(0, 4));
			$('#inputPublisher').attr('value', d.ItemAttributes.Label);
			$('#inputCoverUrl').attr('value', d.LargeImage.URL);
			if (d.Tracks)
			{
				if (d.ItemAttributes.NumberOfDiscs == 1)
				{
					for(var i = 0; i < d.Tracks.Disc.Track.length; i++)
					{
						var elt = addTagForm(1, i + 1);
						elt.find('input#inputTrackTitle_1_' + (i+1)).attr('value', d.Tracks.Disc.Track[i]._);
					}
				}
				else
				{
					for(var i = 0; i < d.Tracks.Disc.length; i++)
					{
						for(var j = 0; j < d.Tracks.Disc[i].Track.length; j++)
						{
							var elt = addTagForm(i + 1, j + 1);
							elt.find('input#inputTrackTitle_' + (i + 1) +'_' + (j+1)).attr('value', d.Tracks.Disc[i].Track[j]._);
						}
					}
				}
			}
			$('#searchResult').html('');
		}
	});
}
jQuery(document).ready(function() {
	$('#inputArtist').on('keyup', amazonSearch);
	$('#inputTitle').on('keyup', amazonSearch);
	$('#inputEan').on('keyup', function(){ if ($(this).attr('value').length == 12) {amazonGetDataItem({ean: $(this).attr('value')}); }});
});