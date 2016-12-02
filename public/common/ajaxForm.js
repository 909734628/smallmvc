/**
 * Created by myPersonalFile on 2016/11/28.
 */
(function ($) {
   $.fn.ajaxForm = function (callback) {
       var thisObj = this;
       thisObj.submit(function (e) {
           e.preventDefault();
           var data = thisObj.serialize();
           $.ajaxPostData(thisObj.attr('action'),data,callback);
       })
   };
   $.ajaxPostData = function (url,data,callback) {
       $.post(url,data,function (data) {
           if(data.target!==''){
               location.href = data.target;
           }
           if(callback!==undefined){

               callback(data);
           }
           if(data.msg!==''){
               $.showTip(data.msg);
           }
       },'JSON');
   };
   $.showTip = function (msg) {
       $('.tip').remove();
       var obj = $("<div class='tip'><div class='tip-wrap'>"+msg+"</div></div>");
       $('body').append(obj);
       $('body').one("click",function () {
           alert('qwe');
           obj.fadeOut(200,function () {
               obj.remove();
           })
       })
   }
})(jQuery);