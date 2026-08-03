
var h = setInterval(function () {
   $(".c.active").each(function () {
      var c = +$(this).data("current") || 0;
      var max = +$(this).data("max");
      var suffix = $(this).data("suffix") || "";
      if (++c <= max) {
         $(this).data("current", c).text(c.toLocaleString("ru-RU") + suffix);
      } else {
         $(this).text(max.toLocaleString("ru-RU") + suffix);
         $(this).removeClass("active");
      }
   });
   if (!$(".c.active").length) {
      clearInterval(h);
   }
}, 30);
