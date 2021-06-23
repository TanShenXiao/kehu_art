var http = require('../../../../../utils/request.js');
const app = getApp();
Page({
  data: {
    data: [],
    term: 0,
    userLogo: null,
    userName: '',
    resourceDomain: app.globalData.resourceDomain,
    domain: app.globalData.domain,
    stateLogin: false,
    aframe: false,
    isShowQrcode: false,
    duser: [],
    shareInfo:[]
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      userLogo: app.globalData.confInfo.userLogo
    })
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    var that = this;
    var tokenId = wx.getStorageSync('tokenId');
    if (tokenId) {
      that.getData(tokenId);
      that.setData({
        tokenId: tokenId,
        stateLogin: true
      })
    } else {
      that.getUserInfo();
      that.setData({
        stateLogin: false
      })
    }
  },
  //获取用户信息
  getData: function (tokenId) {
    var that = this;
    http.Post('weapp/users/index', { tokenId: tokenId }, function (res) {
      if (res.status == 1) {
        that.setData({
          data: res.data
        })
      }
    });
    http.Post("addon/distribut-distribut-weGetUser", { tokenId: tokenId }, function (res) {
      if (res.status == 1) {
        that.setData({
          duser: res.data
        })
      }
    });
    http.Post("addon/distribut-distribut-weShareInfo", { tokenId: tokenId }, function (res) {
      if (res.status == 1) {
        that.setData({
          shareInfo: res.data
        })
      }
    });
  },
  //获取用户信息
  getUserInfo: function () {
    var that = this;
    var userInfo = app.globalData.userInfo;
    app.getSetting('scope.userInfo', function (scope) {
      if (scope == 1 && userInfo == '') {
        wx.getUserInfo({
          success: function (info) {
            app.globalData.userInfo = info.userInfo;
            that.setData({
              userLogo: info.userInfo.avatarUrl,
              userName: info.userInfo.nickName
            })
          }
        });
      } else {
        that.setData({
          userLogo: userInfo.avatarUrl,
          userName: userInfo.nickName
        })
      }
      if (scope == -1) {
        that.setData({
          aframe: true
        })
      }
    })
  },
  getUser: function (e) {
    var that = this;
    if (e.detail.errMsg == 'getUserInfo:ok') {
      wx.getUserInfo({
        success: function (info) {
          app.globalData.userInfo = info.userInfo;
          that.setData({
            userLogo: info.userInfo.avatarUrl,
            userName: info.userInfo.nickName
          })
        }
      });
    } else {
      //取消授权

    }
  },
  showQrcode: function (e) {
    var that = this;
    var tokenId = wx.getStorageSync('tokenId');
    var isNew = e.currentTarget.dataset.isnew;
    var pages = "pages/index/index";
    http.Post('addon/distribut-distribut-weCreatePoster', { tokenId: tokenId, isNew: isNew, pages: pages }, function (res) {
      if (res.status == 1) {
        that.setData({
          isShowQrcode: true,
          shareImg: res.data.shareImg+"?rnd="+Math.random()
        });
      }
    });
  },
  hideQrcode: function (e) {
    var that = this;
    that.setData({
      isShowQrcode: false
    });
  },
  jumpCenter: function (e) {
    var url = e.currentTarget.dataset.url;
    wx.navigateTo({
      url: url,
      fail: function () {
        wx.switchTab({
          url: url,
        })
      }
    })
  },
  //分享
  onShareAppMessage: function (res) {
    var that = this;
    console.log(that.data.shareInfo);
    return {
      title: that.data.shareInfo.title,
      imageUrl: that.data.resourceDomain + that.data.shareInfo.imgUrl,
      path: '/pages/index/index?shareUserId=' + that.data.shareInfo.shareUserId
    }
  }
})