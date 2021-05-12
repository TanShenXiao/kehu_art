var http = require('../../../../../../utils/request.js');

var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    data:[],
    domain: app.globalData.domain,
    auctionId:'',
    payObj:0,
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
      auctionId: options.auctionId,
      payObj: options.payObj
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
    var auctionId = that.data.auctionId;
    var payObj = that.data.payObj;
    var tokenId = app.globalData.tokenId;
    http.Post("addon/auction-walletswe-getWalletsUrl", { tokenId: tokenId, auctionId: auctionId, payObj: payObj }, function (res) {
      if (res.status == 1) {
        // 获取商品信息
        http.Get('addon/auction-walletswe-wallets',{
         payObj:payObj,
         pkey:res.pkey,
         tokenId:tokenId,
        },function(payInfo){
           if (payInfo.status != 1){
             return that.goBack('获取保证金支付信息失败');
           }
           that.setData({
               data:payInfo.data.auction,
               pwdType: payInfo.data.payPwd,
               needPay:payInfo.data.needPay,
               userMoney:payInfo.data.userMoney,
               payKey:res.pkey
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
    var auctionId = that.data.auctionId;
    var payObj = that.data.payObj;
    var tokenId = app.globalData.tokenId;
    that.setData({ disabled: true, loading: true})
    http.Post("addon/auction-weixinpaysweapp-topay", { tokenId: tokenId, auctionId: auctionId, payObj: payObj}, function (res) {
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