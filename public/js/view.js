/**
 * Created by ryuji on 2016/10/18.
 */

function AuthenticationWindowView(url, title, w, h) {
    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    // Puts focus on the newWindow
    if (window.focus) {
        newWindow.focus();
    }

    return newWindow;
}

function OpenViewer(derivativeUrn) {

    var viewerApp;

    var documentId = 'urn:' + derivativeUrn;
    
    var options = {
        'env' : 'AutodeskProduction',
        'document' : documentId,
        'getAccessToken': Get3LeggedToken,
        'refreshToken': Get3LeggedToken,
        'language': 'en'
    };
    
    Autodesk.Viewing.Initializer(options, function onInitialized(){
        
        viewerApp = new Autodesk.A360ViewingApplication('viewer-div');
        viewerApp.registerViewer(viewerApp.k3D, Autodesk.Viewing.Private.GuiViewer3D);
        viewerApp.loadDocumentWithItemAndObject(documentId);

    });
    
    var loadDocument = function(documentId){
        // first let's get the 3 leg token (developer & user & autodesk)
        var oauth3legtoken = Get3LeggedToken();

        Autodesk.Viewing.Document.load(
            documentId,
            function (doc) { // onLoadCallback

                documentJsonData = doc;

                geometryItems = Autodesk.Viewing.Document.getSubItemsWithProperties(doc.getRootItem(), {
                    'type': 'geometry',
                }, true);

                if (geometryItems.length > 0) {
                    
                    viewer.load(doc.getViewablePath(geometryItems[0]), null, null, null, doc.acmSessionId /*session for DM*/);
                }
                // viewer.loadDocumentWithItemAndObject(documentId);
            },
            function (errorMsg) { // onErrorCallback
                // showThumbnail(documentId.substr(4, documentId.length - 1));
                console.log(errorMsg);
            }
            , {
                'oauth2AccessToken': oauth3legtoken,
                'x-ads-acm-namespace': 'WIPDM',
                'x-ads-acm-check-groups': 'true'
            }
        )
    }
}

function SetUpViewerOpenEvent() {
    
    $('#viewer-btn').on('click', function(){

        var derivativeId = $('#derivative-id').val();

        OpenViewer(derivativeId);
    });
}

