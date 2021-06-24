//index.js
var http = require('../../utils/request.js');
var util = require('../../utils/util.js');
var parse = require('../common/parse/parse.js');
var timer = null;
//获取应用实例
const app = getApp()
Page({
  /**
   * 页面的初始数据
   */
  data: {
    select: '1',
    indicatorDots: true,
    autoplay: true,
    interval: 3000,
    duration: 500,
    circular: true,
    data: [],
    domain: app.globalData.domain,
    goodsLogo: null,
    isLogin: 0,
    /*分类商品*/
    currPage: -1,
    goods: [],
    num: '',
    frame: false,
    load: 2,
    weappIndexDecoration: 0,
    weappIndexDecorationData: [],
    tabBar: [],
    weappOneClickLogin: 0,
    currentPage: 1, // 首页自定义布局商品组件切换页面
    textDesc: [],
    latitude: '',
    longitude: '',
    stime: '',
    etime: '',
    customPageId: 0,
    isLoad:1
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    var scene = options.scene;
    if (typeof (scene) != "undefined") {
      var shareUserId = decodeURIComponent(scene);
      wx.setStorageSync('shareUserId', shareUserId);
    }
    wx.showNavigationBarLoading()
    this.getWeappIndexDecorationSetting();
    this.getData();
    this.getGoods();

    var confInfo = app.getConfInfo();
    confInfo.then(res => {
      that.setData({
        weappOneClickLogin: res.data.weappOneClickLogin,
      })
    });
    Promise.all([confInfo]).then(res => {
      that.authorize();
    });
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
    wx.hideNavigationBarLoading()
  },
  // 获取是否开启首页自定义页面功能
  getWeappIndexDecorationSetting: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Get("weapp/index/getCustomPagesSetting", { tokenId: tokenId }, function (res) {
      wx.hideNavigationBarLoading() //完成停止加载
      console.log("custom-page-res:", res);
      if (res.data.pageId > 0) {
        var weappIndexDecoration = (res.data.pageId) > 0 ? 1 : 0
        that.setData({
          isLoad:0,
          customPageId: res.data.pageId,
          weappIndexDecoration: weappIndexDecoration
        });
        if(res.data.hasShop){
          /*获取经纬度*/
          wx.getLocation({
            type: 'wgs84',
            success: function (res) {
              var latitude = res.latitude;
              var longitude = res.longitude;
              that.setData({
                latitude: latitude,
                longitude: longitude
              })
              that.getWeappIndexDecorationData();
            },
            fail: function () {
              that.getWeappIndexDecorationData();
              console.log('调用失败');
            }
          })
        }else{
          that.getWeappIndexDecorationData();
        }
      } else {
        that.setData({
          weappIndexDecoration: 0
        });
      }
    });
  },
  // 获取首页自定义页面数据
  getWeappIndexDecorationData: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    var latitude = that.data.latitude;
    var longitude = that.data.longitude;
    var customPageId = that.data.customPageId;
    if (customPageId == 0) return;
    http.Get("weapp/index/getCustomPageDecorationData", { tokenId: tokenId, latitude: latitude, longitude: longitude, pageId: customPageId }, function (res) {
      wx.hideNavigationBarLoading() //完成停止加载
      console.log("res.data:", res.data);
      if (res.status == 1) {
        that.setData({
          weappIndexDecorationData: that.formatData(res.data)
        });
      }
    });
  },
  // 组装小程序首页装修数据
  formatData: function (array) {
    var k = 0;
    for (var i = 0; i < array.length; i++) {
      if (array[i]['name'] == 'page') {
        wx.setNavigationBarTitle({
          title: array[i]['title'],
        });
      } else if (array[i]['name'] == 'swiper') {
        array[i]['indicatorDots'] = this.data.indicatorDots;
        array[i]['autoplay'] = this.data.autoplay;
        array[i]['interval'] = parseInt(array[i]['interval']) * 1000;
        array[i]['duration'] = this.data.duration;
        array[i]['circular'] = this.data.circular;
        array[i]['paddingTop'] = array[i]['paddingTop'];
        array[i]['paddingBottom'] = array[i]['paddingBottom'];
        array[i]['paddingLeft'] = array[i]['paddingLeft'];
        array[i]['paddingRight'] = array[i]['paddingRight'];
        array[i]['resourceDomain'] = app.globalData.resourceDomain;
        switch (array[i]['indicatorType']) {
          case '1':
            array[i]['indicatorType'] = 'rectangle';
            break;
          case '2':
            array[i]['indicatorType'] = 'square';
            break;
          case '3':
            array[i]['indicatorType'] = 'roundness';
            break;
          default:
            break;
        }
      } else if (array[i]['name'] == 'search') {
        array[i]['placeholder'] = array[i]['attr']['text'];
        switch (array[i]['attr']['class']) {
          case '1':
            array[i]['searchStyle'] = 'square';
            break;
          case '2':
            array[i]['searchStyle'] = 'circular';
            break;
          case '3':
            array[i]['searchStyle'] = 'arc';
            break;
          default:
            break;
        }
        switch (array[i]['attr']['alignment']) {
          case '1':
            array[i]['textAlign'] = 'left';
            break;
          case '2':
            array[i]['textAlign'] = 'center';
            break;
          case '3':
            array[i]['textAlign'] = 'right';
            break;
          default:
            break;
        }
      } else if (array[i]['name'] == 'nav') {
        array[i]['resourceDomain'] = app.globalData.resourceDomain;
        var navs = array[i]['navs'];
        for (var j = 0; j < navs.length; j++) {
          switch (navs[j]['count']) {
            case '1':
              array[i]['navs'][j]['count'] = 'three';
              break;
            case '2':
              array[i]['navs'][j]['count'] = 'four';
              break;
            case '3':
              array[i]['navs'][j]['count'] = 'five';
              break;
            default:
              break;
          }
          switch (navs[j]['style']) {
            case '1':
              array[i]['navs'][j]['style'] = 'square';
              break;
            case '2':
              array[i]['navs'][j]['style'] = 'roundness';
              break;
            default:
              break;
          }
        }
      } else if (array[i]['name'] == 'goods_group') {
        array[i]['resourceDomain'] = app.globalData.resourceDomain;
        array[i]['currentPage'] = this.data.currentPage;
        array[i]['goodsGroupId'] = i;
        var backgroundColor = array[i]['backgroundColor'];
        var goods = array[i]['goods'];
        for (var j = 0; j < goods.length; j++) {
          array[i]['goods'][j]['backgroundColor'] = backgroundColor;
        }
      } else if (array[i]['name'] == 'image') {
        array[i]['resourceDomain'] = app.globalData.resourceDomain;
        var paddingTop = array[i]['paddingTop'];
        var paddingBottom = array[i]['paddingBottom'];
        var paddingLeft = array[i]['paddingLeft'];
        var paddingRight = array[i]['paddingRight'];
        array[i]['circular'] = this.data.circular;
        var images = array[i]['images'];
        for (var j = 0; j < images.length; j++) {
          array[i]['images'][j]['paddingTop'] = paddingTop;
          array[i]['images'][j]['paddingBottom'] = paddingBottom;
          array[i]['images'][j]['paddingLeft'] = paddingLeft;
          array[i]['images'][j]['paddingRight'] = paddingRight;
        }
      } else if (array[i]['name'] == 'shopwindow') {
        array[i]['resourceDomain'] = app.globalData.resourceDomain;
      } else if (array[i]['name'] == 'video') {
        array[i]['resourceDomain'] = app.globalData.resourceDomain;
      } else if (array[i]['name'] == 'coupon') {
        array[i]['resourceDomain'] = app.globalData.resourceDomain;
      } else if (array[i]['name'] == 'notice') {
        array[i]['resourceDomain'] = app.globalData.resourceDomain;
      } else if (array[i]['name'] == 'text') {
        array[i]['resourceDomain'] = app.globalData.resourceDomain;
        parse.wxParse(`textDesc[${k}]`, 'html', array[i]['text'], this, 5);
        k++;
      } else if (array[i]['name'] == 'txt') {
        switch (array[i]['alignment']) {
          case '1':
            array[i]['textAlign'] = 'left';
            break;
          case '2':
            array[i]['textAlign'] = 'center';
            break;
          case '3':
            array[i]['textAlign'] = 'right';
            break;
          default:
            break;
        }
      } else if (array[i]['name'] == 'image_text') {
        array[i]['resourceDomain'] = app.globalData.resourceDomain;
        var title = array[i]['title'];
        var desc = array[i]['desc'];
        if (array[i]['style'] == 1 || array[i]['style'] == 2) {
          if (title.length > 12) {
            title = title.substr(0, 12) + '...';
          }
          array[i]['title'] = title;
        }
        if (desc.length > 50) {
          desc = desc.substr(0, 50) + '...';
        }
        array[i]['desc'] = desc;
      } else if (array[i]['name'] == 'shop') {
        array[i]['resourceDomain'] = app.globalData.resourceDomain;
      } else if (array[i]['name'] == 'new') {
        array[i]['resourceDomain'] = app.globalData.resourceDomain;
      } else if (array[i]['name'] == 'marketing') {
        array[i]['resourceDomain'] = app.globalData.resourceDomain;
        if (array[i]['type'] == 'Seckill') {
          array[i]['hour'] = '00';
          array[i]['mini'] = '00';
          array[i]['sec'] = '00';
          this.setData({
            stime: array[i]['secStartTime'],
            etime: array[i]['secEndTime'],
          })
          this.getNowTime();
        }
      }
    }
    return array;
  },
  //信息
  getData: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Get("weapp/index/getIndexData", { tokenId: tokenId }, function (res) {
      wx.hideNavigationBarLoading() //完成停止加载
      if (res.status == 1) {
        that.title(app.globalData.confInfo.mallName);
        that.setData({
          isLoad:0,
          data: res.data,
          resourceDomain: app.globalData.resourceDomain,
          goodsLogo: app.globalData.confInfo.goodsLogo
        })
      }
    });
  },
  //标题
  title: function (e) {
    wx.setNavigationBarTitle({
      title: e
    })
  },
  //楼层商品
  getGoods: function () {
    var that = this;
    var currPage = that.data.currPage;
    if (currPage > 0) that.setData({ load: 0 })
    if (currPage < 10) {
      currPage = currPage + 1;
      http.Post("weapp/index/pageQuery", { currPage: currPage }, function (res) {
        if (res.status == 1) {
          var goods = that.data.goods;
          goods = goods.concat(res.data);
          that.setData({
            goods: goods,
            resourceDomain: app.globalData.resourceDomain,
            currPage: currPage
          })
          wx.hideLoading();
        } else {
          that.setData({ load: 1 })
        }
      });
    } else {
      that.setData({ load: 1 });
    }
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    app.editTabBar();
    var that = this;
    var length = '';//文字长度
    that.setData({
      length: length,
      white_strip: length < 400 ? 400 - length : that.data.white_strip
    });
    var tokenId = wx.getStorageSync('tokenId') || null;
    if (tokenId == null) {
      that.setData({ isLogin: 0 });
    } else {
      that.setData({ isLogin: 1 });
    }
    app.getCartNum();
  },

  getMore: function () {
    wx.navigateTo({
      url: '../news/news',
    })
  },
  toNews: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "../news/news?id=" + id + "&status=open",
    })
  },
  //消息
  getClassify: function () {
    wx.navigateTo({
      url: '../classify/classify'
    })
  },
  //登陆
  getLogin: function () {
    wx.redirectTo({
      url: '../login/login'
    })
  },
  //消息
  getMessages: function () {
    // wx.navigateTo({
    //   url: '../users/messages/messages',
    // })
    wx.navigateTo({
      url: '../../addons/package/pages/wstim/chat-list/chat-list',
    })
  },
  onReachBottom: function () {
    this.getGoods();
  },
  onPullDownRefresh: function () {
    this.getData();
    this.getWeappIndexDecorationSetting();
    app.editTabBar();
    wx.stopPullDownRefresh(); //停止下拉刷新
  },
  jumpcenter: function (e) {
    app.jumpcenter(e.currentTarget.dataset.url);
  },
  btnUrl: function (e) {
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
    if (res.from === 'button') {
      // 来自页面内转发按钮
    }
    return {
      title: app.globalData.confInfo.mallName,
      path: 'pages/index/index'
    }
  },
  //搜索
  toSearch: function () {
    wx.navigateTo({
      url: '../goods-search/goods-search'
    })
  },
  //授权
  authorize: function () {
    var that = this;
    wx.getSetting({
      success(res2) {
        if (!res2.authSetting['scope.userInfo']) {
          that.setData({
            frame: true
          })
        }
      }
    })
  },
  //关闭
  cancel: function (e) {
    this.setData({
      frame: false
    })
    if (this.data.weappOneClickLogin == 1) {
      this.getData();
      this.setData({
        isLogin: 1
      })
    }
  },
  getUser: function (e) {
    var that = this;
    if (e.detail.errMsg == 'getUserInfo:ok') {
      if (that.data.weappOneClickLogin == 1) {
        app.getUserInfo(function (res) {
          var sessionKey = app.globalData.sessionKey;
          var unionKey = app.globalData.unionKey;
          var avatarUrl = app.globalData.userInfo.avatarUrl;
          var nickName = app.globalData.userInfo.nickName;
          var gender = app.globalData.userInfo.gender;
          var tokenId = app.globalData.tokenId;
          http.Get("weapp/users/bindUserInfo", { tokenId: tokenId, sessionKey: sessionKey, unionKey: unionKey, avatarUrl: avatarUrl, nickName: nickName, gender: gender }, function (res) {
            if (res.status == 1) {
              // 绑定用户信息成功，更新用户消息数
              if (that.data.isLogin == 0) {
                that.getData();
                that.setData({
                  isLogin: 1
                })
              }
            }
          });
        })
      }
    } else {
      //取消授权
    }
  },
  // 以下首页自定义布局的组件方法
  // 商品组布局切换页面
  pageSwitch: function (e) {
    var currentPage = e.currentTarget.dataset.id;
    var goodsGroupId = e.currentTarget.dataset.gid;
    var data = this.data.weappIndexDecorationData;
    for (var i = 0; i < data.length; i++) {
      if (data[i]['name'] == 'goods_group') {
        if (data[i]['goodsGroupId'] == goodsGroupId) {
          data[i]['currentPage'] = currentPage;
        }
      }
    }
    this.setData({
      weappIndexDecorationData: data
    });
  },
  //领取优惠券
  collar: function (e) {
    var that = this;
    var couponId = e.currentTarget.dataset.couponid;
    var tokenId = app.globalData.tokenId;
    http.Post("addon/coupon-weapp-receive", { tokenId: tokenId, couponId: couponId }, function (res) {
      app.prompt(res.msg);
    });
  },
  /*跳转到店铺详情 */
  jumpShopDetail: function (e) {
    var shopId = e.currentTarget.dataset.shopid;
    if (shopId == '1') {
      wx.navigateTo({
        url: '../shop-self/shop-self',
      })
    } else {
      wx.navigateTo({
        url: '../shop-home/shop-home?shopId=' + shopId,
      })
    };
  },
  /*跳转到新闻详情 */
  getNewDetail: function (e) {
    var newsId = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '../news/news-detail?id=' + newsId,
    })
  },
  /*跳转到秒杀列表*/
  getMarketingMore: function (e) {
    wx.navigateTo({
      url: '/addons/package/pages/seckill/goods/list',
    })
  },
  /*秒杀倒计时*/
  getNowTime: function () {
    var that = this;
    http.Post('addon/seckill-goods-weGetNowTime', {}, function (res) {
      var nowTime = new Date(Date.parse(res.data.nowTime.replace(/-/g, "/")));
      var startTime = new Date(Date.parse(that.data.stime.replace(/-/g, "/")));
      var endTime = new Date(Date.parse(that.data.etime.replace(/-/g, "/")));
      timer = that.countDown(nowTime, endTime);
    });
  },
  countDown: function (nowTime, endTime) {
    var that = this;
    var opts = {
      nowTime: nowTime,
      endTime: endTime,
      callback: function (data) {
        if (data.last > 0) {
          for (var i = 0; i < that.data.weappIndexDecorationData.length; i++) {
            if (that.data.weappIndexDecorationData[i]['name'] == 'marketing') {
              that.data.weappIndexDecorationData[i]['hour'] = data.hour;
              that.data.weappIndexDecorationData[i]['mini'] = data.mini;
              that.data.weappIndexDecorationData[i]['sec'] = data.sec;
            }
          }
          that.setData({
            weappIndexDecorationData: that.data.weappIndexDecorationData
          });
        } else {
          that.onLoad();
        }
      }
    };
    return util.countDown(opts);
  }
})
