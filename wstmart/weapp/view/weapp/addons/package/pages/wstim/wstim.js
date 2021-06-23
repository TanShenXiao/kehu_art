// addons/package/pages/wstim/wstim.js
var http = require('../../../../utils/request.js');
//获取应用实例
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    // 当前评分
    score:4,
    // 分数对应文字
    _star_text:{ 1:'非常不满意', 2:'不满意', 3:'一般', 4:'满意', 5:'非常满意' },
    imImgArr:[],
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
    navBarPaddingTop: wx.getSystemInfoSync().statusBarHeight,
    statusBarHeight: app.globalData.statusBarHeight,
    titleBarHeight: app.globalData.titleBarHeight
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    var shopId = options.shopId;
    var shopName = options.shopName;
    var shopImg = options.shopImg.indexOf('http') != -1 ? options.shopImg : this.data.domain + options.shopImg;
    var goodsId = options.goodsId;
    // console.log("shopId:",shopId);
    // console.log("shopName:",shopName);
    // console.log("shopImg:", shopImg);
    // console.log("goodsId:",goodsId);
    // wx.setNavigationBarTitle({
    //   title: shopName,
    // })
    this.setData({
      shopId:shopId,
      shopLogo:shopImg,
      shopName:shopName
    });
    if(this.data.shopId > 0){
      var goodsId = options.goodsId;
      this.getChatRecord(this.data.shopId, goodsId);
      //this.getUserInfo();
      // 进入页面表示已读
      this.setRead();
      // 加载底部导航栏
      //app.editTabBar();
    }
  },
  onReady: function(options) {

  },
  // 用户点击导航栏的后退键要关闭webSocket
  onUnload() {
    this.data.webSocket.close();
  },
  onPullDownRefresh: function() {
    wx.stopPullDownRefresh(); //停止下拉刷新
  },
  goBack(){
    wx.navigateBack({
      delta:1
    });
  },
  goShop(e){
    var shopId = e.currentTarget.dataset.shopid;
    if(shopId == 1){
      wx.navigateTo({
        url: '/pages/shop-self/shop-self'
      });
    }else{
      wx.navigateTo({
        url: '/pages/shop-home/shop-home?shopId=' + shopId
      });
    }

  },
  //滚动聊天区域
  scrollChange(e) {
    var scrollTop = e.detail.scrollTop;
    if (scrollTop < 20) {
      this.loadMoreHistory();
    }
  },
  getGoodsInfo(shopId,goodsId) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post('addon/wstim-chats-getBaseData', {
      tokenId: tokenId,
      goodsId: goodsId,
      shopId:shopId,
      isWeapp: 1
    }, function(res) {
      console.log("baseData:", res);
      if (res.status == 1) {
        that.setData({
          goodsInfo: res.data.goods
        });
        var _data = that.data.goodsInfo;
        if (_data) {
          var chatItem = [];
          var goodsInfo = {};
          goodsInfo.goodsId = _data.goodsId;
          goodsInfo.goodsImg = _data.goodsImg.indexOf('http') != -1 ? _data.goodsImg : that.data.domain + _data.goodsImg;
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
          userId: res.data.userId,
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
    http.Post('addon/wstim-chats-pagequery', {
      tokenId: tokenId,
      receiveId: this.data.shopId,
      isWeapp: 1,
      page: parseInt(currPage) + 1,
    }, function(res) {
      //console.log("chat-res:", res);
      if (res.data.length > 0) {
        var data = res.data;
        // 每次请求数据之后都清空图片数组
        let _oldArr = [...that.data.imImgArr];
        that.data.imImgArr = [];
        var result = that.formatData(data);
        that.data.imImgArr = [...that.data.imImgArr, ..._oldArr];
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
  getChatRecord(shopId,goodsId) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post('addon/wstim-chats-pagequery', {
      tokenId: tokenId,
      receiveId:shopId,
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
          that.getGoodsInfo(shopId,goodsId);
        }
        that.bottom();
      }
      // 获取用户信息，并连接websocket
      that.getUserInfo();
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
            this.data.imImgArr.push(src);
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
  imViewImg({ currentTarget: { dataset: { src:current } } }){
    const urls = this.data.imImgArr;
    wx.previewImage({
      current,
      urls
    })
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
        url: '/pages/users/orders/orders-detail/orders-detail?orderId=' + orderId,
      });
    }
  },
  openScreenTier: function(e) {
    var action = e.currentTarget.dataset.action;
    var screenType = e.currentTarget.dataset.type;
    this.parameterPopup(screenType, action);
  },
  parameterPopup: function(screenType, action) {
    /* 动画部分 */
    var that = this;
    var animation = wx.createAnimation({
      duration: 300, //动画时长  
      timingFunction: "linear", //线性  
      delay: 0, //0则不延迟  
      transformOrigin: "100% 50% 0"
    });
    animation.translateY(300).step();
    this.setData({
      parameterData: animation.export()
    })
    setTimeout(function() {
      animation.translateX(0).step();
      this.setData({
        parameterData: animation.export()
      })
      //关闭抽屉  
      if (action == "close") {
        if (screenType == "goods") {
          that.setData({
            recentTier: false,
            showRecentStatus: false,
          });
        } else if(screenType == "orders") {
          that.setData({
            orderTier: false,
            showOrderStatus: false,
          });
        }else if(screenType == "eval"){
          that.setData({
            evalTier: false,
            showEvalStatus: false,
          });
        }
      }
    }.bind(this), 500)
    // 显示抽屉  
    if (action == "open") {
      if (screenType == "goods") {
        that.getHistory();
        that.setData({
          recentTier: true,
          showRecentStatus: true,
        });
      } else if(screenType == "orders")  {
        that.getOrderList();
        that.setData({
          orderTier: true,
          showOrderStatus: true,
        });
      }else if(screenType == "eval"){
        that.setData({
          evalTier: true,
          showEvalStatus: true,
        });
      }
    }
  },
  getHistory() {
    var history = [];
    var goodsIds = '';
    var tokenId = app.globalData.tokenId;
    var that = this;
    wx.showNavigationBarLoading();
    wx.getStorage({
      key: 'history',
      success: function(cache) {
        history = cache.data
        //console.log("history:", history);
        if (history.length > 0) { // 判断是否为空
          for (var i = 0; i < history.length; i++) {
            goodsIds += history[i].goodsId + ',';
          }
          //console.log("goodsIds:", goodsIds);
          wx.showNavigationBarLoading();
          http.Post("addon/wstim-chats-getHistory", {
            tokenId: tokenId,
            goodsIds: goodsIds,
            isWeapp: 1,
            shopId:that.data.shopId,
          }, function(res) {
            wx.hideNavigationBarLoading();
            if (res.status == 1) {
              //console.log(res);
              that.setData({
                goodsList: res.data
              });
            }
          })
        } else {
          wx.hideNavigationBarLoading();
        }
      }
    });

  },
  getOrderList() {
    var tokenId = app.globalData.tokenId;
    var that = this;
    var currPage = this.data.currOrderPage;
    var totalPage = this.data.totalOrderPage;
    if (parseInt(currPage) + 1 > totalPage) {
      return;
    }
    wx.showNavigationBarLoading();
    http.Post("addon/wstim-chats-getOrderList", {
      tokenId: tokenId,
      shopId:this.data.shopId,
      isWeapp: 1,
      page: parseInt(currPage) + 1,
    }, function(res) {
      wx.hideNavigationBarLoading();
      //console.log("order:", res);
      if (res.data.length > 0) {
        that.setData({
          ordersList: res.data,
          currOrderPage: res.current_page,
          totalOrderPage: res.last_page,
        });
      }
    })
  },
  // 加载更多订单记录
  loadMoreOrderList() {
    var that = this;
    var tokenId = app.globalData.tokenId;
    var orderLoading = this.data.orderLoading;
    if (orderLoading) {
      // 加载中
      return;
    }
    wx.showNavigationBarLoading();
    this.setData({
      orderLoading: true
    });
    var currPage = this.data.currOrderPage;
    var totalPage = this.data.totalOrderPage;
    if (parseInt(currPage) + 1 > totalPage) {
      wx.hideNavigationBarLoading();
      return;
    }
    http.Post('addon/wstim-chats-getOrderList', {
      tokenId: tokenId,
      isWeapp: 1,
      page: parseInt(currPage) + 1,
    }, function (res) {
      if (res.Rows.length > 0) {
        wx.hideNavigationBarLoading();
        that.data.ordersList.push.apply(that.data.ordersList, res.Rows);
        that.setData({
          currOrderPage: res.CurrentPage,
          totalOrderPage: res.TotalPage,
          ordersList: that.data.ordersList,
          orderLoading: false
        });
      }
    });
  },
  //滚动订单列表区域
  orderScrollToLower(e) {
    this.loadMoreOrderList();
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
        role: 'user',
        type: 'say',
        to: this.data.group
      }),
      success: res => {
        //console.log("发送成功");
      }
    });
  },
  sendOfflineMsg(content) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    //console.log("message-content:",content);
    http.Post("addon/wstim-chats-sendMsg", {
      tokenId: tokenId,
      isWeapp: 1,
      content: content,
      type: 'message',
      to: this.data.shopId
    }, function(res) {
      //console.log("message-return:",res);
      if (res.status == 1) {
        // 留言内容
        var data = [];
        var chatItem = {};
        chatItem.content = content;
        chatItem.from = that.data.userId;
        data.push(chatItem);
        var result = that.formatData(data);
        that.data.chatList.push.apply(that.data.chatList, result);
        that.setData({
          chatList: that.data.chatList
        });
        that.bottom();
        // 自动回复
        if (res.data && res.data.length > 0){
          var msgData = [];
          for(var i in res.data){
            setTimeout(function () {
              var chatItem = {};
              chatItem.content = res.data[i];
              chatItem.from = that.data.shopId;
              msgData.push(chatItem);
              var result = that.formatData(msgData);
              that.data.chatList.push.apply(that.data.chatList, result);
              that.setData({
                chatList: that.data.chatList
              });
              that.bottom();
            }, 200)
          }
        }
      } else {
        wx.showToast({
          title: '消息发送失败，请重试',
          icon: 'none',
          duration: 2000
        })
      }
    })
  },
  imInit() {
    this.connect();
  },
  connect() {
    var that = this;
    //console.log("ws:", app.globalData.ws);
    if (app.globalData.ws!=''){
      var webSocket = wx.connectSocket({
        url: app.globalData.ws,
        success: function (res) {
          //console.log('connectSocket success');
        }
      });
      this.setData({
        webSocket: webSocket
      });
    }

    webSocket.onOpen(function(res) {
      //console.log('socketOpen success');
      // 第一次进来先连接服务器
      var sendData = {
        type: 'login',
        uid: that.data.userId,
        userName: that.data.userData.userName,
        role: 'user',
        platform: 5,
        shopId: that.data.shopId,
        group: ''
      };
      webSocket.send({
        data: JSON.stringify(sendData),
        success: function(res) {
          //console.log('send-msg success');
        }
      })
    });

    webSocket.onMessage(function(res) {
      //console.log("receive-msg:", res);
      var _data = JSON.parse(res.data);
      if (!!_data.group) {
        var group = _data.group;
        that.setData({
          group: group
        });
      }
      switch (_data.type) {
        case 'chat':
          // 客服接待
          var chatItem = [];
          var receive = {};
          receive.groupName = _data.groupName;
          chatItem.push(receive);
          that.data.chatList.push.apply(that.data.chatList, chatItem);
          that.setData({
            offline: false,
            chatList: that.data.chatList
          });
          that.bottom();
          break;
        case 'say':
          if (_data.role == 'user') {
            // 接收消息(包括自己发送的)
            that.receiveMsg(_data);
            if (_data.from != that.data.userId) {
              that.setRead();
            }
          }
          break;
        case 'message':
          // 触发留言
          var chatItem = [];
          var receive = {};
          receive.noGroup = true;
          receive.noGroupText = "当前无客服在线，您可以留言，我们会尽快回复您。";
          chatItem.push(receive);
          that.data.chatList.push.apply(that.data.chatList, chatItem);
          that.setData({
            offline: true,
            chatList: that.data.chatList
          });
          that.bottom();
          break;
        case 'wait':
          // 排队状态
          that.disableSendMsg();
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
    http.Post("addon/wstim-chats-setRead", {
      tokenId: tokenId,
      isWeapp: 1,
      shopId: this.data.shopId
    }, function(res) {
      //console.log("setRead-res:", res);
    })
  },
  sendOrderNo(e) {
    var orderId = e.currentTarget.dataset.orderid;
    var orderNo = e.currentTarget.dataset.orderno;
    var content = JSON.stringify({
      type: "orders",
      orderId: orderId,
      content: "订单号：" + orderNo
    });
    this.sendMsg(content);
    this.bottom();
    this.setData({
      orderTier: false,
      showOrderStatus: false,
    })
  },
  sendGoodsName(e) {
    var goodsId = e.currentTarget.dataset.goodsid;
    var goodsName = e.currentTarget.dataset.goodsname;
    var delFlag = e.currentTarget.dataset.delflag;
    var content = JSON.stringify({
      type: "goods",
      goodsId: goodsId,
      content: goodsName
    });
    if (delFlag) {
      this.data.offline ? this.sendOfflineMsg(content) : this.sendMsg(content);
    } else {
      this.sendMsg(content);
    }

    if (delFlag) {
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
    }
    this.bottom();
    this.setData({
      recentTier: false,
      showRecentStatus: false,
    });
  },
  // 排队状态
  disableSendMsg() {
    this.setData({
      queueTier: true
    });
  },
  // 隐藏排队遮罩层
  noQueue() {
    this.setData({
      queueTier: false
    });
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
  },
  // 点击分数
  tapStar:function({currentTarget:{ dataset:{ i } }}){
      this.setData({score:i});
  },
  submitEval:function(){
    let that = this;
    const url = app.globalData.APIS['userEvalate'];
    const { score, shopId, group, _eval_flag } = this.data;
    const tokenId = app.globalData.tokenId;
    let postData = {
        tokenId,
        score,
        shopId,
        serviceId:group,
        isWeApp:1,
    }
    if(_eval_flag){
      return app.prompt('你已经评价过了哦');
    }
    http.Post(url, postData, function(res){
        app.prompt(res.msg);
        if(res.status==1){
            that.setData({
              _eval_flag:true,
              evalTier: false,
              showEvalStatus: false,
            });
        }
    })
  }
})