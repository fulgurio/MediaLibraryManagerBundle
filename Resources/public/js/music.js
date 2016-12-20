function getAlbumsList(e)
{
	e.preventDefault();
	e.currentTarget.disabled = true;
	$('#searchResult').empty();
	getAlbumInfo({ artist: $('#music_album_artist').val(), title: $('#music_album_title').val() });
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
	$('#music_album_artist').attr('value', d.artist);
	$('#music_album_title').attr('value', d.title);
	$('#music_album_ean').attr('value', d.ean);
	$('#music_album_publication_date').attr('value', d.releaseYear);
	$('#music_album_publisher').attr('value', d.publisher);
	$('#music_album_cover_file_url').attr('value', d.image);
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
			artist: $('#music_album_artist').val(),
			trackLabel: $('#inputTrackTitle_' + trackId).val()
		},
		success: function(d)
		{
			$('#inputTrackLyrics_' + trackId).val(d);
		}
	});
}



var MusicAlbumManager = {
    /**
	 * Add new track line
	 *
     * @param {Element} elt
     * @param {MouseEvent} e
     */
	addTrack: function(elt, e) {
        e.preventDefault();
        var trackNb = $('#tracks tbody tr').length / 2 + 1;

        $('#tracks tbody').append(this.getTrackFormElement(trackNb));
	},

    /**
     * Add a track line
     *
     * @param {Element} elt
     * @param {MouseEvent} e
     */
    removeTrack: function (elt, e) {
		var $parent = $(elt).closest('tr');
    	this.initNextTrack(parseInt($parent.attr('id').substr(6)) + 1);
    	// Removing lyrics before
        $parent.next().remove();
        $parent.remove();
	},

    /**
	 * Generate dom form of a track from the template
	 *
     * @param {number} trackNb
     * @returns string
     */
	getTrackFormElement: function (trackNb) {
		var newTrack = tmpl('tmpl-track', {'trackNb': trackNb});

		return newTrack
            .replace(/track_n/g, trackNb)
            .replace(/__name__/g, trackNb - 1);
	},

    /**
	 * Init index and track number
	 *
     * @param {number} trackNb
     */
    initNextTrack: function (trackNb) {
		var newTrackNb = trackNb - 1,
			newTrackIndex = newTrackNb - 1,
			$elt = $('#track_' + trackNb);
		if ($elt.length > 0) {
			this.replaceId($elt, trackNb, newTrackNb, newTrackIndex);
        }
	},

	replaceId: function($elt, trackNb, newTrackNb, newTrackIndex) {
        $elt.find('td:first-child').each(function() {
            this.innerHTML = newTrackNb;
        });
        $elt.find('input,label,select,textarea,button').each(function() {
            if (this.id) {
                this.id = this.id.replace(/_[0-9]+_/, '_' + newTrackIndex + '_');
            }
            if (this.name) {
                this.name = this.name.replace(/\[[0-9]+\]/, '[' + newTrackIndex + ']');
            }
            console.log(this);
        });
        $elt.attr('id', newTrackNb);
        this.initNextTrack(trackNb + 1);
	},

	initForm: function() {
		var self = this;

        $('#addTrack').click(function(e) { self.addTrack(this, e); });
        $('form').on('click', '.btn.btn-remove-track', function(e) { self.removeTrack(this, e); });
	}
};
$(document).ready(function() {
	if ($('#addTrack').length === 1) {
        MusicAlbumManager.initForm();
    }
	// $('form').on('click', '.btn.btn-lyrics-track', getTrackLyrics);
	//
    //
	// $('#music_album_title').on('keyup', function(e) {
	// 	if ($(this).val().length > 1)
	// 	{
	// 		if (!$(this).hasClass('span2'))
	// 		{
	// 			$(this).addClass('span2');
	// 			var elt = $('<button id="retrieveBtn" type="button" class="btn"><i class="icon-refresh"></i></button>');
	// 			$(elt).click(getAlbumsList);
	// 			$(this).after(elt);
	// 		}
	// 		else
	// 		{
	// 			$('#retrieveBtn').attr('disabled', false);
	// 		}
	// 	}
	// });
});
tmpl.regexp = /([\s'\\])(?![^%]*%\])|(?:\[%(=|#)([\s\S]+?)%\])|(\[%)|(%\])/g;

