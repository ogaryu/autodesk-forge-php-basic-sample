/**
 * Created by ryuji on 2016/10/18.
 */

var AjaxFormController = function() {

    $(document).off('submit', '.ajaxform');

    $(document).on('submit', '.ajaxform', function(e){

        e.preventDefault();

        var form = $(this);
        var formData = form.serialize();
        var formActionUrl = form.attr('action');
        var formActionStr = formActionUrl.substr(1);
        var formMethod = form.attr('method');

        if(formMethod == undefined || formMethod == ''){
            formMethod = 'GET';
        }
        
        if(formData == undefined || formData == ''){
            formData = {};
        }

        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });
        
        $.ajax({
            type: formMethod,
            url : formActionUrl,
            data : formData,
            async: false,
            contentType: 'application/json',
            dataType: 'json',
            success : function(data){
                
                console.log("ajax forms response: " + data);

                if(formActionStr == 'login'){
                    var data = JSON.parse(data);
                    var authWindow = AuthenticationWindowView(data['url'], "Autodesk Login", 800, 400);
                    authWindow.onload = function() {};
                }
                else if(formActionStr == 'logout'){
                    document.location.reload(true);
                }
                else if(formActionStr == 'hub'){
                    var self = $("#hub-body").parent().find('.panel-heading');
                    $("#hub-body").html(data);
                    $('.panel .panel-heading').not(self).next().slideUp();
                    $("#hub-body").slideDown();
                    setAjaxForm();
                }
                else if(formActionStr == 'projects'){
                    $("#projects-body").html(data);
                    $('.panel .panel-heading').next().slideUp();
                    $("#projects-body").slideDown();
                    setAjaxForm();
                }
                else if(formActionStr == 'items'){
                    $("#items-body").html(data);
                    $('.panel .panel-heading').next().slideUp();
                    $("#items-body").slideDown();
                    setAjaxForm();
                    setViewer();
                }
                else if(formActionStr == 'issues'){
                    $("#issues-body").html(data);
                    $('.panel .panel-heading').next().slideUp();
                    $("#issues-body").slideDown();
                    setAjaxForm();
                }
            }
        });
    });
};

AjaxFormController();