var http = require('../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    loginName:'',
    verifyCode:'',
    sessionId: null,
    step: 0,
    userData:'',
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.code();
    this.setData({
      sessionId: app.globalData.confInfo.sessionId,
    })
  },
  //验证码
  code: function () {
    var that = this;
    var sessionId = app.globalData.confInfo.sessionId;
    that.setData({
      code: app.globalData.domain + "weapp/index/getVerify?rnd=" + Math.random() + "&sessionId=" + sessionId
    })
  },
  loginName: function (e) {
    this.setData({
      loginName: e.detail.value
    })
  },
  verifyCode: function (e) {
    this.setData({
      verifyCode: e.detail.value
    })
  },
  confirm:function(e){
    var type = e.currentTarget.dataset.type;
    wx.navigateTo({
      url: '../loginpwd-forget/loginpwd-back/loginpwd-back?type='+type
    })
  },
  //提交
  submit: function (e) {
    var that = this;
    var loginName = that.data.loginName;
    var verifyCode = that.data.verifyCode;
    var sessionId = that.data.sessionId;
    if (loginName == '') {
      app.prompt('请输入用户名')
      return false;
    }
    if (verifyCode == '') {
      app.prompt('请输入验证码')
      return false;
    }
    var data = { loginName: loginName, verifyCode: verifyCode, sessionId: sessionId}
    http.Post("weapp/users/findPass", data, function (res) {
      if (res.status == 1) {
        that.setData({
          step:1,
          userData:res.data
        });
        wx.setNavigationBarTitle({
          title: '找回方式',
        });
      } else {
        app.prompt(res.msg);
        that.code();
      }
    });
  }
})