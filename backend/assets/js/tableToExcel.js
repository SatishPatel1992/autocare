var tableToExcel = (function() {
    var uri = 'data:application/vnd.ms-excel;base64,'
            , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
            , base64 = function(s) {
                return window.btoa(unescape(encodeURIComponent(s)))
            }
    , format = function(s, c) {
        return s.replace(/{(\w+)}/g, function(m, p) {
            //alert(c[p]);
            return c[p];
        })
    }
   
   return function(table, name,filename) {
       if(table.indexOf('undefined') != -1) {
           table = table.replace('undefined','');
        } 
        var ctx = {worksheet: name || 'Worksheet', table: table};
        var dataURI = uri + base64(format(template, ctx));
        download(dataURI,filename);
    }
})()
function dataURItoBlob(dataURI) {
  var byteString = atob(dataURI.split(',')[1]);
  var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0]
  var ab = new ArrayBuffer(byteString.length);
  var ia = new Uint8Array(ab);
  for (var i = 0; i < byteString.length; i++) {
      ia[i] = byteString.charCodeAt(i);
  }
  return new Blob([ab], {type: mimeString});
}
function download(dataURI,filename) {
    var blob = dataURItoBlob(dataURI);
    var url  = window.URL.createObjectURL(blob);
    //window.location.assign(url);
    document.getElementById("dlink").href = url;
    document.getElementById("dlink").download = filename;
    document.getElementById("dlink").click();
}

