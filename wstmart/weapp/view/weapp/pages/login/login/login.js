var http = require('../../../utils/request.js');
var rsa = require('../../common/rsa/rsa.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    loginName: '',
    loginPwd: '',
    verifyCode: '',
    loDisabled: false,
    sessionId: null,
    termData: [],
    isPhone: false,
    isPhoneVerify: false,
    verifyWord: '获取验证码',
    phDisabled: false,
    loginNamea: '',
    phoneCode: '',
    mobileCode:'',
    time: 0,
    isSend: false
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.pcode();
    this.code();
    this.getData();
    this.setData({
      sessionId: app.globalData.confInfo.sessionId
    })
    if (app.globalData.confInfo.smsVerfy == 1) {
      this.setData({
        isPhone: true,
      })
    }
    if (app.globalData.confInfo.smsOpen == 1) {
      this.setData({
        isPhoneVerify: true,
      })
    }
  },
  //验证码
  pcode: function () {
    var that = this;
    var sessionId = app.globalData.confInfo.sessionId;
    that.setData({
      pcode: app.globalData.domain + "weapp/index/getVerify?rnd=" + Math.random() + "&sessionId=" + sessionId
    })
  },
  getData:function(){
    var that = this;
    http.Post("weapp/users/getLoginType", {}, function (res) {
      if (res.status == 1) {
        var data = res.data;
        if(data.length > 0){
          var tempData = [];
          Object.keys(data).forEach(function (key) {
            if(data[key]==1){
              tempData.push({ types: 'account', title: '账号登录' });
              that.setData({
                types: 'account',
              });
            }else{
              tempData.unshift({ types: 'phone', title: '手机登录' });
              that.setData({
                types: 'phone',
              });
            }
          });
          that.setData({
            termData:tempData
          });
        }
      }
    })
  },
  selected(e) {
    var that = this;
    var types = e.currentTarget.dataset.types;
    that.setData({
      types: types,
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
  name : function(e){
    this.setData({
      loginName:e.detail.value
    })
  },
  phone: function (e) {
    this.setData({
      loginNamea: e.detail.value
    })
  },
  pwd : function(e){
    this.setData({
      loginPwd:e.detail.value
    })
  },
  verify: function (e) {
    this.setData({
      verifyCode: e.detail.value
    })
  },
  phoneverfy: function (e) {
    this.setData({
      phoneCode: e.detail.value
    })
  },
  checkCode: function (e) {
    this.setData({
      mobileCode: e.detail.value
    })
  },
  //短信验证码
  pverify: function (e) {
    var that = this;
    var time = that.data.time;
    var isSend = that.data.isSend;
    var loginNamea = that.data.loginNamea;
    var mobileCode = that.data.mobileCode;
    var phoneCode = that.data.phoneCode;
    var sessionId = that.data.sessionId;
    if (loginNamea == '') {
      app.prompt('请输入手机号');
      return false;
    }
    if (app.globalData.confInfo.smsVerfy == 1) {
      if (phoneCode == '') {
        app.prompt('请输入验证码');
        return false;
      }
    }
    if (isSend) return;
    that.setData({ isSend: true })
    http.Post("weapp/users/getphonecode2", { userPhone: loginNamea, smsVerfy: phoneCode, sessionId: sessionId }, function (res) {
      if (res.status == 1) {
        wx.showToast({
          title: '短信已发送',
          icon: 'success'
        })
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
        that.setData({ isSend: false })
      }
    });
  },
  login: function () {
    var that = this;
    var loginName = that.data.loginName;
    var loginPwd = that.data.loginPwd;
    var verifyCode = that.data.verifyCode;
    var sessionId = that.data.sessionId;
    var sessionKey = app.globalData.sessionKey;
    var unionKey = app.globalData.unionKey;
    var isCryptPwd = app.globalData.confInfo.isCryptPwd;
    var public_key = app.globalData.confInfo.pwdModulusKey;
    var avatarUrl = app.globalData.userInfo.avatarUrl;
    if (loginName ==''){
      app.prompt('请输入用户名');
      return false;
    }
    if (loginPwd == '') {
      app.prompt('请输入密码');
      return false;
    }
    if (verifyCode == '') {
      app.prompt('请输入验证码');
      return false;
    }
    if (isCryptPwd == 1) {
      var exponent = "10001";
      var rsakey = new rsa.RSAKey();
      rsakey.setPublic(public_key, exponent);
      var loginPwd = rsakey.encrypt(loginPwd);
    }
    that.setData({ loDisabled: true })
    wx.showLoading({ title: '登录中···' })
    var data = { avatarUrl:avatarUrl,loginName: loginName, loginPwd: loginPwd, verifyCode: verifyCode, sessionId: sessionId, sessionKey: sessionKey, unionKey: unionKey}
    http.Post("weapp/users/login", data, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        app.globalData.tokenId = res.data; 
        wx.setStorageSync('tokenId', res.data);
        wx.showToast({
          title: '登录成功',
          icon: 'success',
          complete: function (err) {
            wx.reLaunch({
              url: '../../users/users',
            })
          }
        })
      } else {
        app.prompt(res.msg);
        that.code();
        that.setData({ loDisabled: false })
      }
    });
  },
  login2: function () {
    var that = this;
    var loginNamea = that.data.loginNamea;
    var mobileCode = that.data.mobileCode;
    var sessionId = that.data.sessionId;
    var sessionKey = app.globalData.sessionKey;
    var unionKey = app.globalData.unionKey;
    var isCryptPwd = app.globalData.confInfo.isCryptPwd;
    var public_key = app.globalData.confInfo.pwdModulusKey;
    var avatarUrl = app.globalData.userInfo.avatarUrl;
    if (loginNamea == '') {
      app.prompt('请输入手机号');
      return false;
    }
    if (mobileCode == '') {
      app.prompt('请输入短信验证码');
      return false;
    }
    that.setData({ loDisabled: true })
    wx.showLoading({ title: '登录中···' })
    var data = { avatarUrl: avatarUrl, loginNamea: loginNamea, mobileCode: mobileCode, sessionId: sessionId, sessionKey: sessionKey, unionKey: unionKey }
    http.Post("weapp/users/loginByPhone", data, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        app.globalData.tokenId = res.data;
        wx.setStorageSync('tokenId', res.data);
        wx.showToast({
          title: '登录成功',
          icon: 'success',
          complete: function (err) {
            wx.reLaunch({
              url: '../../users/users',
            })
          }
        })
      } else {
        app.prompt(res.msg);
        that.code();
        that.setData({ loDisabled: false })
      }
    });
  },
  //忘记密码
  forget:function (){
    wx.navigateTo({
      url: '../loginpwd-forget/loginpwd-forget'
    })
  }
})