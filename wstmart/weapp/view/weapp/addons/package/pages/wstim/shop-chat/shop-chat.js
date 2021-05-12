// addons/package/pages/wstim/wstim.js
var http = require('../../../../../utils/request.js');
//获取应用实例
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    webSocket: '',
    domain: app.globalData.domain,
    scrollTop: 0,
    userData: [],
    goodsList: [],
    ordersList: [],
    chatList: [],
    message: '',
    shopLogo: '',
    currPage: 1,
    totalPage: 1,
    currOrderPage:0,
    totalOrderPage:1,
    shopId:0,
    userId: 0,
    loading: false,
    orderLoading:false,
    group: '', // 用于标识后台哪个客服接待
    offline: false,
    goodsInfo: [],
    queueTier: false, // 排队遮罩层
    tabBar: [],
    serviceId:0,
    workerName:'',
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    var userId = options.userId;
    var userName = options.userName;
    var userPhoto = options.userPhoto;
    var shopId = options.shopId;
    var serviceId = options.serviceId;
    var workerName = options.workerName;
    this.setData({
      userId:userId,
      serviceId:serviceId,
      workerName:workerName,
      shopId:shopId,
      userPhoto:userPhoto
    });
    wx.setNavigationBarTitle({
      title: userName,
    });
    // console.log("userId:",userId);
    // console.log("userName:",userName);
    // console.log("shopId:",shopId);
    // console.log("serviceId:",serviceId);
    // console.log("workerName:", workerName);
    if (this.data.userId > 0 && this.data.serviceId > 0 && this.data.shopId > 0 && this.data.workerName != ''){
      var goodsId = 0;
      this.getChatRecord(this.data.userId, goodsId);
      this.getUserInfo();
      // 进入页面表示已读
      this.setRead();
      // 加载底部导航栏
      //app.editTabBar();
    }
  },
  onReady: function(options) {

  },
  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },
  // 用户点击导航栏的后退键要关闭webSocket
  onUnload() {
    this.data.webSocket.close();
  },
  onPullDownRefresh: function() {
    wx.stopPullDownRefresh(); //停止下拉刷新
  },
  //滚动聊天区域
  scrollChange(e) {
    var scrollTop = e.detail.scrollTop;
    if (scrollTop < 20) {
      this.loadMoreHistory();
    }
  },
  getGoodsInfo(goodsId) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post('addon/wstim-chats-getBaseData', {
      tokenId: tokenId,
      goodsId: goodsId,
      isWeapp: 1
    }, function(res) {
      //console.log("baseData:", res);
      if (res.status == 1) {
        that.setData({
          goodsInfo: res.data.goods
        });
        var _data = that.data.goodsInfo;
        if (_data) {
          var chatItem = [];
          var goodsInfo = {};
          goodsInfo.goodsId = _data.goodsId;
          goodsInfo.goodsImg = _data.goodsImg;
          goodsInfo.goodsName = _data.goodsName;
          goodsInfo.shopPrice = _data.shopPrice;
          goodsInfo.goodsInfo = true;
          chatItem.push(goodsInfo);
          that.data.chatList.push.apply(that.data.chatList, chatItem);
          that.setData({
            chatList: that.data.chatList
          });
          that.bottom();
        }
      }
    });
  },
  getUserInfo: function() {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post('weapp/users/index', {
      tokenId: tokenId
    }, function(res) {
      //console.log("userInfo:",res);
      if (res.status == 1) {
        that.setData({
          userData: res.data,
        });
        that.imInit();
      }
    });
  },
  // 加载更多聊天记录
  loadMoreHistory() {
    var that = this;
    var tokenId = app.globalData.tokenId;
    var loading = this.data.loading;
    if (loading) {
      // 加载中
      return;
    }
    this.setData({
      loading: true
    });
    var currPage = this.data.currPage;
    var totalPage = this.data.totalPage;
    if (parseInt(currPage) + 1 > totalPage) {
      return;
    }
    http.Post('addon/wstim-shopchats-pagequery', {
      tokenId: tokenId,
      isWeapp: 1,
      userId: this.data.userId,
      page: parseInt(currPage) + 1,
    }, function(res) {
      //console.log("chat-res:", res);
      if (res.data.length > 0) {
        var data = res.data;
        var result = that.formatData(data);
        that.data.chatList.unshift.apply(that.data.chatList, result);
        that.setData({
          currPage: res.current_page,
          totalPage: res.last_page,
          chatList: that.data.chatList,
          loading: false
        });
      }
    });
  },
  // 聊天记录
  getChatRecord(userId,goodsId) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post('addon/wstim-shopchats-pagequery', {
      tokenId: tokenId,
      userId:userId,
      isWeapp: 1
    }, function(res) {
      //console.log("chat-res:", res);
      if (res.data.length > 0) {
        var data = res.data;
        var result = that.formatData(data);
        that.setData({
          currPage: res.current_page,
          totalPage: res.last_page,
          chatList: result
        });
        if (goodsId) {
          that.getGoodsInfo(goodsId);
        }
        that.showCurrVisit(userId);
        that.bottom();
      }
    });
  },
  formatData(data) {
    var chatRes = [];
    for (var i = 0; i < data.length; i++) {
      var message = {};
      var flag = this.isJsonString(data[i].content);
      if (flag) {
        var msg = JSON.parse(data[i].content);
        switch (msg.type) {
          case 'orders':
            message.content = msg.content;
            message.type = 'orders';
            message.orderId = msg.orderId;
            message.goodsId = 0;
            break;
          case 'goods':
            message.content = msg.content;
            message.goodsId = msg.goodsId;
            message.orderId = 0;
            message.type = 'goods';
            break;
          case 'image':
            var src = msg.content.indexOf('http') != -1 ? msg.content : this.data.domain + msg.content;
            message.content = src;
            message.goodsId = 0;
            message.orderId = 0;
            message.type = 'image';
            break;
        }
      } else {
        message.content = data[i].content;
        message.goodsId = 0;
        message.orderId = 0;
        message.type = 'msg';
      }

      message.time = data[i].createTime;
      message.from = data[i].from;
      //console.log("message:", message);
      chatRes.push(message);
    }
    return chatRes;
  },
  jumpDetail(e) {
    var goodsId = e.currentTarget.dataset.gid;
    var orderId = e.currentTarget.dataset.oid;
    if (goodsId > 0) {
      wx.navigateTo({
        url: '/pages/goods-detail/goods-detail?goodsId=' + goodsId,
      });
    }
    if (orderId > 0) {
      wx.navigateTo({
        url: '/pages/users/orders/orders-detail/orders-detail?orderId=' + orderId + '&types=2',
      });
    }
  },
  chooseImg() {
    var that = this;
    var tokenId = app.globalData.tokenId;
    wx.chooseImage({
      count: 1, // 默认9
      sizeType: ['original', 'compressed'],
      sourceType: ['album', 'camera'],
      success(res) {
        var tempFilePath = res.tempFilePaths[0]
        //console.log("tempFilePath:", tempFilePath);
        http.Upload('weapp/users/uploadPic', tempFilePath, {
          tokenId: tokenId,
          dir: 'users'
        }, function(res) {
          //console.log("send-img-res:", res);
          var content = JSON.stringify({
            type: "image",
            content: res.savePath + res.name
          });
          that.data.offline ? that.sendOfflineMsg(content) : that.sendMsg(content);
          that.bottom();
        });
      }
    })
  },
  //监听input值的改变
  bindChange(res) {
    this.setData({
      message: res.detail.value
    });
  },
  send() {
    var that = this;
    var content = this.data.message;
    if (content == '') return;
    this.data.offline ? this.sendOfflineMsg(content) : this.sendMsg(content);
    this.setData({
      message: ''
    });
    this.bottom();
  },
  sendMsg(content) {
    var webSocket = this.data.webSocket;
    webSocket.send({
      data: JSON.stringify({
        content: content,
        role: 'worker',
        type: 'say',
        to: this.data.userId
      }),
      success: res => {
        //console.log("shop-chat-发送成功");
      }
    });
  },
  sendOfflineMsg(content) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post("addon/wstim-shopchats-sendMsg", {
      tokenId: tokenId,
      isWeapp: 1,
      content: content,
      type: 'message',
      shopId:this.data.shopId,
      userId: this.data.userId
    }, function(res) {
      if (res.status == 1) {
        var data = [];
        var chatItem = {};
        chatItem.content = content;
        chatItem.from = that.data.serviceId;
        data.push(chatItem);
        var res = that.formatData(data);
        that.data.chatList.push.apply(that.data.chatList, res);
        that.setData({
          chatList: that.data.chatList
        });
        that.bottom();
      } else {
        wx.showToast({
          title: '消息发送失败，请重试',
          icon: 'none',
          duration: 2000
        })
      }
    })
  },
  closeGoodsItem(e) {
    var _data = this.data.chatList;
    var index = 0;
    for (var i = 0; i < _data.length; i++) {
      if (_data[i].goodsInfo == true) {
        index = i;
        this.data.chatList.splice(index, 1);
        this.setData({
          chatList: this.data.chatList
        });
      }
    }
    this.bottom();
  },
  imInit() {
    this.connect();
  },
  connect() {
    var that = this;
    var webSocket = '';
    //console.log("ws:", app.globalData.ws);
    if (app.globalData.ws!=''){
      var webSocket = wx.connectSocket({
        url: app.globalData.ws,
        success: function (res) {
          //console.log('shop-chat-connectSocket success');
        }
      });
      this.setData({
        webSocket: webSocket
      });
    }

    webSocket.onOpen(function(res) {
      //console.log('shop-chat-socketOpen success');
      // 第一次进来先连接服务器
      var sendData = {
        type: 'login',
        serviceId: that.data.serviceId,
        userName: that.data.workerName,
        role: 'worker',
        shopId: that.data.shopId
      };
      webSocket.send({
        data: JSON.stringify(sendData),
        success: function(res) {
          //console.log('shop-chat-send-msg success');
        }
      })
    });
    
    /*
    var webSocket = app.globalData.globalWebSocket;
    this.setData({
      webSocket: webSocket
    });
    var sendData = {
      type: 'login',
      serviceId: that.data.serviceId,
      userName: that.data.workerName,
      role: 'worker',
      shopId: that.data.shopId
    };
    webSocket.send({
      data: JSON.stringify(sendData),
      success: function (res) {
        console.log('shop-chat-send-msg success');
      }
    })
    */

    webSocket.onMessage(function(res) {
      //console.log("shop-chat-receive-msg:", res);
      var _data = JSON.parse(res.data);
      if (!!_data.group) {
        var group = _data.group;
        that.setData({
          group: group
        });
      }
      switch (_data.type) {
        case 'visit':
          //console.log("visit_data:",_data);
          var goodsInfo = {};
          goodsInfo[_data.from] = JSON.parse(_data.content);
          wx.setStorage({
            key: 'goodsInfo',
            data: goodsInfo
          })
          that.showCurrVisit(_data.from);
          break;
        case 'say':
          if (_data.role == 'worker') {
            // 接收消息(包括自己发送的)
            that.receiveMsg(_data);
            if (_data.from == that.data.userId) {
              that.setRead();
            }
          }
          break;
        case 'newMsg':
          // 不用处理，这个由全局监听websocket来处理
          // that.setRead();
          //app.editTabBar();
          break;
      }
    });

    webSocket.onError(function(res) {
      //console.log('WebSocketError!', res);
      that.setData({
        offline: true,
      });
    });

    webSocket.onClose(function(res) {
      //console.log('WebSocketClosed!');
      that.setData({
        offline: true,
      });
    });
  },
  receiveMsg(msg) {
    var data = [];
    data.push(msg);
    var result = this.formatData(data);
    this.data.chatList.push.apply(this.data.chatList, result);
    this.setData({
      chatList: this.data.chatList
    });
    this.bottom();
  },
  setRead() {
    var tokenId = app.globalData.tokenId;
    http.Post("addon/wstim-shopchats-setRead", {
      tokenId: tokenId,
      isWeapp: 1,
      from: this.data.userId
    }, function(res) {
      //console.log("setRead-res:", res);
      //app.globalData.unReadNum = 0;
    })
  },
  showCurrVisit(userId){
    var that = this;
    wx.getStorage({
      key: 'goodsInfo',
      success: function (cache) {
        //console.log("cache:",cache);
        var goodsInfo = cache.data[userId];
        var chatItem = [];
        var message = {};
        message.goodsId = goodsInfo.goodsId;
        message.goodsImg = goodsInfo.goodsImg.indexOf('http') != -1 ? goodsInfo.goodsImg : that.data.domain + goodsInfo.goodsImg;
        message.goodsName = goodsInfo.goodsName;
        message.shopPrice = goodsInfo.shopPrice;
        message.goodsInfo = true;
        chatItem.push(message);
        that.data.chatList.push.apply(that.data.chatList, chatItem);
        that.setData({
          chatList: that.data.chatList
        });
        that.bottom();
      }
    })    
  },
  //聊天消息始终显示最底端
  bottom() {
    var that = this;
    var len = this.data.chatList.length;
    this.setData({
      scrollTop: len * 100 // 100:页面每个chat-item的最大高度(大概)
    });
    // wx.createSelectorQuery().select('#chat-list').boundingClientRect(function(rect) {
    //   console.log("rect:",rect);
    //   that.setData({
    //     scrollTop: rect.bottom
    //   });
    //   // wx.pageScrollTo({
    //   //   scrollTop: rect.bottom,
    //   // })
    // }).exec();
  },
  isJsonString(str) {
    if (typeof str == 'string') {
      try {
        var obj = JSON.parse(str);
        if (typeof obj == 'object' && obj) {
          return true;
        } else {
          return false;
        }
      } catch (e) {
        //console.log('error：' + str + '```' + e);
        return false;
      }
    }
    //console.log('It is not a string!')
  }
})