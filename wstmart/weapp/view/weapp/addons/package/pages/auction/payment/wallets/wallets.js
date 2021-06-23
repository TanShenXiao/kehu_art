var http = require('../../../../../../utils/request.js');
var rsa = require('../../../../../../pages/common/rsa/rsa.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    data:[],
    domain: app.globalData.domain,
    auctionId:'',
    payObj:'',
    pwdType:1,
    payPwd:'',
    confirmPwd:'',
    disabled:false
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
    let postData = {
       tokenId: tokenId, 
       auctionId: auctionId, 
       payObj: payObj 
    }
    http.Post("addon/auction-walletswe-getWalletsUrl", postData, function (res) {
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
  goBack:function(msg){
    wx.showModal({
      title: '提示',
      content: msg,
      showCancel: false,
      confirmText: "确定",
      success: function (res) {
        if (res.confirm) {
          wx.navigateBack({
            delta: 1
          })
        }
      }
    })
  },

  payPwd: function (e) {
    this.setData({
      payPwd: e.detail.value
    })
  },
  confirmPwd: function (e) {
    this.setData({
      confirmPwd: e.detail.value
    })
  },
  //支付
  payment:function(e){
    var that = this;
    var payPwd = that.data.payPwd;
    var confirmPwd = that.data.confirmPwd;
    var pwdType = that.data.pwdType;
    var auctionId = that.data.auctionId;
    var payKey = that.data.payKey;
    var isCryptPwd = app.globalData.confInfo.isCryptPwd;
    var public_key = app.globalData.confInfo.pwdModulusKey;
    var tokenId = app.globalData.tokenId;
    if (payPwd == '') {
      app.prompt('请输入支付密码');
      return false;
    }
    if (confirmPwd == '' && pwdType==0) {
      app.prompt('确认密码不能为空');
      return false;
    }
    if (payPwd != confirmPwd && pwdType == 0) {
      app.prompt('确认密码不一致');
      return false;
    }
    if (isCryptPwd==1){
      var exponent = "10001";
      var rsakey = new rsa.RSAKey();
      rsakey.setPublic(public_key, exponent);
      var confirmPwd = rsakey.encrypt(confirmPwd);
      var payPwd = rsakey.encrypt(payPwd);
    }
    that.setData({ disabled: true })
    wx.showLoading({ title: '支付中···' })
    if (pwdType == 0){
      http.Post("weapp/users/editpayPwd", { tokenId: tokenId, oldPass: confirmPwd, newPass: payPwd }, function (res) {
      });
    }
    http.Post("addon/auction-walletswe-payByWallet", { tokenId: tokenId, payPwd: payPwd, auctionId: auctionId, pkey: payKey}, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        wx.showToast({
          title: res.msg,
          icon: 'success',
          complete: function (err) {
            if(that.data.payObj=='deal'){
              // 完成竞拍
              return wx.redirectTo({url:`../../user/settlement?id=${auctionId}`});
            }
            wx.navigateBack({
              delta: 2
            })
          }
        })
      }else{
        app.prompt(res.msg);
        that.setData({ disabled: false })
      }
    });
  },
  //忘记密码
  forget:function(){
    wx.navigateTo({
      url: '../../../../../../pages/users/security/paypwd-back/paypwd-back'
      
    })
  }
})