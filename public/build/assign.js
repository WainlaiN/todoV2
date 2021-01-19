(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["assign"],{

/***/ "./assets/js/assign.js":
/*!*****************************!*\
  !*** ./assets/js/assign.js ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {$(document).ready(function () {
  var switches = $(".assign");
  switches.click(function () {
    var id = $(this).attr('data-id');
    var url = '/task/assign/' + id; // AJAX Request

    $.ajax({
      context: this,
      url: url,
      type: 'POST',
      success: function success(response) {
        console.log(response);

        if (response.success === 1) {
          location.reload();
        } else {
          alert('Problème technique.');
        }
      }
    });
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ })

},[["./assets/js/assign.js","runtime","vendors~app~assign"]]]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvYXNzaWduLmpzIl0sIm5hbWVzIjpbIiQiLCJkb2N1bWVudCIsInJlYWR5Iiwic3dpdGNoZXMiLCJjbGljayIsImlkIiwiYXR0ciIsInVybCIsImFqYXgiLCJjb250ZXh0IiwidHlwZSIsInN1Y2Nlc3MiLCJyZXNwb25zZSIsImNvbnNvbGUiLCJsb2ciLCJsb2NhdGlvbiIsInJlbG9hZCIsImFsZXJ0Il0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7QUFBQUEsMENBQUMsQ0FBQ0MsUUFBRCxDQUFELENBQVlDLEtBQVosQ0FBa0IsWUFBWTtBQUMxQixNQUFJQyxRQUFRLEdBQUdILENBQUMsQ0FBQyxTQUFELENBQWhCO0FBRUFHLFVBQVEsQ0FBQ0MsS0FBVCxDQUFlLFlBQVk7QUFFdkIsUUFBSUMsRUFBRSxHQUFHTCxDQUFDLENBQUMsSUFBRCxDQUFELENBQVFNLElBQVIsQ0FBYSxTQUFiLENBQVQ7QUFDQSxRQUFJQyxHQUFHLEdBQUcsa0JBQWtCRixFQUE1QixDQUh1QixDQUt2Qjs7QUFDQUwsS0FBQyxDQUFDUSxJQUFGLENBQU87QUFDSEMsYUFBTyxFQUFFLElBRE47QUFFSEYsU0FBRyxFQUFFQSxHQUZGO0FBR0hHLFVBQUksRUFBRSxNQUhIO0FBS0hDLGFBQU8sRUFBRSxpQkFBVUMsUUFBVixFQUFvQjtBQUN6QkMsZUFBTyxDQUFDQyxHQUFSLENBQVlGLFFBQVo7O0FBQ0EsWUFBSUEsUUFBUSxDQUFDRCxPQUFULEtBQXFCLENBQXpCLEVBQTRCO0FBRXhCSSxrQkFBUSxDQUFDQyxNQUFUO0FBRUgsU0FKRCxNQUlPO0FBQ0hDLGVBQUssQ0FBQyxxQkFBRCxDQUFMO0FBQ0g7QUFDSjtBQWRFLEtBQVA7QUFnQkgsR0F0QkQ7QUF1QkgsQ0ExQkQsRSIsImZpbGUiOiJhc3NpZ24uanMiLCJzb3VyY2VzQ29udGVudCI6WyIkKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbiAoKSB7XHJcbiAgICB2YXIgc3dpdGNoZXMgPSAkKFwiLmFzc2lnblwiKTtcclxuXHJcbiAgICBzd2l0Y2hlcy5jbGljayhmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgIHZhciBpZCA9ICQodGhpcykuYXR0cignZGF0YS1pZCcpXHJcbiAgICAgICAgdmFyIHVybCA9ICcvdGFzay9hc3NpZ24vJyArIGlkO1xyXG5cclxuICAgICAgICAvLyBBSkFYIFJlcXVlc3RcclxuICAgICAgICAkLmFqYXgoe1xyXG4gICAgICAgICAgICBjb250ZXh0OiB0aGlzLFxyXG4gICAgICAgICAgICB1cmw6IHVybCxcclxuICAgICAgICAgICAgdHlwZTogJ1BPU1QnLFxyXG5cclxuICAgICAgICAgICAgc3VjY2VzczogZnVuY3Rpb24gKHJlc3BvbnNlKSB7XHJcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhyZXNwb25zZSk7XHJcbiAgICAgICAgICAgICAgICBpZiAocmVzcG9uc2Uuc3VjY2VzcyA9PT0gMSkge1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBsb2NhdGlvbi5yZWxvYWQoKTtcclxuXHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIGFsZXJ0KCdQcm9ibMOobWUgdGVjaG5pcXVlLicpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSk7XHJcbiAgICB9KTtcclxufSk7Il0sInNvdXJjZVJvb3QiOiIifQ==