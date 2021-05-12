var app = getApp();
var http = require("../../../utils/request.js");
Page({
  data: {
    allStatus: true,
    domain: app.globalData.domain,
    linkman: '',
    linkPhone:'',
    applyIntention:'',
    isApply:false,
    isLoad:0,
  },
  onLoad: function (e) {
    this.setData({
      resourceDomain: app.globalData.resourceDomain,
    });
    this.getData();
  },
  onReady: function () {

  },
  getData: function () {
    var that = this;
    http.Post('weapp/shopapplys/getData', { tokenId: app.globalData.tokenId }, function (res) {
      if (res.status == 1) {
        that.setData({
          isApply: res.data.isApply,
          linkPhone: res.data.linkPhone,
          isLoad:1
        });
      }
    })
  },
  changeLinkman: function (e) {
    this.setData({
      linkman: e.detail.value
    })
  },
  changeLinkPhone: function (e) {
    this.setData({
      linkPhone: e.detail.value
    })
  },
  changeApplyIntention: function (e) {
    this.setData({
      applyIntention: e.detail.value
    })
  },

  //提交
  submit: function (e) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    var linkman = that.data.linkman;
    var linkPhone = that.data.linkPhone;
    var applyIntention = that.data.applyIntention;
    if (linkman == '') {
      app.prompt('请填写联系人', 'none');
      return false;
    }
    if (linkPhone == '') {
      app.prompt('请填写联系方式', 'none');
      return false;
    }
    if (applyIntention == '') {
      app.prompt('请填写营业范围', 'none');
      return false;
    }
    var data = { tokenId: tokenId, linkman: linkman, linkPhone: linkPhone, applyIntention: applyIntention }
    http.Post("weapp/shopapplys/add", data, function (res) {
      if (res.status == 1) {
         wx.redirectTo({url: '/pages/users/shop-applys/shop-applys'})
      } else {
         app.prompt(res.msg, '');
      }
    });
  },
})