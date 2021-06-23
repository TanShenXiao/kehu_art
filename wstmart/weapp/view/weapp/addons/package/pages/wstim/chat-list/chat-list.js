// addons/package/pages/wstim/chat-list/chat-list.js
var http = require('../../../../../utils/request.js');
var httpp = require('../../../../../utils/request-p.js');
//获取应用实例
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    domain: app.globalData.domain,
    chatList:[], // 作为客户的聊天列表
    userChatList:[], // 作为客服的聊天列表
    webSocket:'',
    group:'',
    shopId:0,
    sendId:0,
    loginName:'',
    serviceId:0,
    workerName:'',
    userInfoObj: {},
    loading:true,
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.getChatList();
    if (app.globalData.userData.isService){
      this.getConfig();
    }
  },
  getChatList() {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post('addon/wstim-chats-getChatList', {
      tokenId: tokenId,
      isWeapp: 1
    }, function (res) {
      if (res.status == 1) {
        if(res.data && res.data.length > 0){
          that.setData({
            chatList: res.data
          });
        }
      }
    });
  },
  // 以用户的身份进入聊天室
  goWstim(e) {
    var shopId = e.currentTarget.dataset.shopid;
    var shopName = e.currentTarget.dataset.shopname;
    var shopImg = e.currentTarget.dataset.shopimg;
    wx.navigateTo({
      url: '../wstim?shopId=' + shopId + '&shopName=' + shopName + '&shopImg=' + shopImg
    });
  },
  // 以客服的身份进入聊天室
  goShopChat(e) {
    var userId = e.currentTarget.dataset.userid;
    var userName = e.currentTarget.dataset.username;
    var userPhoto = e.currentTarget.dataset.userphoto;
    var shopId = this.data.shopId;
    var servicepId = this.data.serviceId;
    var workerName = this.data.workerName;
    // console.log("userId:", userId);
    // console.log("userName:", userName);
    // console.log("shopId:", shopId);
    // console.log("workerName:", workerName);
    wx.navigateTo({
      url: '../shop-chat/shop-chat?userId=' + userId + '&userName=' + userName + '&shopId=' + shopId + '&workerName=' + workerName + '&userPhoto=' + userPhoto + '&serviceId=' + servicepId
    });
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    this.setData({
      loading:true
    });
    app.editTabBar();
    //console.log("this.data.webSocket:", this.data.webSocket);
    if(this.data.webSocket.readyState == 1){
      // 更新底部消息数
      this.sendListenerMsg();
      //this.sendLoginMsg();
    }
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
    
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
    this.getChatList();
    wx.stopPullDownRefresh();
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  },
  getConfig: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post('addon/wstim-shopchats-getBaseData', {
      tokenId: tokenId,
      isWeapp:1
    }, function (res) {
      //console.log("baseData:", res);
      if (res.status == 1) {
        var baseData = res.data;
        that.setData({
          serviceId: baseData.serviceId,
          sendId: baseData.userId,
          shopId: baseData.shopId,
          loginName: baseData.workerName,
          workerName: baseData.workerName,
        });
        that.imInit();
      }
    });
  },
  imInit() {
    this.connect();
  },
  connect() {
    var that = this;
    //console.log("ws:", app.globalData.ws);
    // if (app.globalData.ws != '') {
    //   var webSocket = wx.connectSocket({
    //     url: app.globalData.ws,
    //     success: function (res) {
    //       console.log('chat-list-connectSocket success');
    //     }
    //   });
    //   this.setData({
    //     webSocket: webSocket
    //   });
    // }
        /*
    webSocket.onOpen(function (res) {
      console.log('chat-list-socketOpen success');
      // 第一次进来先连接服务器
      var sendData = {
        uid: that.data.sendId,// 用户id
        userName: that.data.loginName,// 用户名
        role: 'lisenter',// 角色
        shopId: that.data.shopId// 所属店铺id
      };
      webSocket.send({
        data: JSON.stringify(sendData),
        success: function (res) {
          console.log('chat-list-send-msg1 success');
        }
      })
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
          console.log('chat-list-send-msg2 success');
        }
      })
    });
    */
    // 不新建websocket，使用全局的websocket
    var webSocket = app.globalData.globalWebSocket;
    this.setData({
      webSocket:webSocket
    });
    this.sendListenerMsg();
    this.sendLoginMsg();

    webSocket.onMessage(function (res) {
      //console.log("chat-list-receive-msg:", res);
      var _data = JSON.parse(res.data);
      if (!!_data.group) {
        var group = _data.group;
        that.setData({
          group: group
        });
      }
      switch (_data.type) {
        // 客服 
        case 'say':
          // 接收某人消息
          if (_data.role == 'worker') {
            if (_data.from != that.data.serviceId) {
              that.updateUserChatList(_data);
            }  
          }  
          break;
        case 'load':
          // 加入聊天列表
          //console.log("_data.list:",_data.list);
          that.loadUserInfo(_data.list);
          break;
        case 'unReadMsg':
          // 加载未读留言
          //console.log("_data.list:", _data.list);
          that.loadUserInfo(_data.list);
          break;
        case 'conversation':
          // 判断用户是否存在聊天列表中，如果不存在则添加用户信息到聊天列表
          var userChatList = that.data.userChatList;
          var isChatFlag = false;
          for (var i in userChatList) {
            if (_data.from == userChatList[i].userId) {
              isChatFlag = true;
              break;
            }
          }
          if(!isChatFlag){
            that.getUserInfo(_data, _data.from, false, false);
          }
          
          // 标识用户为在线
          that.setUserOnlineStatus(_data.from, '');
          break;
        case 'userExit':
          // 接收消息者已离线
          that.setUserOnlineStatus(_data.clientUid,'offline');
          break;
      }
    });

    webSocket.onError(function (res) {
      //console.log('WebSocketError!', res);
    });

    webSocket.onClose(function (res) {
      //console.log('WebSocketClosed!');
    });
  },
  sendListenerMsg(){
    var webSocket = this.data.webSocket;
    var sendData = {
      uid: this.data.sendId,// 用户id
      userName: this.data.loginName,// 用户名
      role: 'lisenter',// 角色
      shopId: this.data.shopId// 所属店铺id
    };
    webSocket.send({
      data: JSON.stringify(sendData),
      success: function (res) {
        //console.log('chat-list-send-lisenter success');
      }
    })
  },
  sendLoginMsg(){
    var webSocket = this.data.webSocket;
    var sendData = {
      type: 'login',
      serviceId: this.data.serviceId,
      userName: this.data.workerName,
      role: 'worker',
      shopId: this.data.shopId
    };
    webSocket.send({
      data: JSON.stringify(sendData),
      success: function (res) {
        //console.log('chat-list-send-login success');
      }
    })
  },
  loadUserInfo(data,flag){
    this.data.userInfoObj = {};
    this.data.userChatList = [];
    this.setData({
      userInfoObj:{},
      userChatList:[]
    });
    var userIds = Object.keys(data);
    for (var i in userIds) {
      var _userId = userIds[i];
      if (!this.data.userInfoObj[_userId]) {
        // 先设置为空对象
        this.data.userInfoObj[_userId] = {};
        this.getUserInfo(data,_userId,true);
      }
    }
  },
  // 第三个参数代表是否要格式化数据
  // 第四个参数代表是否要显示用户头像的消息数，true为显示，false不显示
  getUserInfo(data,userId, flag, unReadNumFlag) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post('addon/wstim-shopchats-getUserInfo', {
      tokenId: tokenId,
      isWeapp: 1,
      userId: userId
    }, function (res) {
      that.data.userInfoObj[userId] = res;
      that.setData({
        userInfoObj: that.data.userInfoObj,
      });
      //console.log("userInfoObj:", that.data.userInfoObj);
      if (flag) {
        that.formatData(data, userId);
      }else{
        that.addUserChatList(data, userId, unReadNumFlag);
      }
    });
  },
  formatData(data,userId){
    var chatRes = [];
    for (var i in data) {
      if(i == userId){
        var message = {};
        var _data = data[i];
        var content = '';
        if (_data.content && _data.content.content) {
          content = this.replaceContent(_data.content.content);
        }
        message.createTime = _data.createTime;
        message.unReadNum = _data.unReadNum;
        message.content = content;
        //console.log("userId:",userId);
        //console.log("userInfo1:", this.data.userInfoObj);
        //console.log("userInfo2:", this.data.userInfoObj[userId]);

        message.userId = userId;
        message.offline = '';
        message.userName = this.data.userInfoObj[userId].loginName;
        message.userPhoto = this.data.userInfoObj[userId].userPhoto;
        chatRes.push(message);
        this.data.userChatList.push.apply(this.data.userChatList,chatRes);
        this.setData({
          userChatList: this.data.userChatList,
          loading:false
        })
      }
    }  
    //return chatRes;
  },
  // 当用户发消息给客服，把用户信息显示在客服的聊天列表，unReadNumFlag传true，代表消息数+1
  // 当用户开启与客服聊天的界面，把用户信息显示在客服的聊天列表，unReadNumFlag传false，代表消息数不用加
  addUserChatList(data,userId,unReadNumFlag){
    var chatRes = [];
    var message = {};
    message.userId = userId; 
    message.offline = '';
    message.userName = this.data.userInfoObj[userId].loginName;
    message.userPhoto = this.data.userInfoObj[userId].userPhoto;
    message.createTime = data.createTime;
    message.unReadNum = 0;
    if(unReadNumFlag){
      message.unReadNum = 1;
    }
    if (data.content && data.content.content) {
      message.content = this.replaceContent(data.content.content);
    }else{
      message.content = this.replaceContent(data.content);
    }
    chatRes.push(message);
    this.data.userChatList.unshift.apply(this.data.userChatList, chatRes);
    this.setData({
      userChatList: this.data.userChatList,
      loading:false
    });
  },
  updateUserChatList(data){
    // 判断当前用户是否已经存在会话列表中
    var userId = data.from;
    var content = this.replaceContent(data.content);
    var time = data.createTime;
    var userChatList = this.data.userChatList;
    if (!this.data.userInfoObj[userId]) {
      //console.log("该用户不存在会话列表中");
      this.getUserInfo(data,userId, false,true);
    }else{
      //console.log("该用户存在会话列表中");
      for (var i in userChatList){
        if (userId == userChatList[i].userId){
          userChatList[i].unReadNum += 1;
          userChatList[i].content = content;
          userChatList[i].createTime = time;
        }
      }
      this.setData({
        userChatList:userChatList
      });
    }  
  },
  setUserOnlineStatus(userId,status){
    var userChatList = this.data.userChatList;
    for (var i in userChatList) {
      if (userId == userChatList[i].userId) {
        userChatList[i].offline = status;
      }
    }
    this.setData({
      userChatList: userChatList
    });
  },
  // 替换消息中的图片、链接
  replaceContent(content) {
    if (this.isJsonString(content)) {
      var _json = JSON.parse(content);
      switch (_json.type) {
        case 'goods':
          content = '[链接]'
          break;
        case 'orders':
          content = '[链接]'
          break;
        case 'image':
          content = '[图片]';
          break;
      }
    }
    return content.replace(/<a\b[^>]+[^>]*>([\s\S]*?)<\/a>/g, '[链接]').replace(/<img\b.*\/>/g, '[图片]');
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

})