class EasyNotesJS {

	// constructor
	constructor() {
		this.events();
	}

	// events
	events() {
		jQuery("#easynotes-wrapper").on('click', ".delete-note", this.deleteNote);
		jQuery("#easynotes-wrapper").on('click', ".edit-note", this.editNote.bind(this));
		jQuery("#easynotes-wrapper").on('click', ".update-note", this.updateNote.bind(this));
		jQuery(".submit-note").on('click', this.createNote.bind(this));
	}

	editNote(e) {
		var thisNote = jQuery(e.target).parents("li");

		if (thisNote.data("state") == "editable") {
			this.makeNoteReadOnly(thisNote);
		} else {
			this.makeNoteEditable(thisNote);
		}
	}

	makeNoteEditable(thisNote) {
		thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field");
		thisNote.find(".update-note").show();
		thisNote.data("state", "editable");
		thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i> Cancel');

		// focus and set cursor at the end of textarea (+ scroll it)
		var txtarea = thisNote.find(".note-body-field");
		var txt = txtarea.val();
		txtarea.focus().val('').val(txt);
		txtarea.scrollTop(txtarea[0].scrollHeight);
	}

	makeNoteReadOnly(thisNote) {
		thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field");
		thisNote.find(".update-note").hide();
		thisNote.data("state", "readonly");
		thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit');

		// textarea scroll to the top
		var txtarea = thisNote.find(".note-body-field");
		txtarea.scrollTop(0);
	}

	//Methods go here
	deleteNote(e) {
	    var thisNote = jQuery(e.target).parents("li");

	    jQuery.ajax({
	      beforeSend: (xhr) => {
	        xhr.setRequestHeader('X-WP-Nonce', easynotesData.nonce);
	      },
	      url: easynotesData.root_url + '/wp-json/wp/v2/easynotes/' + thisNote.data('id'),
	      type: 'DELETE',
	      success: (response) => {
	        thisNote.slideUp();
	        console.log("Success");
	        console.log(response);
	        // if (response.userNoteCount < 5) {
	        //   jQuery(".note-limit-message").removeClass("active");
	        // }
	      },
	      error: (response) => {
	        console.log("Failure");
	        console.log(response);
	      }
	    });
	}

	updateNote(e) {
	    var thisNote = jQuery(e.target).parents("li");
	    var ourUpdatedNote = {
	    	'title': thisNote.find(".note-title-field").val(),
	    	'content': thisNote.find(".note-body-field").val(),
	    }

	    jQuery.ajax({
	      beforeSend: (xhr) => {
	        xhr.setRequestHeader('X-WP-Nonce', easynotesData.nonce);
	      },
	      url: easynotesData.root_url + '/wp-json/wp/v2/easynotes/' + thisNote.data('id'),
	      type: 'POST',
	      data: ourUpdatedNote,
	      success: (response) => {
	      	this.makeNoteReadOnly(thisNote);
	        console.log("Success");
	        console.log(response);
	        // if (response.userNoteCount < 5) {
	        //   jQuery(".note-limit-message").removeClass("active");
	        // }
	      },
	      error: (response) => {
	        console.log("Failure");
	        console.log(response);
	      }
	    });
	}

	createNote(e) {
	    var ourNewNote = {
	    	'title': jQuery(".new-note-title").val(),
	    	'content': jQuery(".new-note-body").val(),
	    	'status': 'publish',
	    }

	    jQuery.ajax({
	      beforeSend: (xhr) => {
	        xhr.setRequestHeader('X-WP-Nonce', easynotesData.nonce);
	      },
	      url: easynotesData.root_url + '/wp-json/wp/v2/easynotes/',
	      type: 'POST',
	      data: ourNewNote,
	      success: (response) => {
	      	jQuery(".new-note-title, .new-note-body").val('');
	      	jQuery(`
	      		<li data-id="${response.id}">
					<input readonly class="note-title-field" value="${response.title.raw}">
					<textarea readonly class="note-body-field" >${response.content.raw}</textarea>
					<span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
	                <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
	                <span class="update-note"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>	
				</li>   
	      		`).prependTo("#easynotes-wrapper").hide().slideDown();
	        console.log("Success");
	        console.log(response);
	      },
	      error: (response) => {
	        console.log("Failure");
	        console.log(response);
	      }
	    });
	}
}

// export default EasyNotesJS;
var easynotesjs = new EasyNotesJS();