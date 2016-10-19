/**
 * Created by ryuji on 2016/10/18.
 */


var ClientRequestData = {

    setData : function(data) {

        this.refreshData();

        if(typeof data == 'object'){
            data = JSON.stringify(data, null , "    ");
        }

        this.data = data;
        
        $('#client-request-body pre').text(this.data);
    },

    getData : function() {
        return this.data;
    },
    
    refreshData : function(){
        this.data = "";
        $('#client-request-body pre').text("");
    }
};

var ServerRequestData = {

    setData : function(data) {

        this.refreshData();

        if(typeof data == 'object'){
            data = JSON.stringify(data, null , "    ");
        }

        this.data = data;

        $('#server-request-body pre').text(this.data);
    },

    getData : function() {
        return this.data;
    },

    refreshData : function(){
        this.data = "";
        $('#server-request-body pre').text("");
    }
};

var ServerResponseData = {
    
    setData : function(data) {
        
        this.refreshData();

        if(typeof data == 'object'){
            data = JSON.stringify(data, null , "    ");
        }
        
        this.data = data;

        $('#server-response-body pre').text(this.data);
    },

    getData : function() {
        return this.data;
    },

    refreshData : function(){
        this.data = "";
        $('#server-response-body pre').text("");
    }
};