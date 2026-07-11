const BASE_URL = url_path('');
const JS_URL = BASE_URL + '/webroot/js/';

function loadjs(filename){
    var fileref=document.createElement('script');
    fileref.setAttribute("type","text/javascript");
    fileref.setAttribute("src", filename);
    if (typeof fileref!="undefined"){
        document.getElementsByTagName("head")[0].appendChild(fileref);
    }
}

function url_path(path){
    var url = window.location.href;
    var arr = url.split("/");
    var result = arr[0]+"//"+arr[2];
    return result + '/agrisubsidy' + path;
}
