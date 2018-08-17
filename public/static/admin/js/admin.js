$(function() {

  checkNewOrder();


  // 检测新的待发货订单
  function checkNewOrder() {
    $.ajax({
      method: 'get',
      url: '/admin/order/checkNewOrder',
      data: {_token: LA.token},
      success: function(res) {
        console.log('res: ', res);
        if (res.code === '00') {
          if (res.data.length > 0) {
            toastr.success('有待发货订单啦');
          }
        }
      },
      error: function(err) {
        // toastr.error(err.statusText);
        console.log('err: ', err);
      }
    });

    setTimeout(function() {
      checkNewOrder();
    }, 1000 * 60 * 10);
  }

});