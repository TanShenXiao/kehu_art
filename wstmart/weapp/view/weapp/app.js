//app.js
var config = require('config.js');
var http = require('./utils/request.js');
var httpp = require('./utils/request-p.js');
App({
  /**
   * 当小程序初始化完成时，会触发 onLaunch（全局只触发一次）
   */
  onLaunch: function () {
    // 本地存储
    var that = this;
    this.getdata();
    this.getNavBarHeight();
    wx.getStorageInfo({
      success: function (res) {
        // 判断商品浏览记录是否存在，没有则创建
        if (!('history' in res.keys)) {
          wx.setStorage({
            key: 'history',
            data: []
          })
        }
      }
    })
    this.getCartNum();
  },
  // 一键登录
  oneClickLogin: function () {
    var that = this;
    wx.login({
      success: function (res) {
        if (res.code) {
          var code = res.code;
          http.Post("weapp/request/index", { code: code }, function (res) {
            if (res.status == 1) {
              var openId = res.data.openId ? res.data.openId : '';
              http.Get("weapp/index/oneClickLogin", { tokenId: '' ,openId:openId}, function (res) {
                if (res.status == 1) {
                  that.globalData.tokenId = res.data;
                  wx.setStorageSync('tokenId', res.data);
                }
              });
            }else{
              that.prompt('获取用户信息失败！');
            }
          });
        } else {
          that.prompt('获取用户登录态失败！');
        }
      }
    }); 

  },
  // 获取自定义导航栏的高度
  getNavBarHeight(){
    var that = this;
    wx.getSystemInfo({
      success: function (res) {
        //console.log("res:", res);
        let totalTopHeight = 68
        if (res.model.indexOf('iPhone X') !== -1) {
          totalTopHeight = 88
        } else if (res.model.indexOf('iPhone') !== -1) {
          totalTopHeight = 64
        }
        that.globalData.statusBarHeight = res.statusBarHeight;
        that.globalData.titleBarHeight = totalTopHeight - res.statusBarHeight;
      },
      failure() {
        that.globalData.statusBarHeight = 0;
        that.globalData.titleBarHeight = 0;
      }
    })
  },
  /*检测用户授权*/
  getSetting: function (scope, cb) {
    var that = this;
    wx.getSetting({
      success(res) {
        if (res.authSetting[scope] == true) {
          typeof cb == "function" && cb(1);
        } else {
          typeof cb == "function" && cb(-1);
        }
      }
    })
  },
  //微信用户信息
  getUserInfo: function (cb) {
    var that = this
    if (this.globalData.userInfo == '') {
      wx.login({
        success: function (res) {
          if (res.code) {
            var code = res.code;
            http.Post("weapp/request/index", { code: code }, function (res) {
              if (res.status == 1) {
                that.globalData.sessionKey = res.data.sessionKey;
                wx.setStorageSync('sessionKey', res.data.sessionKey);
                wx.getSetting({
                  success(res2) {
                    if (res2.authSetting['scope.userInfo']) {
                      wx.getUserInfo({
                        success: function (res3) {
                          that.globalData.userInfo = res3.userInfo;
                          res3['sessionKey'] = res.data.sessionKey;
                          http.Post("weapp/request/bizdata", res3, function (res4) {
                            if (res4.status == 1) {
                              that.globalData.unionKey = res4.data;
                              wx.setStorageSync('unionKey', res4.data);
                              typeof cb == "function" && cb(res3);
                            }
                          });
                        }
                      });
                    }
                  }
                })
              }
            });
          } else {
            typeof cb == "function" && cb('获取用户登录态失败！');
          }
        }
      });
    }
  },
  //检测登录
  checkLogin: function (cb) {
    var that = this;
    var sessionKey = that.globalData.sessionKey;
    var unionKey = that.globalData.unionKey;
    if (sessionKey) {
      that.triggerLogin(sessionKey, unionKey, function (res2) {
        typeof cb == "function" && cb(res2);
      })
    } else {
      that.getUserInfo(function (res) {
        if (res.userInfo) {
          var sessionKey = that.globalData.sessionKey;
          var unionKey = that.globalData.unionKey;
          if (sessionKey) {
            that.triggerLogin(sessionKey, unionKey, function (res2) {
              typeof cb == "function" && cb(res2);
            })
          }
        }
      })
    }
  },
  //自动登录
  triggerLogin: function (key, key2, cb) {
    var that = this;
    http.Post("weapp/users/handleLogin", { sessionKey: key, unionKey: key2 }, function (res) {
      if (res.status == 1) {
        that.globalData.tokenId = res.data;
        wx.setStorageSync('tokenId', res.data);
        typeof cb == "function" && cb(1);
      } else {
        typeof cb == "function" && cb(0);
      }
    });
  },
  getCartNum: function () {
    // if (this.globalData.tokenId != null){
    http.Post("weapp/carts/getCartNum", { tokenId: this.globalData.tokenId }, function (res) {
      if (res.status == 1) {
        if (res.data != 0 && res.data != undefined) {
          wx.setTabBarBadge({
            index: 2,
            text: String(res.data)
          })
        } else {
          wx.removeTabBarBadge({
            index: 2
          })
        }
      }
    })
    // }
  },

  getdata: function (cb) {
    var that = this;
    var confInfo = this.getConfInfo();
    confInfo.then(res => {
      if (res.status == 1) {
        that.globalData.confInfo = res.data;
        that.globalData.resourceDomain = res.data.resourceDomain;
        that.globalData.addons = res.data.addons;
        var tokenId = wx.getStorageSync('tokenId') || null;
        var sessionKey = wx.getStorageSync('sessionKey') || null;
        var unionKey = wx.getStorageSync('unionKey') || null;
        if(sessionKey){
          that.globalData.sessionKey = sessionKey;
        }
        if (unionKey) {
          that.globalData.unionKey = unionKey;
        }
        if (tokenId) {
          that.globalData.tokenId = tokenId;
          that.checkLogin();
        } else {
          var weappOneClickLogin = res.data.weappOneClickLogin;
          if (weappOneClickLogin == 1) {
            that.oneClickLogin();
          } else {
            that.checkLogin();
          }
        }
      }
    });
    Promise.all([confInfo]).then(res => {
      typeof cb == "function" && cb(res);
    });
  },
  /*获取屏幕信息*/
  getSize: function (cb) {
    wx.getSystemInfo({
      success: function (res) {
        typeof cb == "function" && cb(res);
      }
    })
  },
  /*处理用户头像 */
  userPhoto: function (userPhoto) {
    // 外网头像
    if (userPhoto && userPhoto.indexOf('http') != -1) {
      userPhoto = userPhoto;
    } else if (userPhoto) {
      userPhoto = this.globalData.resourceDomain + userPhoto;
    } else {
      // 使用默认头像
      userPhoto = this.globalData.domain + this.globalData.confInfo.userLogo;
    }
    return userPhoto;
  },
  /*广告跳转 */
  jumpcenter: function (url) {
    var url = url;
    if (url == '') return;
    if (url.indexOf('/pages') == 0) {
      url = url.replace('/pages/', '');
      if (url.indexOf('addons') == 0) {
        wx.navigateTo({
          url: '../../' + url,
        })
      } else {
        wx.navigateTo({
          url: '../' + url,
          fail: function () {
            wx.switchTab({
              url: '../' + url,
            })
          }
        })
      }
    } else {
      wx.navigateTo({
        url: '../webview/webview?url=' + url,
      })
    }
  },
  /*判断字符串是否在数组内 ,有则返回索引*/
  isInArray: function (arr, value) {
    for (var i = 0; i < arr.length; i++) {
      if (value === arr[i]) {
        return i;
      }
    }
    return false;
  },
  /*提示信息,icon：有效值 "success", "loading", "none"*/
  prompt: function (msg, icon = 'none') {
    wx.showToast({
      title: msg,
      icon: icon,
      duration: 2000
    })
  },
  /**
   * 当小程序发生脚本错误，或者 api 调用失败时，会触发 onError 并带上错误信息
   */
  onError: function (msg) {
    //console.log(msg)
  },
  getConfInfo: function () {
    return httpp.Get("weapp/index/confInfo", {});
  },
  getCartNumP: function () {
    return httpp.Get("weapp/carts/getCartNum", { tokenId: this.globalData.tokenId });
  },
  getApis: function () {
    return httpp.Get("addon/wstim-chats-getApis", { tokenId:this.globalData.tokenId,isWeapp: 1 });
  },
  getUserData: function () {
    return httpp.Get("addon/wstim-chats-listenerData", { tokenId: this.globalData.tokenId, isWeapp: 1 });
  },
  editTabBar: function () {
    var _curPageArr = getCurrentPages();
    var _curPage = _curPageArr[_curPageArr.length - 1];
    var _pagePath = _curPage.__route__;
    if (_pagePath.indexOf('/') != 0) {
      _pagePath = '/' + _pagePath;
    }
    var cartNum = this.getCartNumP();
    cartNum.then(res => {
      this.globalData.cartNum = res.data;
    });

    var confInfo = this.getConfInfo();
    var getApis = {};
    confInfo.then(res => {
      if (res.data.addons.Wstim && res.data.addons.Wstim == 1) {
        this.globalData.isOpenIm = true;
      } else {
        this.globalData.isOpenIm = false;
      }
      if (this.globalData.tokenId != null && this.globalData.isOpenIm == true) {
        getApis = this.getApis()
        getApis.then(res => {
          this.globalData.ws = res.data.imServer;
          this.globalData.APIS = res.data;
        });
      }
    });

    // 获取首页自定义页面底部导航栏
    var getTabbarMenu = this.getTabbarMenu();
    getTabbarMenu.then(res => {
      if (res.status == 1) {
        var tabbars = res.data.tabbars;
        var list = [];
        for (var j = 0; j < tabbars.length; j++) {
          var active = false;
          if (j == 0) {
            active = true;
          }
          if(tabbars[j]['menuFlag'] == 'cart'){
            var cartNum = res.data.cartNum;
            var tabbarItem = {
              "pagePath": tabbars[j]['link'],
              "text": tabbars[j]['text'],
              "iconPath": this.globalData.resourceDomain + tabbars[j]['icon'],
              "selectedIconPath": this.globalData.resourceDomain + tabbars[j]['selectIcon'],
              "class": "menu-item",
              "num": cartNum,
              active: active
            }
          }else{
            var tabbarItem = {
              "pagePath": tabbars[j]['link'],
              "text": tabbars[j]['text'],
              "iconPath": this.globalData.resourceDomain + tabbars[j]['icon'],
              "selectedIconPath": this.globalData.resourceDomain + tabbars[j]['selectIcon'],
              "class": "menu-item",
              active: active
            }
          }

          list.push(tabbarItem);
        }
        this.globalData.customTabBar = {
          "color": res.data.color,
          "selectedColor": res.data.selectedColor,
          "backgroundColor": res.data.backgroundColor,
          "borderStyle": res.data.borderStyle,
          "list": list
        }
      }
    });

    Promise.all([confInfo, cartNum, getApis,getTabbarMenu]).then(res => {
      var cartNum = this.globalData.cartNum;
      var unReadNum = this.globalData.unReadNum;
      if (this.globalData.customTabBar != '') {
        this.globalData.tabBar = this.globalData.customTabBar;
      } else {
        if (this.globalData.tokenId && this.globalData.isOpenIm) {
          var userData = this.getUserData();
          userData.then(res => {
            if (res.status == 1) {
              this.globalData.userData = res.data;
              this.appImInit();
            }
          });
          this.globalData.tabBar = {
            "color": "#2c2c2c",
            "selectedColor": "#df0202",
            "backgroundColor": "#ffffff",
            "borderStyle": "#cccccc",
            "list": [
              {
                "pagePath": "/pages/index/index",
                "text": "主页",
                "iconPath": "/image/footer-home.png",
                "selectedIconPath": "/image/footer-home2.png",
                "class": "menu-item",
                active: true
              },
              {
                "pagePath": "/pages/classify/classify",
                "text": "分类",
                "iconPath": "/image/footer-classify.png",
                "selectedIconPath": "/image/footer-classify2.png",
                "class": "menu-item",
                active: false
              },
              {
                "pagePath": "/addons/package/pages/wstim/chat-list/chat-list",
                "text": "消息",
                "iconPath": "/image/footer-message.png",
                "selectedIconPath": "/image/footer-message2.png",
                "class": "menu-item",
                "num": unReadNum,
                active: false
              },
              {
                "pagePath": "/pages/carts/carts",
                "text": "购物车",
                "iconPath": "/image/footer-cart.png",
                "selectedIconPath": "/image/footer-cart2.png",
                "class": "menu-item",
                "num": cartNum,
                active: false
              },
              {
                "pagePath": "/pages/users/users",
                "text": "个人中心",
                "iconPath": "/image/footer-user.png",
                "selectedIconPath": "/image/footer-user2.png",
                "class": "menu-item",
                active: false
              }
            ],
            "position": "bottom"
          }
        } else {
          this.globalData.tabBar = {
            "color": "#2c2c2c",
            "selectedColor": "#df0202",
            "backgroundColor": "#ffffff",
            "borderStyle": "#cccccc",
            "list": [
              {
                "pagePath": "/pages/index/index",
                "text": "主页",
                "iconPath": "/image/footer-home.png",
                "selectedIconPath": "/image/footer-home2.png",
                "class": "menu-item",
                active: true
              },
              {
                "pagePath": "/pages/classify/classify",
                "text": "分类",
                "iconPath": "/image/footer-classify.png",
                "selectedIconPath": "/image/footer-classify2.png",
                "class": "menu-item",
                active: false
              },
              {
                "pagePath": "/pages/carts/carts",
                "text": "购物车",
                "iconPath": "/image/footer-cart.png",
                "selectedIconPath": "/image/footer-cart2.png",
                "class": "menu-item",
                "num": cartNum,
                active: false
              },
              {
                "pagePath": "/pages/users/attension/attension",
                "text": "关注",
                "iconPath": "/image/footer-follow.png",
                "selectedIconPath": "/image/footer-follow2.png",
                "class": "menu-item",
                active: false
              },
              {
                "pagePath": "/pages/users/users",
                "text": "个人中心",
                "iconPath": "/image/footer-user.png",
                "selectedIconPath": "/image/footer-user2.png",
                "class": "menu-item",
                active: false
              }
            ],
            "position": "bottom"
          }
        }
      }

      var tabBar = this.globalData.tabBar;
      for (var i = 0; i < tabBar.list.length; i++) {
        tabBar.list[i].active = false;
        if (tabBar.list[i].pagePath == _pagePath) {
          tabBar.list[i].active = true;//根据页面地址设置当前页面状态  
        }
      }
      //console.log("app-tabBar:",tabBar);
      _curPage.setData({
        tabBar: tabBar
      });
    });
  },
  /*
   * 获取后台自定义的底部导航栏菜单
   */
  getTabbarMenu: function () {
    return httpp.Get("weapp/index/getTabbarMenu", { tokenId: this.globalData.tokenId});
  },
  appImInit() {
    var that = this;
    //console.log("this.globalData.globalWebSocket:", this.globalData.globalWebSocket);
    if (this.globalData.globalWebSocket == '' || (this.globalData.globalWebSocket!=undefined && this.globalData.globalWebSocket.readyState==3)) {
      //if (this.globalData.globalWebSocket == ''){
      // 当后台没运行websocket的时候，小程序进入了消息页面，此时后台运行websocket，小程序从消息页面切换到别的页面的时候要监听全局的websocket
      if (this.globalData.globalWebSocket.readyState == 3) {
        this.globalData.globalWebSocket.close();
      }

      var webSocket = wx.connectSocket({
        url: this.globalData.ws,
        success: function (res) {
          //console.log('global connectSocket success');
        }
      });
      this.globalData.globalWebSocket = webSocket;
    }

    var userData = this.globalData.userData;
    if (userData != undefined && userData != '') {
      wx.onSocketOpen(function (res) {
        //console.log('global socketOpen success');
        // 第一次进来先连接服务器
        var sendData = {
          uid: userData.userId,// 用户id
          userName: userData.loginName,// 用户名
          role: 'lisenter',// 角色
          shopId: userData.shopId// 所属店铺id
        };
        wx.sendSocketMessage({
          data: JSON.stringify(sendData),
          success: function (res) {
            //console.log('global-send-msg success');
          }
        })
      });

      wx.onSocketMessage(function (res) {
        //console.log("global-receive-msg:", res);
        var _data = JSON.parse(res.data);
        switch (_data.type) {
          case 'newMsg':
            that.globalData.unReadNum += 1;
            //console.log("unReadNum:", that.globalData.unReadNum);
            break;
          case 'unReadMsgNum':
            var totalNum = _data.userUnRead + _data.serviceUnRead;
            if (totalNum > 0) {
              that.globalData.unReadNum = totalNum;
            } else {
              that.globalData.unReadNum = 0;
            }
            //console.log("unReadNum:", that.globalData.unReadNum);
            break;
        }
        //that.editTabBar();
        that.updateBottomNum('im');
      });
    }
  },
  updateBottomNum: function (type) {
    var that = this;
    var _curPageArr = getCurrentPages();
    var _curPage = _curPageArr[_curPageArr.length - 1];
    var _pagePath = _curPage.__route__;
    if (_pagePath.indexOf('/') != 0) {
      _pagePath = '/' + _pagePath;
    }

    var cartNumRes = {};
    if (type == 'cart') {
      cartNumRes = this.getCartNumP();
      cartNumRes.then(res => {
        this.globalData.cartNum = res.data ? res.data : 0;
      });
    }

    Promise.all([cartNumRes]).then(res => {
      var cartNum = this.globalData.cartNum;
      var unReadNum = this.globalData.unReadNum;
      if (this.globalData.customTabBar != ''){
        this.globalData.tabBar = this.globalData.customTabBar;
      }else{
        if (this.globalData.tokenId && this.globalData.isOpenIm) {
          this.globalData.tabBar = {
            "color": "#2c2c2c",
            "selectedColor": "#df0202",
            "backgroundColor": "#ffffff",
            "borderStyle": "#cccccc",
            "list": [
              {
                "pagePath": "/pages/index/index",
                "text": "主页",
                "iconPath": "/image/footer-home.png",
                "selectedIconPath": "/image/footer-home2.png",
                "class": "menu-item",
                active: true
              },
              {
                "pagePath": "/pages/classify/classify",
                "text": "分类",
                "iconPath": "/image/footer-classify.png",
                "selectedIconPath": "/image/footer-classify2.png",
                "class": "menu-item",
                active: false
              },
              {
                "pagePath": "/addons/package/pages/wstim/chat-list/chat-list",
                "text": "消息",
                "iconPath": "/image/footer-message.png",
                "selectedIconPath": "/image/footer-message2.png",
                "class": "menu-item",
                "num": unReadNum,
                active: false
              },
              {
                "pagePath": "/pages/carts/carts",
                "text": "购物车",
                "iconPath": "/image/footer-cart.png",
                "selectedIconPath": "/image/footer-cart2.png",
                "class": "menu-item",
                "num": cartNum,
                active: false
              },
              {
                "pagePath": "/pages/users/users",
                "text": "个人中心",
                "iconPath": "/image/footer-user.png",
                "selectedIconPath": "/image/footer-user2.png",
                "class": "menu-item",
                active: false
              }
            ],
            "position": "bottom"
          }
        } else {
          this.globalData.tabBar = {
            "color": "#2c2c2c",
            "selectedColor": "#df0202",
            "backgroundColor": "#ffffff",
            "borderStyle": "#cccccc",
            "list": [
              {
                "pagePath": "/pages/index/index",
                "text": "主页",
                "iconPath": "/image/footer-home.png",
                "selectedIconPath": "/image/footer-home2.png",
                "class": "menu-item",
                active: true
              },
              {
                "pagePath": "/pages/classify/classify",
                "text": "分类",
                "iconPath": "/image/footer-classify.png",
                "selectedIconPath": "/image/footer-classify2.png",
                "class": "menu-item",
                active: false
              },
              {
                "pagePath": "/pages/carts/carts",
                "text": "购物车",
                "iconPath": "/image/footer-cart.png",
                "selectedIconPath": "/image/footer-cart2.png",
                "class": "menu-item",
                "num": cartNum,
                active: false
              },
              {
                "pagePath": "/pages/users/attension/attension",
                "text": "关注",
                "iconPath": "/image/footer-follow.png",
                "selectedIconPath": "/image/footer-follow2.png",
                "class": "menu-item",
                active: false
              },
              {
                "pagePath": "/pages/users/users",
                "text": "个人中心",
                "iconPath": "/image/footer-user.png",
                "selectedIconPath": "/image/footer-user2.png",
                "class": "menu-item",
                active: false
              }
            ],
            "position": "bottom"
          }
        }
      }

      var tabBar = this.globalData.tabBar;
      for (var i = 0; i < tabBar.list.length; i++) {
        tabBar.list[i].active = false;
        if (tabBar.list[i].pagePath == _pagePath) {
          tabBar.list[i].active = true;//根据页面地址设置当前页面状态  
        }
      }
      //console.log("app-tabBar:",tabBar);
      _curPage.setData({
        tabBar: tabBar
      });
    });
  },
  bindHook:function(hook,funcName){
      var that = this;
      var isFind = false;
      for(var i=0;i<that.globalData.WSTHook[hook].length;i++){
         if(that.globalData.WSTHook[hook][i]==funcName){
            isFind = true;
            break;
         }
      }
      if(!isFind)that.globalData.WSTHook[hook].push(funcName);
  },
  unbindHook:function(hook,funcName){
      var that = this;
      if(funcName){
        for(var i=that.globalData.WSTHook[hook].length-1; i>=0;i--){
           if(that.globalData.WSTHook[hook][i]==funcName){
              that.globalData.WSTHook[hook].splice(i,1);
           }
        }
      }
  },
  emptyHook:function(hook){
      var that = this;
      that.globalData.WSTHook[hook] = [];
  },
  globalData: {
    userInfo: [],
    sessionKey: null,
    unionKey: null,
    tokenId: null,
    domain: config.domain,
    addons: config.addons,
    resourceDomain:null,
    confInfo: null,
    WSTHook:[],
    WSTHookCallBack:[],
    imServer:null,
    ws:'',
    cartNum: 0,
    unReadNum: 0,
    isOpenIm: false,
    globalWebSocket: '',
    statusBarHeight:0,
    titleBarHeight:0,
    customTabBar:'',
    tabBar: {
      "color": "#2c2c2c",
      "selectedColor": "#df0202",
      "backgroundColor": "#ffffff",
      "borderStyle": "#cccccc",
      "list": [
        {
          "pagePath": "/pages/index/index",
          "text": "主页",
          "iconPath": "/image/footer-home.png",
          "selectedIconPath": "/image/footer-home2.png",
          "class": "menu-item",
          active: true
        },
        {
          "pagePath": "/pages/classify/classify",
          "text": "分类",
          "iconPath": "/image/footer-classify.png",
          "selectedIconPath": "/image/footer-classify2.png",
          "class": "menu-item",
          active: false
        },
        {
          "pagePath": "/pages/carts/carts",
          "text": "购物车",
          "iconPath": "/image/footer-cart.png",
          "selectedIconPath": "/image/footer-cart2.png",
          "class": "menu-item",
          active: false
        },
        {
          "pagePath": "/pages/users/attension/attension",
          "text": "关注",
          "iconPath": "/image/footer-follow.png",
          "selectedIconPath": "/image/footer-follow2.png",
          "class": "menu-item",
          active: false
        },
        {
          "pagePath": "/pages/users/users",
          "text": "个人中心",
          "iconPath": "/image/footer-user.png",
          "selectedIconPath": "/image/footer-user2.png",
          "class": "menu-item",
          active: false
        }
      ],
      "position": "bottom"
    }
  }
})
