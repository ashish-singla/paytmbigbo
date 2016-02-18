jQuery(document).ready(function() { 
jQuery("#app_service_location_10").change(function(){
var service=jQuery("#app_service_location_10").val();
var host=jQuery(location).attr('host'); 
//alert(host);
var data="http://"+host+"/paytmbigbo/make-an-appointment/?app_service_city="+service;
//alert(data);
window.location.href=data;
//alert(service);
});
var data = {};
jQuery("#area-datalist-id option").each(function(i,el) {  
   data[jQuery(el).data("value")] = jQuery(el).val();
});
//console.log(data, jQuery("#area-datalist-id option").val());
   jQuery("#app_service_location_2").change(function(){
        var value = jQuery('#app_service_location_2').val();
        var area=jQuery('#area-datalist-id [value="' + value + '"]').data('value');
        var location_get=window.location.href;
        var area_redirect_url=location_get+"&app_provider_location="+area;
       // alert(area_redirect_url);
        window.location.href=area_redirect_url;
    });
});
