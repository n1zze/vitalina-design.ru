
var h = setInterval(function () {
   $(".c.active").each(function () {
      var c = +$(this).data("current") || 0;
      var max = +$(this).data("max");
      if (++c <= max) {
         $(this).data("current", c).text(c);
      } else $(this).removeClass("active");
   });
   if (!$(".c.active").length) {
      clearInterval(h);
      console.log("the end");
   }
}, 50);