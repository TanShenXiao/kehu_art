var http = require('../../../../utils/request.js');
var rsa = require('../../../common/rsa/rsa.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    data:[],
    smsVerfy:0,
    phoneVerfy:'',
    phoneCode:'',
    loginPwd:'',
    cologinPwd: '',
    verifyWord: '获取验证码',
    time: 0,
    isSend: false,
    phDisabled: false,
    nextDisabled: false,
    suDisabled:false,
    sessionId: null,
    step: 0,
    type:1,
    emailVerfy: '',
    emailCode: '',
    emailVerifyWord: '获取校验码',
    emDisabled: false,
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      type:options.type
    });
    wx.setNavigationBarTitle({
      title: options.type == 1?'通过手机找回密码':'通过邮箱找回密码',
    })
    this.code();
    this.getData();
    this.setData({
      sessionId: app.globalData.confInfo.sessionId,
      smsVerfy: app.globalData.confInfo.smsVerfy
    })
  },
  //数据
  getData: function () {
    var that = this;
    var sessionId = app.globalData.confInfo.sessionId;
    http.Post("weapp/users/findPassInfo", { sessionId: sessionId}, function (res) {
      if (res.status == 1) {
        that.setData({
          data: res.data,
          step: res.data.phoneType
        })
      }else{
        wx.showToast({
          title: res.msg,
          icon: 'none',
          complete: function (err) {
            wx.navigateBack({
              delta: 1
            })
          }
        });
      }
    });
  },
  //验证码
  code: function () {
    var that = this;
    var sessionId = app.globalData.confInfo.sessionId;
    that.setData({
      code: app.globalData.domain + "weapp/index/getVerify?rnd=" + Math.random() + "&sessionId=" + sessionId
    })
  },
  phoneVerfy: function (e) {
    this.setData({
      phoneVerfy: e.detail.value
    })
  },
  phoneCode: function (e) {
    this.setData({
      phoneCode: e.detail.value
    })
  },
  emailVerfy: function (e) {
    this.setData({
      emailVerfy: e.detail.value
    })
  },
  emailCode: function (e) {
    this.setData({
      emailCode: e.detail.value
    })
  },
  loginPwd: function (e) {
    this.setData({
      loginPwd: e.detail.value
    })
  },
  cologinPwd: function (e) {
    this.setData({
      cologinPwd: e.detail.value
    })
  },
  //短信验证码
  pverify: function () {
    var that = this;
    var time = that.data.time;
    var isSend = that.data.isSend;
    var phoneVerfy = that.data.phoneVerfy;
    var sessionId = that.data.sessionId;
    if (app.globalData.confInfo.smsVerfy == 1) {
      if (phoneVerfy == '') {
        app.prompt('请输入验证码');
        return false;
      }
    }
    if (isSend) return;
    that.setData({ isSend: true })
    http.Post("weapp/users/getfindPhone", {smsVerfy: phoneVerfy, sessionId: sessionId }, function (res) {
      if (res.status == 1) {
        wx.showToast({
          title: '短信已发送',
          icon: 'success'
        })
        that.code();
        time = 120;
        that.setData({ phDisabled: true, verifyWord: '120秒获取' })
        var task = setInterval(function () {
          time--;
          that.setData({ verifyWord: '' + time + "秒获取" })
          if (time == 0) {
            clearInterval(task);
            that.setData({ isSend: false, phDisabled: false, verifyWord: '重新发送' })
          }
        }, 1000);
      } else {
        app.prompt(res.msg);
        that.code();
        that.setData({ isSend: false })
      }
    });
  },
  //邮箱验证码
  everify: function () {
    var that = this;
    var time = that.data.time;
    var isSend = that.data.isSend;
    var emailVerfy = that.data.emailVerfy;
    var sessionId = that.data.sessionId;
    if (app.globalData.confInfo.smsVerfy == 1) {
      if (emailVerfy == '') {
        app.prompt('请输入验证码');
        return false;
      }
    }
    if (isSend) return;
    that.setData({ isSend: true })
    http.Post("weapp/users/getfindEmail", { smsVerfy: emailVerfy, sessionId: sessionId }, function (res) {
      if (res.status == 1) {
        wx.showToast({
          title: '邮件已发送',
          icon: 'success'
        })
        that.code();
        time = 120;
        that.setData({ phDisabled: true, emailVerifyWord: '120秒获取' })
        var task = setInterval(function () {
          time--;
          that.setData({ emailVerifyWord: '' + time + "秒获取" })
          if (time == 0) {
            clearInterval(task);
            that.setData({ isSend: false, emDisabled: false, emailVerifyWord: '重新发送' })
          }
        }, 1000);
      } else {
        app.prompt(res.msg);
        that.code();
        that.setData({ isSend: false })
      }
    });
  },
  //下一步
  verify: function () {
    var that = this;
    var url = "weapp/users/verifybackLogin";
    if(that.data.type==2){
      url = "weapp/users/verifyEmailCode";
    }
    var verifyCode = that.data.type==1?that.data.phoneCode:that.data.emailCode;
    var sessionId = that.data.sessionId;
    if (verifyCode == '' ){
      var msg = that.data.type == 1 ? '请输入短信验证码' : '请输入邮件验证码';
      app.prompt(msg);
      return false;
    }
    that.setData({ nextDisabled: true })
    wx.showLoading({ title: '验证中···' })
    var data = {verifyCode: verifyCode, sessionId: sessionId}
    http.Post(url, data, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        wx.showToast({
          title: res.msg,
          icon: 'success',
          complete: function (err) {
            that.setData({ step: 2 })
          }
        })
      } else {
        app.prompt(res.msg);
        that.setData({ nextDisabled: false })
      }
    });
  },
  //提交
  submit: function () {
    var that = this;
    var loginPwd = that.data.loginPwd;
    var cologinPwd = that.data.cologinPwd;
    var sessionId = that.data.sessionId;
    var isCryptPwd = app.globalData.confInfo.isCryptPwd;
    var public_key = app.globalData.confInfo.pwdModulusKey;
    var type = that.data.type;
    if (loginPwd == '') {
      app.prompt('新密码不能为空')
      return false;
    }
    if (cologinPwd == '') {
      app.prompt('确认密码不能为空')
      return false;
    }
    if (loginPwd.length < 6 || loginPwd.length > 16) {
      app.prompt('请输入密码为6-16位字符');
      return false;
    }
    if (cologinPwd != loginPwd) {
      app.prompt('确认密码不一致')
      return false;
    }
    if (isCryptPwd == 1) {
      var exponent = "10001";
      var rsakey = new rsa.RSAKey();
      rsakey.setPublic(public_key, exponent);
      var loginPwd = rsakey.encrypt(loginPwd);
    }
    that.setData({ suDisabled: true })
    wx.showLoading({ title: '设置中···' })
    var data = {newPass: loginPwd, sessionId: sessionId,type:type}
    http.Post("weapp/users/resetfindPass", data, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        wx.showToast({
          title: "设置成功",
          icon: 'success',
          complete: function (err) {
            wx.navigateBack({
              delta: 2
            })
          }
        })
      } else {
        app.prompt(res.msg);
        that.setData({ suDisabled: false })
      }
    });
  },
  //找回密码
  back: function () {
    wx.navigateTo({
      url: './pay-pwd/pay-pwd'
    })
  }
})