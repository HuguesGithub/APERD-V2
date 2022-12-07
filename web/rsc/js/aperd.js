$(document).ready(function(){
    // Pour les éléments ayant un trigger de click, on ajoute l'événement qui correspond
    $('.ajaxAction[data-trigger="click"]').on('click', function(){
        ajaxActionClick($(this));
    });
});

//////////////////////////////////////////////////////////
// L'objet cliqué
function ajaxActionClick(obj) {
    // Il peut y avoir plusieurs actions à effectuer
    let actions = obj.data('ajax').split(',');
    for (let oneAction of actions) {
        switch (oneAction) {
            // Export d'un fichier CSV
            case 'csvExport' :
                csvExport(obj);
                break;
        }
    }
}
//////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////
// 
function csvExport(obj) {
    let ids = '';
    $('input[name="post[]"]:checked').each(function(){
        ids += $(this).val()+',';
    });
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
  
function displayToast(value) {
  $('#toastPlacement').append(value);
  $('#toastPlacement .toast:last-child').delay(5000).hide(0);
}

