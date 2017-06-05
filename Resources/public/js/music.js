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
    success: function(d) {
      $('#inputTrackLyrics_' + trackId).val(d);
    }
  });
}



var MusicAlbumManager = {
  /**
   * Add new track line
   *
   * @param {MouseEvent} e
   */
  addTrack: function(e) {
    var trackNb = $('#tracks tbody tr').length / 2 + 1,
      $elt = $(this.getTrackFormElement(trackNb));

    $('#tracks tbody').append($elt);
    return $elt;
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
      this.updateId($elt, trackNb, newTrackNb, newTrackIndex);
    }
  },

  /**
   * Replace id number
   *
   * @param $elt
   * @param trackNb
   * @param newTrackNb
   * @param newTrackIndex
   */
  updateId: function($elt, trackNb, newTrackNb, newTrackIndex) {
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
    });
    $elt.attr('id', 'track_' + newTrackNb);
    this.initNextTrack(trackNb + 1);
  },

  /**
   * Init form to add track
   */
  initForm: function() {
    var self = this;

    $('#addTrack').click(function(e) {
      e.preventDefault();
      self.addTrack();
    });
    $('form').on('click', '.btn.btn-remove-track', function(e) { self.removeTrack(this, e); });
  },


  setAlbumInfo: function (d) {
    var self = this;

    $('#music_album_artist').val(d.artist);
    $('#music_album_title').val(d.title);
    $('#music_album_ean').val(d.ean);
    $('#music_album_publication_year').attr('value', d.releaseYear);
    $('#music_album_publisher').attr('value', d.publisher);
    $('#music_album_cover_url').attr('value', d.cover);
    if (d.thumbnail != '') {
      $('#thumbnail img:first').attr('src', d.thumbnail);
    }
    $('#tracks tbody').empty();
    if (d.tracks) {
      for (var i = 0; i < d.tracks.length; i++) {
        var $elt = self.addTrack(d.tracks[i]);
        $elt.find('input[id$=_title]').val(d.tracks[i].name);
        $elt.find('input[id$=duration]').val(d.tracks[i].duration);
        if (d.tracks[i].lyrics !== 'undefined') {
          $elt.find('input[id$=lyrics]').val(d.tracks[i].lyrics);
        }
      }
    }
  },

  /**
   * Get album informations
   *
   * @param {{ artist: string, title: string, ean: string }} data
   */
  getAlbumInfos: function (data, callback) {
    var self = this;

    $.ajax({
      url: searchUrl,
      data: data,
      success: function(data) {
        $('#retrieveBtn').attr('disabled', false);
        if (data instanceof Array) {
          var $html = $(tmpl('tmpl-search-result', { 'items': data }));
          $html.find('li').each(function() {
            var $this = $(this);
            var index = $this.data('index');
            $this.click(function() {
              self.getAlbumInfos(data[index], function(d) {
                if (d instanceof Array) {
                }
                else {
                  $('#myModal').modal('hide');
                }
              });
            });
          });

          $('#myModal .modal-content').append($html);
          $('#myModal').modal('show');
        }
        else {
          self.setAlbumInfo(data);
        }
        if (typeof callback === 'function') {
          callback(data);
        }
      }
    });
  },

  /**
   * Search album data from configured webservice
   *
   * @param {element} elt
   * @param {MouseEvent} event
   */
  searchAlbumData: function (elt, event) {
    event.preventDefault();
    $(elt).attr('disabled', true);
    $('#searchResult').remove();
    this.getAlbumInfos({ artist: $('#music_album_artist').val(), title: $('#music_album_title').val(), ean: $('#music_album_ean').val() });
  },

  /**
   * Add the search button to complete the form
   */
  addSearchButton: function() {
    var self = this,
      $albumTitle = $('#music_album_title'),
      $retrieveBtn = $('<button id="retrieveBtn" type="button" class="btn btn-default pull-right"><i class="glyphicon glyphicon-refresh"></i></button>');

    $albumTitle.addClass('pull-left');
    $albumTitle.after($retrieveBtn);
    $albumTitle.innerWidth($albumTitle.innerWidth() - $retrieveBtn.innerWidth() - 10);
    $('#music_album_title, #music_album_artist').on('keyup', function() {
      $retrieveBtn.attr('disabled', $(this).val().length === 0);
    });

    $retrieveBtn.click(function(e) { self.searchAlbumData(this, e); });
  }
};

$(document).ready(function() {
  if ($('#addTrack').length === 1) {
    MusicAlbumManager.initForm();
    if (typeof searchUrl !== 'undefined') {
      MusicAlbumManager.addSearchButton();
    }
  }
  // $('form').on('click', '.btn.btn-lyrics-track', getTrackLyrics);
});
tmpl.regexp = /([\s'\\])(?![^%]*%\])|(?:\[%(=|#)([\s\S]+?)%\])|(\[%)|(%\])/g;

