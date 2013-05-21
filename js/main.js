var feed = {
	more: function(limit, sidebar) {
		if (sidebar === undefined) sidebar = 0;

		$.ajax({
			url: 'lib/feed_ajax.php',
			type: 'POST',
			data: 'limit=' + limit + '&sidebar=' + sidebar + '&token=' + token,
			success: function(a) {
				$('#feed').html(a);
			}
		});

		return false;
	}
};

var user = {
	statusUpdate: function() {
		status = $('#statusInput').val();
		$.ajax({
			url: '../../lib/status.php',
			type: 'POST',
			data: 'status=' + status + '&token=' + token,
			success: function(a) {
				alert(a);
			},
			
			error: function(a,b,c) {
				alert(a+b+c);
			}
		});
	}
};

function Mark(token) {
	var value = (token) ? 'checked' : '';

	for (var o = document.getElementsByTagName('input'), i = 0; i < o.length; i++) {
		if (o[i].type == 'checkbox') {
			o[i].checked = value;
		}
	}

	return false;
}

function BBCode(aTag, eTag) {
	var input = document.getElementById('postContent');
	input.focus();

	if (typeof document.selection != 'undefined') {
		var range = document.selection.createRange();
		var insText = range.text;
		range.text = aTag + insText + eTag;

		range = document.selection.createRange();

		if (insText.length == 0)
		{
			range.move('character', -eTag.length);
		}
		else
		{
			range.moveStart('character', aTag.length + insText.length + eTag.length);
		}

		range.select();
	} else if (typeof input.selectionStart != 'undefined') {
		var start = input.selectionStart;
		var end = input.selectionEnd;
		var insText = input.value.substring(start, end);
		input.value = input.value.substr(0, start) + aTag + insText + eTag + input.value.substr(end);

		var pos = start + aTag.length + (insText.length == 0 ? start + aTag.length + insText.length + eTag.length : '');
		input.selectionStart = pos;
		input.selectionEnd = pos;
	} else {
		var pos;
		var re = new RegExp('^[0-9]{0,3}$');

		while (!re.test(pos)) {
			pos = prompt('Einf&uuml;gen an Position (0..' + input.value.length + '):', '0');
		}

		if (pos > input.value.length) {
			pos = input.value.length;
		}

		var insText = prompt('Bitte gib den zu formatierenden Text ein:');
		input.value = input.value.substr(0, pos) + aTag + insText + eTag + input.value.substr(pos);
	}
}

function Spoiler(div) {
	var obj = div.parentNode.parentNode.getElementsByTagName('div')[1];
	var obj = $(div).parent().children('div');

	if (obj.is(':hidden')) {
		obj.slideDown();
		$(div).children('a').html('Ausblenden');
	} else {
		obj.slideUp();
		$(div).children('a').html('Anzeigen');
	}      
}