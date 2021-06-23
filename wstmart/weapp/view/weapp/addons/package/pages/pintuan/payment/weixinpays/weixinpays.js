var http = require('../../../../../../utils/request.js');

var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    data:[],
    domain: app.globalData.domain,
    pkey:'',
    payPwd:'',
    confirmPwd:'',
    disabled:false,
    loading:false
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      resourceDomain:app.globalData.resourceDomain,
      pkey: options.pkey
    });
  },
  /**
  * 生命周期函数--监听小程序显示
  */
  onShow: function () {
    this.getData();
  },
  //数据
  getData:function(){
    var that = this;
    var pkey = that.data.pkey;
    var tokenId = app.globalData.tokenId;
    http.Post("addon/pintuan-walletswe-getWalletsUrl", { tokenId: tokenId, pkey: pkey }, function (res) {
      if (res.status == 1) {
        // 获取商品信息
        http.Get('addon/pintuan-walletswe-wallets',{
          pkey: pkey,
          tokenId:tokenId,
        },function(payInfo){
           
           that.setData({
              data: payInfo.data,
               pwdType: payInfo.data.payPwd,
               needPay:payInfo.data.needPay,
               userMoney:payInfo.data.userMoney,
             pkey:res.pkey
           });
        });
     }else{
       that.goBack(res.msg)
     }
    });
  },
  //支付
  payment:function(e){
    var that = this;
    var pkey = that.data.pkey;
    var tokenId = app.globalData.tokenId;
    that.setData({ disabled: true, loading: true})
    http.Post("addon/pintuan-weixinpaysweapp-topay", { tokenId: tokenId, pkey: pkey}, function (res) {
      console.log('res', res);
      that.setData({loading: false })
      if (res.status == 1) {
        var payargs = res.data;
        wx.requestPayment({
          timeStamp: payargs.timeStamp,
          nonceStr: payargs.nonceStr,
          package: payargs.package,
          signType: payargs.signType,
          paySign: payargs.paySign,
          success: function (res) {
            wx.showModal({
              title: '提示',
              content: '支付成功',
              showCancel: false,
              confirmText: "确定",
              success: function (res) {
                if (res.confirm) {
                  wx.navigateBack({
                    delta: 2
                  })
                }
              }
            })
          },
          fail: function (res) {
            that.setData({ disabled: false })
          }
        })
      }else{
        app.prompt(res.msg);
        that.setData({ disabled: false})
      }
    });
  }
})