function getAlbumsList(e)
{
	e.preventDefault();
	e.currentTarget.disabled = true;
	$('#searchResult').empty();
	getAlbumInfo({ artist: $('#inputArtist').val(), title: $('#inputTitle').val() });
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
				$('#retrieveBtn').attr('disabled', false);
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
				var elt = addTrackForm(1, j + 1);
				elt.find('input#inputTrackTitle_' + (i+1) + '_' + (j+1)).attr('value', d.tracks[i][j].title);
				elt.find('input#inputTrackDuration_' + (i+1) + '_' + (j+1)).attr('value', d.tracks[i][j].duration);
				elt.find('input#inputTrackLyrics_' + (i+1) + '_' + (j+1)).attr('value', d.tracks[i][j].lyrics);
			}
		}
	}
}
function getTrackLyrics()
{
	var trackId = $(this).parent().parent().parent().attr('data-track');
	$.ajax({
		url: lyricsUrl,
		type: 'post',
		data: {
			artist: $('#inputArtist').val(),
			trackLabel: $('#inputTrackTitle_' + trackId).val()
		},
		success: function(d)
		{
			$('#inputTrackLyrics_' + trackId).val(d);
		}
	});
}
function removeTrack()
{
	initNextTrack($(this).parent().attr('data-track'));
	$(this).parent().remove();
}
function initNextTrack(newId)
{
	var n = newId.split('_');
	var i = new Number(n[1]);
	var currentTrack = n[0] + '_' + (i + 1);
	var elt = $('li[data-track=' + currentTrack + ']');
	if (elt.length > 0)
	{
		var title = elt.find('> span');
		var titleHtml = $(title).html();
		$(title).html(titleHtml.replace(i + 1, i));
		elt.find('label,input,select,textarea,button').each(function() {
			if (this.id) {
				this.id = this.id.replace(currentTrack, newId);
			}
			if (this.name) {
				this.name = this.name.replace(/\[[0-9]+\]/, '[' + (i - 1) + ']');
			}
			if ($(this).attr('for')) {
				$(this).attr('for', $(this).attr('for').replace(currentTrack, newId));
			}
		});
		elt.attr('data-track', newId);
		initNextTrack(currentTrack);
	}
}
function addTrackForm(discNb, trackNb) {
	var newTrack = tmpl('tmpl-track', {'discNb': discNb, 'trackNb': trackNb});
	newTrack = newTrack.replace(/%TRACK_NB%/g, trackNb);
	newTrack = newTrack.replace(/__name__/g, trackNb - 1);
	return $('#tracks').append(newTrack);
}
$(document).ready(function() {
	$('form').on('click', '.btn.btn-lyrics-track', getTrackLyrics);
	$('form').on('click', '.btn.btn-remove-track', removeTrack);
	$('#addTrack').click(function(e) {
		e.preventDefault();
		var discNb = 1;
		var trackNb = $('#tracks').find('li').length + 1;
		addTrackForm(discNb, trackNb);
	});
	$('#inputTitle').on('keyup', function(e) {
		if ($(this).val().length > 1)
		{
			if (!$(this).hasClass('span2'))
			{
				$(this).addClass('span2');
				var elt = $('<button id="retrieveBtn" type="button" class="btn"><i class="icon-refresh"></i></button>');
				$(elt).click(getAlbumsList);
				$(this).after(elt);
			}
			else
			{
				$('#retrieveBtn').attr('disabled', false);
			}
		}
	});
});
tmpl.regexp = /([\s'\\])(?![^%]*%\])|(?:\[%(=|#)([\s\S]+?)%\])|(\[%)|(%\])/g;