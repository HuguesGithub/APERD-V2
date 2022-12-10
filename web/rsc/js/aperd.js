$(document).ready(function(){
    // Pour les éléments ayant un trigger de click, on ajoute l'événement qui correspond
    $('.ajaxAction[data-trigger="click"]').on('click', function(){
        ajaxActionClick($(this));
    });
	
	// S'il y a une interface d'upload de fichier
	if ($('#draganddrophandler')) {
		addDragAndDropHandler($(this));
	}
});

//////////////////////////////////////////////////////////
// L'objet cliqué, différentes actions sont possibles
function ajaxActionClick(obj) {
    // Il peut y avoir plusieurs actions à effectuer
    let actions = obj.data('ajax').split(',');
    for (let oneAction of actions) {
        switch (oneAction) {
            // Export d'un fichier CSV
            case 'csvExport' :
                csvExport(obj);
                break;
            // Drodown d'un menu déroulant
            case 'dropdown' :
                dropdownElement(obj);
                break;
        }
    }
}
//////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////
// Modification du libellé d'un dropdown, en fonction de la valeur sélectionnée
function dropdownElement(obj) {
    let target = obj.data('target');
    let value = obj.html();
    $(target).html(value);
}
//////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////
// Export des données sélectionnées ou de toutes, selon la valeur de la dropdown box
function csvExport(obj) {
    let ids = '';
    let values = obj.next().find('button').html();
    if (values=='Tous') {
        ids = 'all';        
    } else {
        $('input[name="post[]"]:checked').each(function(){
            ids += $(this).val()+',';
        });
    }
    let data = {'action': 'dealWithAjax', 'ajaxAction': 'csvExport', 'type': obj.data('type'), 'ids': ids};

    // On a un appel ajax pour rechercher les équivalences au numéro
    $.post(
        ajaxurl,
        data,
        function(response) {
            try {
                obj = JSON.parse(response);
            } catch (e) {
                console.log("error: "+e);
                console.log(response);
            }
        }).done(function(response) {
            obj = JSON.parse(response);
            displayToast(obj.toastContent);
    /*
  }).done(function(response) {
    let a = $("<a />", {
               href: "data:text/csv," 
                     + URL.createObjectURL(new Blob([response], {
                         type:"text/csv"
                       })),
               "download":"filename.csv"
            }); 
            $("body").append(a);
            a[0].click();
    */
        });
}
//////////////////////////////////////////////////////////
  
function displayToast(value) {
  $('#toastPlacement').append(value);
  $('#toastPlacement .toast:last-child').delay(5000).hide(0);
}

function addDragAndDropHandler(obj) {
    obj.on('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).addClass('hoveringDragAndDrop');
    });
	obj.on('dragover', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });
	obj.on('drop', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).removeClass('hoveringDragAndDrop');
		let target = $('#draganddrophandler');
        let files = e.originalEvent.dataTransfer.files;
        handleFileUpload(files, target);
    });
	
    $(document).on('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });
	$(document).on('dragover', function (e) {
      e.stopPropagation();
      e.preventDefault();
      obj.removeClass('hoveringDragAndDrop');
    });
	$(document).on('drop', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });
}

 function handleFileUpload(files, obj) {
	for (let i = 0; i < files.length; i++) {
		let fd = new FormData();
        fd.append('action', 'dealWithAjax');
        fd.append('ajaxAction', 'importFile');
        fd.append('importType', $('#post-import-drag-drop input[name="importType"]').val());
        fd.append('fileToImport', files[i]);

        let status = new createStatusbar(obj);
        status.setFileNameSize(files[i].name,files[i].size);
        sendFileToServer(fd,status);
   }
}

let rowCount=0;
function createStatusbar(obj) {
	rowCount++;
    let row = ((rowCount%2==0) ? "even" : "odd");
    this.statusbar = $("<div class='statusbar "+row+"'></div>");
    this.filename = $("<div class='filename'></div>").appendTo(this.statusbar);
    this.size = $("<div class='filesize'></div>").appendTo(this.statusbar);
    this.progressBar = $("<div class='progressBar'><div></div></div>").appendTo(this.statusbar);
    this.abort = $("<div class='abort'>Abort</div>").appendTo(this.statusbar);
    obj.after(this.statusbar);

    this.setFileNameSize = function(name, size) {
		let sizeStr = "";
        let sizeKB = size/1024;
        if (parseInt(sizeKB) > 1024) {
            let sizeMB = sizeKB/1024;
            sizeStr = sizeMB.toFixed(2)+" MB";
        } else {
            sizeStr = sizeKB.toFixed(2)+" KB";
        }
        this.filename.html(name);
        this.size.html(sizeStr);
    }
    this.setProgress = function(progress) {
        let progressBarWidth = progress*this.progressBar.width()/ 100;
        this.progressBar.find('div').animate({ width: progressBarWidth }, 10).html(progress + "% ");
        if (parseInt(progress) >= 100) {
            this.abort.hide();
        }
    }
    this.setAbort = function(jqxhr) {
        let sb = this.statusbar;
        this.abort.click(function() {
            jqxhr.abort();
            sb.hide();
        });
    }
}


function sendFileToServer(formData, status) {
    let jqXHR=$.ajax({
		xhr: function() {
			let xhrobj = $.ajaxSettings.xhr();
            if (xhrobj.upload) {
                xhrobj.upload.addEventListener('progress', function(event) {
                    let percent = 0;
                    let position = event.loaded || event.position;
                    let total = event.total;
                    if (event.lengthComputable) {
                        percent = Math.ceil(position / total * 100);
                    }
                    //Set progress
                    status.setProgress(percent);
                }, false);
            }
            return xhrobj;
        },
        url: ajaxurl,
        type: "POST",
        contentType:false,
        processData: false,
        cache: false,
        data: formData,
        success: function(data){
            status.setProgress(100);

            let obj = JSON.parse(data);
            if (obj['the-list'] != '') {
				$('#the-list').html(obj['the-list']);
            }
            if (obj['alertBlock'] != '') {
				$('#alertBlock').html(obj['alertBlock']);
				$('button.close').unbind().click(function() {
					if ($(this).data('dismiss')=='alert') {
                        $(this).parent().remove();
                    }
                });
            }
        }
    });

    status.setAbort(jqXHR);
}